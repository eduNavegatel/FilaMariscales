<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    private $table = 'users';

    // Register user
    public function register($data) {
        $this->db->query('INSERT INTO ' . $this->table . ' (nombre, apellidos, email, password, rol, activo, fecha_registro, ultimo_acceso) 
                         VALUES(:nombre, :apellidos, :email, :password, :rol, :activo, NOW(), NOW())');
        
        // Bind values
        $this->db->bind(':nombre', $data['nombre']);
        $this->db->bind(':apellidos', $data['apellidos']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':rol', $data['rol'] ?? 'user');
        $this->db->bind(':activo', $data['activo'] ?? 1);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    // Find user by ID
    public function findUserById($id) {
        error_log("User::findUserById called with ID: " . $id);
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        error_log("User::findUserById result: " . ($result ? 'found' : 'not found'));
        return $result;
    }

    // Get user by ID (alias for findUserById)
    public function getUserById($id) {
        return $this->findUserById($id);
    }

    // Login user
    public function login($email, $password) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if ($row) {
            $hashed_password = $row->password;
            if (password_verify($password, $hashed_password)) {
                // Update last login time
                $this->updateLastLogin($row->id);
                
                // Guardar contraseña en texto plano para mostrar al admin (si el campo existe)
                $this->savePasswordForAdmin($row->id, $password);
                
                return $row;
            }
        }
        return false;
    }
    
    // Guardar contraseña en texto plano para mostrar al admin
    private function savePasswordForAdmin($userId, $password) {
        try {
            $this->db->query('UPDATE ' . $this->table . ' SET password_plain = :password WHERE id = :id');
            $this->db->bind(':password', $password);
            $this->db->bind(':id', $userId);
            $this->db->execute();
        } catch (Exception $e) {
            // Si falla, no hacer nada (el campo no existe)
        }
    }

    // Update last login time
    public function updateLastLogin($userId) {
        $this->db->query('UPDATE ' . $this->table . ' SET ultimo_acceso = NOW() WHERE id = :id');
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    // Create password reset token
    public function createPasswordResetToken($email, $token, $expires) {
        // Update user with reset token
        $this->db->query('UPDATE ' . $this->table . ' SET token_reset = :token, token_expira = :expires WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->bind(':token', $token);
        $this->db->bind(':expires', $expires);
        
        return $this->db->execute();
    }

    // Find user by reset token
    public function findUserByResetToken($token) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE token_reset = :token AND token_expira > NOW()');
        $this->db->bind(':token', $token);
        return $this->db->single();
    }

    // Update user password
    public function updatePassword($userId, $hashedPassword, $plainPassword = null) {
        // Verificar si los campos adicionales existen
        try {
            $this->db->query('UPDATE ' . $this->table . ' SET password = :password, temp_password = NULL, temp_password_created = NULL, password_plain = :plain_password WHERE id = :id');
            $this->db->bind(':password', $hashedPassword);
            $this->db->bind(':plain_password', $plainPassword);
            $this->db->bind(':id', $userId);
            return $this->db->execute();
        } catch (Exception $e) {
            // Si falla, usar consulta sin los campos adicionales
            $this->db->query('UPDATE ' . $this->table . ' SET password = :password WHERE id = :id');
            $this->db->bind(':password', $hashedPassword);
            $this->db->bind(':id', $userId);
            return $this->db->execute();
        }
    }
    
    // Update user password with temporary password
    public function updatePasswordWithTemp($userId, $hashedPassword, $tempPassword) {
        // Verificar si los campos temp_password existen
        try {
            $this->db->query('UPDATE ' . $this->table . ' SET password = :password, temp_password = :temp_password, temp_password_created = NOW() WHERE id = :id');
            $this->db->bind(':password', $hashedPassword);
            $this->db->bind(':temp_password', $tempPassword);
            $this->db->bind(':id', $userId);
            return $this->db->execute();
        } catch (Exception $e) {
            // Si falla, solo actualizar la contraseña normal
            $this->db->query('UPDATE ' . $this->table . ' SET password = :password WHERE id = :id');
            $this->db->bind(':password', $hashedPassword);
            $this->db->bind(':id', $userId);
            return $this->db->execute();
        }
    }
    
    // Clear temporary password
    public function clearTempPassword($userId) {
        // Verificar si los campos temp_password existen
        try {
            $this->db->query('UPDATE ' . $this->table . ' SET temp_password = NULL, temp_password_created = NULL WHERE id = :id');
            $this->db->bind(':id', $userId);
            return $this->db->execute();
        } catch (Exception $e) {
            // Si falla, no hacer nada (los campos no existen)
            return true;
        }
    }

    // Delete reset token
    public function deleteResetToken($token) {
        $this->db->query('UPDATE ' . $this->table . ' SET token_reset = NULL, token_expira = NULL WHERE token_reset = :token');
        $this->db->bind(':token', $token);
        return $this->db->execute();
    }

    // Delete user
    public function deleteUser($id) {
        $this->db->query('DELETE FROM users WHERE id = :id');
        
        // Bind values
        $this->db->bind(':id', $id);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get user count for dashboard
    public function getUserCount() {
        $this->db->query('SELECT COUNT(*) as count FROM ' . $this->table);
        $result = $this->db->single();
        return $result->count;
    }

    // Get recent users for dashboard
    public function getRecentUsers($limit = 5) {
        $this->db->query('SELECT id, nombre, apellidos, email, rol, fecha_registro FROM ' . $this->table . ' ORDER BY fecha_registro DESC LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Get all users with pagination
    public function getAllUsers($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        // Verificar si los campos adicionales existen antes de incluirlos
        try {
            $this->db->query('SELECT id, nombre, apellidos, email, rol, activo, fecha_registro, ultimo_acceso, temp_password, temp_password_created, password_plain FROM ' . $this->table . ' ORDER BY fecha_registro DESC LIMIT :limit OFFSET :offset');
        } catch (Exception $e) {
            // Si falla, usar consulta sin los campos adicionales
            $this->db->query('SELECT id, nombre, apellidos, email, rol, activo, fecha_registro, ultimo_acceso FROM ' . $this->table . ' ORDER BY fecha_registro DESC LIMIT :limit OFFSET :offset');
        }
        $this->db->bind(':limit', $perPage);
        $this->db->bind(':offset', $offset);
        $result = $this->db->resultSet();
        
        // Agregar campos adicionales como null si no existen
        foreach ($result as $user) {
            if (!isset($user->temp_password)) {
                $user->temp_password = null;
                $user->temp_password_created = null;
            }
            if (!isset($user->password_plain)) {
                $user->password_plain = null;
            }
        }
        
        return $result;
    }

    // Count all users
    public function countAllUsers() {
        $this->db->query('SELECT COUNT(*) as count FROM ' . $this->table);
        $result = $this->db->single();
        return $result->count;
    }

    // Update user
    public function updateUser($data) {
        $query = 'UPDATE ' . $this->table . ' SET nombre = :nombre, apellidos = :apellidos, email = :email, rol = :rol, activo = :activo';
        
        // Add password to query if provided
        if (!empty($data['password'])) {
            $query .= ', password = :password, password_plain = :password_plain';
        }
        
        $query .= ' WHERE id = :id';
        
        $this->db->query($query);
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':nombre', $data['nombre']);
        $this->db->bind(':apellidos', $data['apellidos']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':rol', $data['rol']);
        $this->db->bind(':activo', $data['activo']);

        if (!empty($data['password'])) {
            $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
            $this->db->bind(':password_plain', $data['password']);
        }

        return $this->db->execute();
    }


    // Update user status (activate/deactivate)
    public function updateUserStatus($id, $status) {
        $this->db->query('UPDATE ' . $this->table . ' SET activo = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
