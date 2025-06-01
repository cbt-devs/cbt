<?php
class Member {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addMember($data) {
        try {
            // Start transaction
            $this->conn->beginTransaction();
    
            // 1. Insert into accounts
            $createdAt = date('Y-m-d H:i:s');
            $status = 'active';
            $email = strtolower($data['firstName'] . '.' . $data['lastName']) . '@example.com'; // placeholder email
            $password = password_hash('default123', PASSWORD_DEFAULT); // placeholder password
    
            $stmt1 = $this->conn->prepare("
                INSERT INTO accounts (email, pass, created_at, updated_at, status)
                VALUES (:email, :pass, :created_at, :updated_at, :status)
            ");
            $stmt1->execute([
                ':email' => $email,
                ':pass' => $password,
                ':created_at' => $createdAt,
                ':updated_at' => $createdAt,
                ':status' => $status
            ]);
    
            $accountId = $this->conn->lastInsertId();
    
            // 2. Insert into accounts_info
            $baptistDate = $createdAt; // use current timestamp as placeholder
            $inviterId = 0; // placeholder or default
    
            $stmt2 = $this->conn->prepare("
                INSERT INTO accounts_info (
                    accounts_id, first_name, last_name, bday, gender, baptist_date, inviter_id, updated_at
                ) VALUES (
                    :accounts_id, :first_name, :last_name, :bday, :gender, :baptist_date, :inviter_id, :updated_at
                )
            ");
            $stmt2->execute([
                ':accounts_id' => $accountId,
                ':first_name' => $data['firstName'],
                ':last_name' => $data['lastName'],
                ':bday' => $data['birthdate'],
                ':gender' => $data['gender'],
                ':baptist_date' => $baptistDate,
                ':inviter_id' => $inviterId,
                ':updated_at' => $createdAt
            ]);
    
            // 3. Insert into accounts_address
            $stmt3 = $this->conn->prepare("
                INSERT INTO accounts_address (
                    accounts_id, address_line, city, state, postal, is_primary
                ) VALUES (
                    :accounts_id, :address_line, :city, :state, :postal, :is_primary
                )
            ");
            $stmt3->execute([
                ':accounts_id' => $accountId,
                ':address_line' => $data['addressLine'],
                ':city' => $data['city'],
                ':state' => $data['state'],
                ':postal' => $data['postalCode'],
                ':is_primary' => isset( $data[ 'primary' ] ) ? 1 : 0
            ]);
    
            // Commit transaction
            $this->conn->commit();
    
            return true;
    
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log('Insert failed: ' . $e->getMessage());
            return false;
        }
    }
    
}