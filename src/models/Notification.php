<?php

class Notification {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    private $table = 'system_notifications';

    /**
     * Crea una nueva notificación
     */
    public function createNotification($data) {
        $query = 'INSERT INTO ' . $this->table . ' 
                 (type, user_id, user_email, user_name, user_role, action, message, email_sent, email_status) 
                 VALUES (:type, :user_id, :user_email, :user_name, :user_role, :action, :message, :email_sent, :email_status)';
        
        $this->db->query($query);
        
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':user_email', $data['user_email']);
        $this->db->bind(':user_name', $data['user_name']);
        $this->db->bind(':user_role', $data['user_role']);
        $this->db->bind(':action', $data['action']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':email_sent', $data['email_sent'] ?? 0);
        $this->db->bind(':email_status', $data['email_status'] ?? 'pending');

        return $this->db->execute();
    }

    /**
     * Obtiene las notificaciones recientes
     */
    public function getRecentNotifications($limit = 10) {
        $query = 'SELECT * FROM ' . $this->table . ' 
                 ORDER BY created_at DESC 
                 LIMIT :limit';
        
        $this->db->query($query);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    /**
     * Obtiene notificaciones no verificadas
     */
    public function getUnverifiedNotifications($limit = 5) {
        $query = 'SELECT * FROM ' . $this->table . ' 
                 WHERE verified = 0 
                 ORDER BY created_at DESC 
                 LIMIT :limit';
        
        $this->db->query($query);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    /**
     * Marca una notificación como verificada
     */
    public function markAsVerified($notificationId, $verifiedBy = null) {
        $query = 'UPDATE ' . $this->table . ' 
                 SET verified = 1, verified_by = :verified_by, verified_at = NOW() 
                 WHERE id = :id';
        
        $this->db->query($query);
        $this->db->bind(':id', $notificationId);
        $this->db->bind(':verified_by', $verifiedBy);
        
        return $this->db->execute();
    }

    /**
     * Actualiza el estado del email
     */
    public function updateEmailStatus($notificationId, $status, $errorMessage = null) {
        $query = 'UPDATE ' . $this->table . ' 
                 SET email_sent = :email_sent, email_status = :email_status 
                 WHERE id = :id';
        
        $this->db->query($query);
        $this->db->bind(':id', $notificationId);
        $this->db->bind(':email_sent', $status === 'sent' ? 1 : 0);
        $this->db->bind(':email_status', $status);
        
        return $this->db->execute();
    }

    /**
     * Obtiene estadísticas de notificaciones
     */
    public function getNotificationStats() {
        $query = 'SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN verified = 1 THEN 1 ELSE 0 END) as verified,
                    SUM(CASE WHEN email_sent = 1 THEN 1 ELSE 0 END) as emails_sent,
                    SUM(CASE WHEN type = "user_created" THEN 1 ELSE 0 END) as users_created,
                    SUM(CASE WHEN type = "password_reset" THEN 1 ELSE 0 END) as passwords_reset
                 FROM ' . $this->table;
        
        $this->db->query($query);
        return $this->db->single();
    }

    /**
     * Obtiene notificaciones por tipo
     */
    public function getNotificationsByType($type, $limit = 10) {
        $query = 'SELECT * FROM ' . $this->table . ' 
                 WHERE type = :type 
                 ORDER BY created_at DESC 
                 LIMIT :limit';
        
        $this->db->query($query);
        $this->db->bind(':type', $type);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    /**
     * Obtiene notificaciones de un usuario específico
     */
    public function getUserNotifications($userId, $limit = 10) {
        $query = 'SELECT * FROM ' . $this->table . ' 
                 WHERE user_id = :user_id 
                 ORDER BY created_at DESC 
                 LIMIT :limit';
        
        $this->db->query($query);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    /**
     * Elimina notificaciones antiguas
     */
    public function cleanOldNotifications($days = 30) {
        $query = 'DELETE FROM ' . $this->table . ' 
                 WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)';
        
        $this->db->query($query);
        $this->db->bind(':days', $days);
        
        return $this->db->execute();
    }

    /**
     * Obtiene el conteo de notificaciones no verificadas
     */
    public function getUnverifiedCount() {
        $query = 'SELECT COUNT(*) as count FROM ' . $this->table . ' WHERE verified = 0';
        
        $this->db->query($query);
        $result = $this->db->single();
        
        return $result ? $result->count : 0;
    }

    /**
     * Obtiene el conteo de emails pendientes
     */
    public function getPendingEmailsCount() {
        $query = 'SELECT COUNT(*) as count FROM ' . $this->table . ' WHERE email_sent = 0';
        
        $this->db->query($query);
        $result = $this->db->single();
        
        return $result ? $result->count : 0;
    }
}




