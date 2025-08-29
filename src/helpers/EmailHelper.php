<?php

// Importar la clase Database
if (file_exists(__DIR__ . '/../models/Database.php')) {
    require_once __DIR__ . '/../models/Database.php';
}

class EmailHelper {
    private $mailer;
    private $adminEmail = 'admin@mariscales.com';
    private $adminName = 'Administrador Filá Mariscales';

    public function __construct() {
        // Configuración básica para envío de emails
        // En producción, usarías PHPMailer o similar
        $this->adminEmail = 'admin@mariscales.com';
        $this->adminName = 'Administrador Filá Mariscales';
    }

    /**
     * Envía email de bienvenida a un nuevo usuario
     */
    public function sendWelcomeEmail($userData) {
        $subject = 'Bienvenido a Filá Mariscales - Tus Credenciales de Acceso';
        
        $message = $this->getWelcomeEmailTemplate($userData);
        
        return $this->sendEmail($userData['email'], $subject, $message);
    }

    /**
     * Envía email de reseteo de contraseña
     */
    public function sendPasswordResetEmail($userData, $newPassword) {
        $subject = 'Contraseña Actualizada - Filá Mariscales';
        
        $message = $this->getPasswordResetTemplate($userData, $newPassword);
        
        return $this->sendEmail($userData['email'], $subject, $message);
    }

    /**
     * Envía notificación al admin sobre nuevo usuario
     */
    public function sendAdminNotification($userData, $action = 'created') {
        $subject = 'Nuevo Usuario Registrado - Panel de Control';
        
        $message = $this->getAdminNotificationTemplate($userData, $action);
        
        return $this->sendEmail($this->adminEmail, $subject, $message);
    }

