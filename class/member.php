<?php
class Member {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function showMember() {
    try {
        $this->conn->beginTransaction();

        // Fetch accounts
        $stmt = $this->conn->prepare("SELECT id, email, status FROM accounts");
        $stmt->execute();
        $acc_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch addresses (must include account_id to join later)
        $stmt = $this->conn->prepare("SELECT accounts_id, address_line, city, state, postal FROM accounts_address");
        $stmt->execute();
        $acc_address_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch info (must include account_id to join later)
        $stmt = $this->conn->prepare("SELECT accounts_id, first_name, last_name, bday, gender, baptist_date FROM accounts_info");
        $stmt->execute();
        $acc_info_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->conn->commit();

        // Combine data by account ID
        $data_r = [];

        foreach ($acc_r as $acc) {
            $id = $acc['id'];

            $name = $address = $bday = $gender = $baptist_date = '';
            foreach ($acc_address_r as $address_r) {
                if ($id == $address_r['accounts_id']) {
                    $address = $address_r['address_line'] . ' ' . $address_r['city'] . ' ' . $address_r['state'];
                    break;
                }
            }

            foreach ($acc_info_r as $info_r) {
                if ($id == $info_r['accounts_id']) {
                    $name = $info_r['first_name'] . ' ' . $info_r['last_name'];
                    $bday = $info_r['bday'];
                    $gender = $info_r['gender'];
                    $baptist_date = $info_r['baptist_date'];
                    break;
                }
            }

            $data_r[] = [
                'email' => $acc['email'],
                'status' => $acc['status'],
                'name' => $name,
                'gender' => $gender,
                'bday' => date('M d, Y', strtotime($bday)),
                'address' => $address,
                'baptism_date' => date('M d, Y', strtotime($baptist_date))
            ];
        }

        return $data_r;

    } catch (PDOException $e) {
        $this->conn->rollBack();
        return [
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ];
    }
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