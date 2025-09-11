<?php

class OrderController extends Controller {
    
    public function __construct() {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Mostrar página de checkout
    public function checkout() {
        if (empty($_SESSION['cart'])) {
            header('Location: /prueba-php/public/cart');
            exit;
        }
        
        $data = [
            'title' => 'Checkout - Finalizar Compra',
            'cart_items' => $_SESSION['cart'] ?? [],
            'cart_count' => $this->getCartCount(),
            'cart_total' => $this->getCartTotal(),
            'user' => $this->getUserData()
        ];
        
        $this->loadView('pages/checkout', $data);
    }
    
    // Procesar pedido
    public function processOrder() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        if (empty($_SESSION['cart'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El carrito está vacío']);
            return;
        }
        
        // Validar datos del formulario
        $required_fields = ['nombre', 'apellidos', 'email', 'direccion', 'ciudad', 'codigo_postal', 'provincia'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => "El campo $field es requerido"]);
                return;
            }
        }
        
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            
            // Calcular totales
            $subtotal = $this->getCartTotal();
            $descuento = $this->calculateDiscount($_POST['cupon'] ?? '');
            $envio = $this->calculateShipping($subtotal - $descuento);
            $total = $subtotal - $descuento + $envio;
            
            // Crear pedido
            $stmt = $pdo->prepare("
                INSERT INTO pedidos (
                    usuario_id, email, nombre, apellidos, telefono, 
                    direccion, ciudad, codigo_postal, provincia, 
                    total, descuento, envio, estado, metodo_pago, notas
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente', ?, ?)
            ");
            
            $usuario_id = $_SESSION['user_id'] ?? null;
            $stmt->execute([
                $usuario_id,
                $_POST['email'],
                $_POST['nombre'],
                $_POST['apellidos'],
                $_POST['telefono'] ?? null,
                $_POST['direccion'],
                $_POST['ciudad'],
                $_POST['codigo_postal'],
                $_POST['provincia'],
                $total,
                $descuento,
                $envio,
                $_POST['metodo_pago'] ?? 'tarjeta',
                $_POST['notas'] ?? null
            ]);
            
            $pedido_id = $pdo->lastInsertId();
            
            // Crear items del pedido
            $stmt = $pdo->prepare("
                INSERT INTO pedido_items (
                    pedido_id, producto_id, nombre_producto, precio, cantidad, subtotal
                ) VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            foreach ($_SESSION['cart'] as $item) {
                $subtotal_item = $item['precio'] * $item['quantity'];
                $stmt->execute([
                    $pedido_id,
                    $item['id'],
                    $item['nombre'],
                    $item['precio'],
                    $item['quantity'],
                    $subtotal_item
                ]);
                
                // Actualizar stock
                $update_stock = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
                $update_stock->execute([$item['quantity'], $item['id']]);
            }
            
            // Actualizar cupón si se usó
            if (!empty($_POST['cupon'])) {
                $this->updateCouponUsage($_POST['cupon'], $pdo);
            }
            
            $pdo->commit();
            
            // Limpiar carrito
            $_SESSION['cart'] = [];
            
            echo json_encode([
                'success' => true,
                'message' => 'Pedido procesado correctamente',
                'pedido_id' => $pedido_id,
                'total' => $total
            ]);
            
        } catch (Exception $e) {
            if (isset($pdo)) {
                $pdo->rollback();
            }
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al procesar el pedido: ' . $e->getMessage()]);
        }
    }
    
    // Verificar cupón
    public function validateCoupon() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $codigo = $_POST['codigo'] ?? '';
        $total = floatval($_POST['total'] ?? 0);
        
        if (empty($codigo)) {
            echo json_encode(['success' => false, 'message' => 'Código de cupón requerido']);
            return;
        }
        
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->prepare("
                SELECT * FROM cupones 
                WHERE codigo = ? AND activo = 1 
                AND (fecha_inicio IS NULL OR fecha_inicio <= CURDATE())
                AND (fecha_fin IS NULL OR fecha_fin >= CURDATE())
                AND (usos_maximos IS NULL OR usos_actuales < usos_maximos)
            ");
            $stmt->execute([$codigo]);
            $cupon = $stmt->fetch(PDO::FETCH_OBJ);
            
            if (!$cupon) {
                echo json_encode(['success' => false, 'message' => 'Cupón no válido o expirado']);
                return;
            }
            
            if ($total < $cupon->minimo_compra) {
                echo json_encode([
                    'success' => false, 
                    'message' => "Compra mínima requerida: " . number_format($cupon->minimo_compra, 2) . "€"
                ]);
                return;
            }
            
            $descuento = $this->calculateDiscountAmount($cupon, $total);
            
            echo json_encode([
                'success' => true,
                'message' => 'Cupón aplicado correctamente',
                'descuento' => $descuento,
                'cupon' => [
                    'codigo' => $cupon->codigo,
                    'descripcion' => $cupon->descripcion,
                    'tipo' => $cupon->tipo,
                    'valor' => $cupon->valor
                ]
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al validar cupón: ' . $e->getMessage()]);
        }
    }
    
    // Añadir a wishlist
    public function addToWishlist() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $product_id = $_POST['product_id'] ?? null;
        
        if (!$product_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de producto requerido']);
            return;
        }
        
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $usuario_id = $_SESSION['user_id'] ?? null;
            $session_id = $usuario_id ? null : session_id();
            
            $stmt = $pdo->prepare("
                INSERT IGNORE INTO wishlist (usuario_id, session_id, producto_id) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$usuario_id, $session_id, $product_id]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Producto añadido a favoritos']);
            } else {
                echo json_encode(['success' => false, 'message' => 'El producto ya está en favoritos']);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    
    // Obtener wishlist
    public function getWishlist() {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $usuario_id = $_SESSION['user_id'] ?? null;
            $session_id = $usuario_id ? null : session_id();
            
            if ($usuario_id) {
                $stmt = $pdo->prepare("
                    SELECT p.*, w.fecha_creacion as fecha_favorito
                    FROM wishlist w
                    JOIN productos p ON w.producto_id = p.id
                    WHERE w.usuario_id = ? AND p.activo = 1
                    ORDER BY w.fecha_creacion DESC
                ");
                $stmt->execute([$usuario_id]);
            } else {
                $stmt = $pdo->prepare("
                    SELECT p.*, w.fecha_creacion as fecha_favorito
                    FROM wishlist w
                    JOIN productos p ON w.producto_id = p.id
                    WHERE w.session_id = ? AND p.activo = 1
                    ORDER BY w.fecha_creacion DESC
                ");
                $stmt->execute([$session_id]);
            }
            
            $wishlist = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            $data = [
                'title' => 'Mis Favoritos',
                'wishlist' => $wishlist
            ];
            
            $this->loadView('pages/wishlist', $data);
            
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    // Métodos auxiliares
    private function getCartCount() {
        $count = 0;
        foreach ($_SESSION['cart'] ?? [] as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }
    
    private function getCartTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] ?? [] as $item) {
            $total += $item['precio'] * $item['quantity'];
        }
        return $total;
    }
    
    private function getUserData() {
        if (isset($_SESSION['user_id'])) {
            return [
                'nombre' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
                'telefono' => $_SESSION['user_phone'] ?? '',
                'direccion' => $_SESSION['user_address'] ?? ''
            ];
        }
        return null;
    }
    
    private function calculateDiscount($cupon_codigo) {
        if (empty($cupon_codigo)) return 0;
        
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->prepare("
                SELECT * FROM cupones 
                WHERE codigo = ? AND activo = 1 
                AND (fecha_inicio IS NULL OR fecha_inicio <= CURDATE())
                AND (fecha_fin IS NULL OR fecha_fin >= CURDATE())
                AND (usos_maximos IS NULL OR usos_actuales < usos_maximos)
            ");
            $stmt->execute([$cupon_codigo]);
            $cupon = $stmt->fetch(PDO::FETCH_OBJ);
            
            if (!$cupon) return 0;
            
            $total = $this->getCartTotal();
            if ($total < $cupon->minimo_compra) return 0;
            
            return $this->calculateDiscountAmount($cupon, $total);
            
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function calculateDiscountAmount($cupon, $total) {
        if ($cupon->tipo === 'porcentaje') {
            $descuento = ($total * $cupon->valor) / 100;
            if ($cupon->maximo_descuento && $descuento > $cupon->maximo_descuento) {
                $descuento = $cupon->maximo_descuento;
            }
            return $descuento;
        } else {
            return $cupon->valor;
        }
    }
    
    private function calculateShipping($subtotal) {
        // Envío gratis a partir de 50€
        return $subtotal >= 50 ? 0 : 5.99;
    }
    
    private function updateCouponUsage($codigo, $pdo) {
        $stmt = $pdo->prepare("UPDATE cupones SET usos_actuales = usos_actuales + 1 WHERE codigo = ?");
        $stmt->execute([$codigo]);
    }
    
    // Eliminar de wishlist
    public function removeFromWishlist() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $product_id = $input['product_id'] ?? null;
        
        if (!$product_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de producto requerido']);
            return;
        }
        
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $usuario_id = $_SESSION['user_id'] ?? null;
            $session_id = $usuario_id ? null : session_id();
            
            if ($usuario_id) {
                $stmt = $pdo->prepare("DELETE FROM wishlist WHERE usuario_id = ? AND producto_id = ?");
                $stmt->execute([$usuario_id, $product_id]);
            } else {
                $stmt = $pdo->prepare("DELETE FROM wishlist WHERE session_id = ? AND producto_id = ?");
                $stmt->execute([$session_id, $product_id]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Producto eliminado de favoritos']);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    
    // Limpiar wishlist
    public function clearWishlist() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $usuario_id = $_SESSION['user_id'] ?? null;
            $session_id = $usuario_id ? null : session_id();
            
            if ($usuario_id) {
                $stmt = $pdo->prepare("DELETE FROM wishlist WHERE usuario_id = ?");
                $stmt->execute([$usuario_id]);
            } else {
                $stmt = $pdo->prepare("DELETE FROM wishlist WHERE session_id = ?");
                $stmt->execute([$session_id]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Lista de favoritos limpiada']);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