    /**
     * Template para email de bienvenida
     */
    private function getWelcomeEmailTemplate($userData) {
        // Intentar obtener la contraseña real del usuario
        $password = $this->getUserPassword($userData['id'] ?? null);
        
        // Si no se puede obtener la contraseña real, usar la por defecto
        if (!$password) {
            $password = $this->getDefaultPassword($userData['rol']);
        }
        
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #8B0000; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .credentials { background: #fff; padding: 15px; border-left: 4px solid #8B0000; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                .btn { display: inline-block; padding: 10px 20px; background: #8B0000; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🏛️ Filá Mariscales</h1>
                    <p>Caballeros Templarios de Elche</p>
                </div>
                
                <div class='content'>
                    <h2>¡Bienvenido, {$userData['nombre']}!</h2>
                    
                    <p>Has sido registrado exitosamente en nuestro sistema como <strong>{$userData['rol']}</strong>.</p>
                    
                    <div class='credentials'>
                        <h3>🔐 Tus Credenciales de Acceso:</h3>
                        <p><strong>Email:</strong> {$userData['email']}</p>
                        <p><strong>Contraseña:</strong> {$password}</p>
                        <p><strong>Rol:</strong> " . ucfirst($userData['rol']) . "</p>
                    </div>
                    
                    <p><strong>⚠️ IMPORTANTE:</strong></p>
                    <ul>
                        <li>Cambia tu contraseña en tu primer acceso</li>
                        <li>Mantén tus credenciales seguras</li>
                        <li>Contacta al administrador si tienes problemas</li>
                    </ul>
                    
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='http://localhost/prueba-php/public/login' class='btn'>Iniciar Sesión</a>
                    </p>
                </div>
                
                <div class='footer'>
                    <p>© 2025 Filá Mariscales. Todos los derechos reservados.</p>
                    <p>Esta es una notificación automática, no respondas a este email.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Template para email de reseteo de contraseña
     */
    private function getPasswordResetTemplate($userData, $newPassword) {
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #8B0000; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .credentials { background: #fff; padding: 15px; border-left: 4px solid #8B0000; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                .btn { display: inline-block; padding: 10px 20px; background: #8B0000; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🔑 Filá Mariscales</h1>
                    <p>Contraseña Actualizada</p>
                </div>
                
                <div class='content'>
                    <h2>Hola, {$userData['nombre']}!</h2>
                    
                    <p>Tu contraseña ha sido actualizada por el administrador.</p>
                    
                    <div class='credentials'>
                        <h3>🔐 Tus Nuevas Credenciales:</h3>
                        <p><strong>Email:</strong> {$userData['email']}</p>
                        <p><strong>Nueva Contraseña:</strong> {$newPassword}</p>
                        <p><strong>Rol:</strong> " . ucfirst($userData['rol']) . "</p>
                    </div>
                    
                    <p><strong>⚠️ IMPORTANTE:</strong></p>
                    <ul>
                        <li>Cambia tu contraseña en tu próximo acceso</li>
                        <li>Mantén tus credenciales seguras</li>
                        <li>Contacta al administrador si tienes problemas</li>
                    </ul>
                    
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='http://localhost/prueba-php/public/login' class='btn'>Iniciar Sesión</a>
                    </p>
                </div>
                
                <div class='footer'>
                    <p>© 2025 Filá Mariscales. Todos los derechos reservados.</p>
                    <p>Esta es una notificación automática, no respondas a este email.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Template para notificación al admin
     */
    private function getAdminNotificationTemplate($userData, $action) {
        $actionText = $action === 'created' ? 'creado' : 'actualizado';
        $password = $this->getDefaultPassword($userData['rol']);
        
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #8B0000; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .user-info { background: #fff; padding: 15px; border-left: 4px solid #8B0000; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                .btn { display: inline-block; padding: 10px 20px; background: #8B0000; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>👤 Panel de Control</h1>
                    <p>Notificación de Usuario</p>
                </div>
                
                <div class='content'>
                    <h2>Usuario {$actionText} exitosamente</h2>
                    
                    <div class='user-info'>
                        <h3>📋 Información del Usuario:</h3>
                        <p><strong>Nombre:</strong> {$userData['nombre']} {$userData['apellidos']}</p>
                        <p><strong>Email:</strong> {$userData['email']}</p>
                        <p><strong>Rol:</strong> " . ucfirst($userData['rol']) . "</p>
                        <p><strong>Contraseña:</strong> {$password}</p>
                        <p><strong>Fecha:</strong> " . date('d/m/Y H:i:s') . "</p>
                    </div>
                    
                    <p><strong>✅ Acciones realizadas:</strong></p>
                    <ul>
                        <li>Usuario {$actionText} en la base de datos</li>
                        <li>Email enviado al usuario con sus credenciales</li>
                        <li>Notificación registrada en el sistema</li>
                    </ul>
                    
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='http://localhost/prueba-php/public/admin/usuarios' class='btn'>Ver Panel de Control</a>
                    </p>
                </div>
                
                <div class='footer'>
                    <p>© 2025 Filá Mariscales. Sistema de Administración.</p>
                    <p>Esta es una notificación automática del sistema.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Obtiene la contraseña real del usuario desde la base de datos
     */
    private function getUserPassword($userId) {
        try {
            $db = new Database();
            $db->query('SELECT password FROM users WHERE id = ?');
            $db->bind(1, $userId);
            $result = $db->single();
            
            if ($result && $result->password) {
                return $result->password;
            }
        } catch (Exception $e) {
            error_log("Error obteniendo contraseña del usuario: " . $e->getMessage());
        }
        
        return null;
    }

    /**
     * Obtiene la contraseña por defecto según el rol
     */
    private function getDefaultPassword($rol) {
        switch ($rol) {
            case 'admin':
                return 'admin123';
            case 'socio':
                return 'socio123';
            case 'user':
            default:
                return 'password123';
        }
    }

    /**
     * Función principal para enviar emails
     */
    private function sendEmail($to, $subject, $message) {
        // En desarrollo, simulamos el envío
        // En producción, usarías PHPMailer o similar
        
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . $this->adminEmail,
            'Reply-To: ' . $this->adminEmail,
            'X-Mailer: PHP/' . phpversion()
        ];

        // Simular envío (en desarrollo)
        $emailData = [
            'to' => $to,
            'subject' => $subject,
            'message' => $message,
            'headers' => implode("\r\n", $headers),
            'sent_at' => date('Y-m-d H:i:s'),
            'status' => 'sent'
        ];

        // Guardar en log para desarrollo
        $this->logEmail($emailData);

        // En producción, descomentar esta línea:
        // return mail($to, $subject, $message, implode("\r\n", $headers));
        
        return true; // Simular éxito
    }

    /**
     * Guarda el email en un log para desarrollo
     */
    private function logEmail($emailData) {
        $logFile = 'email_log.txt';
        $logEntry = "=== EMAIL SENT ===\n";
        $logEntry .= "Date: " . $emailData['sent_at'] . "\n";
        $logEntry .= "To: " . $emailData['to'] . "\n";
        $logEntry .= "Subject: " . $emailData['subject'] . "\n";
        $logEntry .= "Status: " . $emailData['status'] . "\n";
        $logEntry .= "==================\n\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
