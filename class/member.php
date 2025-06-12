<?php
class Member {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function show( $origdate = 0 ) {
    try {
        $this->conn->beginTransaction();

        $stmt = $this->conn->prepare("SELECT id, email, status FROM accounts WHERE status = 'active'");
        $stmt->execute();
        $acc_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ids = array_column($acc_r, 'id');
        $acc_r_txt = implode(',', $ids);

        $stmt = $this->conn->prepare("SELECT accounts_id, address_line, city, state, postal FROM accounts_address WHERE accounts_id IN ( $acc_r_txt )");
        $stmt->execute();
        $acc_address_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->conn->prepare("SELECT accounts_id, first_name, middle_name, last_name, bday, gender, baptist_date FROM accounts_info WHERE accounts_id IN ( $acc_r_txt )");
        $stmt->execute();
        $acc_info_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->conn->commit();

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
                    $name = $info_r['first_name'] . ' ' . $info_r['middle_name'] . ' ' . $info_r['last_name'];
                    $bday = $info_r['bday'];
                    $gender = $info_r['gender'];
                    $baptist_date = $info_r['baptist_date'];
                    break;
                }
            }

            $data_r[] = [
                'id' => $id,
                'email' => $acc['email'],
                'status' => $acc['status'],
                'name' => $name,
                'gender' => $gender,
                'bday' => !$origdate ? date('M d, Y', strtotime($bday)) : $bday,
                'address' => $address,
                'baptism_date' => !$origdate ? date('M d, Y', strtotime($baptist_date)) : $baptist_date
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


    public function add($data) {
        try {
            // Start transaction
            $this->conn->beginTransaction();
    
            // 1. Insert into accounts
            $createdAt = date('Y-m-d H:i:s');
            $status = 'active';
            $email = strtolower($data['firstName'] . '.' . $data['middleName'] . '.' . $data['lastName']) . '@example.com'; // placeholder email
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
                    accounts_id, first_name, middle_name, last_name, bday, gender, baptist_date, inviter_id, updated_at
                ) VALUES (
                    :accounts_id, :first_name, :middle_name, :last_name, :bday, :gender, :baptist_date, :inviter_id, :updated_at
                )
            ");
            $stmt2->execute([
                ':accounts_id' => $accountId,
                ':first_name' => $data['firstName'],
                ':middle_name' => $data['middleName'],
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

    public function update($id = 0) {
        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
            return;
        }

        try {
            $this->conn->beginTransaction();

            // Collect data from POST
            $firstName = $_POST['firstName'] ?? '';
            $middleName = $_POST['middleName'] ?? '';
            $lastName = $_POST['lastName'] ?? '';
            $bday = $_POST['bday'] ?? '';
            $gender = $_POST['gender'] ?? '';
            $baptist_date = $_POST['baptist_date'] ?? '';
            $email = $_POST['email'] ?? '';

            $addressLine = $_POST['addressLine'] ?? '';
            $city = $_POST['city'] ?? '';
            $state = $_POST['state'] ?? '';
            $postalCode = $_POST['postalCode'] ?? '';

            $updatedAt = date('Y-m-d H:i:s');

            // Update accounts
            $stmt = $this->conn->prepare("UPDATE accounts SET email = :email, updated_at = :updated_at WHERE id = :id");
            $stmt->execute([
                ':email' => $email,
                ':updated_at' => $updatedAt,
                ':id' => $id
            ]);

            // Update accounts_info
            $stmt = $this->conn->prepare("UPDATE accounts_info SET 
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                bday = :bday,
                gender = :gender,
                baptist_date = :baptist_date,
                updated_at = :updated_at
                WHERE accounts_id = :id
            ");
            $stmt->execute([
                ':first_name' => $firstName,
                ':middle_name' => $middleName,
                ':last_name' => $lastName,
                ':bday' => $bday,
                ':gender' => $gender,
                ':baptist_date' => $baptist_date,
                ':updated_at' => $updatedAt,
                ':id' => $id
            ]);

            // Update accounts_address
            $stmt = $this->conn->prepare("UPDATE accounts_address SET 
                address_line = :address_line,
                city = :city,
                state = :state,
                postal = :postal
                WHERE accounts_id = :id
            ");
            $stmt->execute([
                ':address_line' => $addressLine,
                ':city' => $city,
                ':state' => $state,
                ':postal' => $postalCode,
                ':id' => $id
            ]);

            $this->conn->commit();

            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            $this->conn->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function delete($id = 0) {
    if ($id <= 0) {
        return ['status' => 'error', 'message' => 'Invalid ID'];
    }

    try {
        $this->conn->beginTransaction();

        $stmt = $this->conn->prepare("DELETE FROM accounts_info WHERE accounts_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->conn->prepare("DELETE FROM accounts_address WHERE accounts_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->conn->prepare("DELETE FROM accounts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $this->conn->commit();

        return ['status' => 'success'];
    } catch (PDOException $e) {
        $this->conn->rollBack();
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}


}