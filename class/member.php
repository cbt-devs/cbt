<?php

class Member
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function show($_data_r = [])
    {
        try {
            $this->conn->beginTransaction();

            $orig_date = $_data_r['origdate'] ?? 0;

            $sql = '';
            if ($gender = $_data_r[ 'gender' ] ?? 0) {
                $sql = " AND gender = '$gender'";
            }

            $stmt = $this->conn->prepare("SELECT id, email, status FROM accounts WHERE status = 'active'");
            $stmt->execute();
            $acc_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $ids = array_column($acc_r, 'id');
            $acc_r_txt = implode(',', $ids);

            $stmt = $this->conn->prepare("SELECT accounts_id, address_line, city, state, postal FROM accounts_address WHERE accounts_id IN ( $acc_r_txt )");
            $stmt->execute();
            $acc_address_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $this->conn->prepare("SELECT accounts_id, first_name, middle_name, last_name, birthdate, gender, date_baptized, contact, occupation, occupation_place, marital_status, anniv_date, partner_name FROM accounts_info WHERE accounts_id IN ( $acc_r_txt ) $sql");
            $stmt->execute();
            $acc_info_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->conn->commit();

            $data_r = [];

            foreach ($acc_r as $acc) {
                $id = $acc['id'];

                $name = $address = $birthdate = $gender = $date_baptized = '';
                $contact = $occupation = $occupation_place = $marital_status = $partner_name = $anniv_date = '';

                foreach ($acc_address_r as $address_r) {
                    if ($id == $address_r['accounts_id']) {
                        $address = $address_r['address_line'] . ' ' . $address_r['city'] . ' ' . $address_r['state'];
                        break;
                    }
                }

                foreach ($acc_info_r as $info_r) {
                    if ($id == $info_r['accounts_id']) {
                        $name = trim($info_r['first_name'] . ' ' . $info_r['middle_name'] . ' ' . $info_r['last_name']);
                        $birthdate = $info_r['birthdate'];
                        $gender = $info_r['gender'];
                        $date_baptized = $info_r['date_baptized'];
                        $contact = $info_r['contact'];
                        $occupation = $info_r['occupation'];
                        $occupation_place = $info_r['occupation_place'];
                        $marital_status = $info_r['marital_status'];
                        $partner_name = $info_r['partner_name'];
                        $anniv_date = $info_r['anniv_date'];
                        break;
                    }
                }

                $data_r[] = [
                    'id' => $id,
                    'email' => $acc['email'],
                    'status' => $acc['status'],
                    'name' => $name,
                    'gender' => $gender,
                    'birthdate' => !$orig_date ? date('M d, Y', strtotime($birthdate)) : $birthdate,
                    'address' => $address,
                    'baptism_date' => !$orig_date ? date('M d, Y', strtotime($date_baptized)) : $date_baptized,
                    'contact' => $contact,
                    'occupation' => $occupation,
                    'occupation_place' => $occupation_place,
                    'marital_status' => $marital_status,
                    'partner_name' => $partner_name,
                    'anniv_date' => $anniv_date,
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

    public function add($data)
    {
        try {
            // Start transaction
            $this->conn->beginTransaction();

            // 1. Insert into accounts (login info)
            $createdAt = date('Y-m-d H:i:s');
            $status = 'active';
            $email = strtolower($data['firstName'] . '.' . $data['middleName'] . '.' . $data['lastName']) . '@example.com'; // placeholder
            $password = password_hash('default123', PASSWORD_DEFAULT); // placeholder

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

            // 2. Insert into accounts_info (personal, family, baptism info)
            $stmt2 = $this->conn->prepare("
    INSERT INTO accounts_info (
        accounts_id, first_name, middle_name, last_name, birthdate, gender, 
        birthplace, contact, occupation, occupation_place, occupation_position, 
        marital_status, anniv_date, partner_name, partner_occupation,
        father_name, mother_name, father_occupation, mother_occupation,
        date_saved, witness_by, date_baptized, baptized_by, place_of_baptism,
        inviter_id, updated_at
    ) VALUES (
        :accounts_id, :first_name, :middle_name, :last_name, :birthdate, :gender, 
        :birthplace, :contact, :occupation, :occupation_place, :occupation_position, 
        :marital_status, :anniv_date, :partner_name, :partner_occupation,
        :father_name, :mother_name, :father_occupation, :mother_occupation,
        :date_saved, :witness_by, :date_baptized, :baptized_by, :place_of_baptism,
        :inviter_id, :updated_at
    )
");

            $stmt2->execute([
                ':accounts_id' => $accountId,
                ':first_name' => $data['firstName'],
                ':middle_name' => $data['middleName'],
                ':last_name' => $data['lastName'],
                ':birthdate' => $data['birthdate'],   // fixed from :birthdate
                ':gender' => $data['gender'],
                ':birthplace' => $data['birthplace'],
                ':contact' => $data['contact'],
                ':occupation' => $data['occupation'],
                ':occupation_place' => $data['occupation_place'],
                ':occupation_position' => $data['occupation_position'],
                ':marital_status' => $data['status'],
                ':anniv_date' => ($data['status'] === 'married') ? $data['anniversarydate'] : null,
                ':partner_name' => ($data['status'] === 'married') ? $data['partner_name'] : null,
                ':partner_occupation' => ($data['status'] === 'married') ? $data['partner_occupation'] : null,
                ':father_name' => $data['father_name'],
                ':mother_name' => $data['mother_name'],
                ':father_occupation' => $data['father_occupation'],
                ':mother_occupation' => $data['mother_occupation'],
                ':date_saved' => $data['date_saved'],
                ':witness_by' => $data['witness_by'],
                ':date_baptized' => $data['date_baptized'],
                ':baptized_by' => $data['baptized_by'],
                ':place_of_baptism' => $data['place_of_baptism'],
                ':inviter_id' => 0,
                ':updated_at' => $createdAt
            ]);

            // 3. Insert into accounts_address (separate table)
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
                ':is_primary' => isset($data['primary']) ? 1 : 0
            ]);

            // 4. Insert into accounts_ministry (many-to-many)
            if (!empty($data['ministry']) && is_array($data['ministry'])) {
                $stmt4 = $this->conn->prepare("
                INSERT INTO accounts_ministry (accounts_id, ministry_id, date)
                VALUES (:accounts_id, :ministry_id, :date)
            ");
                foreach ($data['ministry'] as $ministryId) {
                    if (is_numeric($ministryId)) {
                        $stmt4->execute([
                            ':accounts_id' => $accountId,
                            ':ministry_id' => $ministryId,
                            ':date' => $createdAt
                        ]);
                    }
                }
            }

            // Commit transaction
            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            die('Insert failed: ' . $e->getMessage());
        }
    }

    public function update($id = 0)
    {
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
            $birthdate = $_POST['birthdate'] ?? '';
            $gender = $_POST['gender'] ?? '';
            $date_baptized = $_POST['date_baptized'] ?? '';
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
                birthdate = :birthdate,
                gender = :gender,
                date_baptized = :date_baptized,
                updated_at = :updated_at
                WHERE accounts_id = :id
            ");
            $stmt->execute([
                ':first_name' => $firstName,
                ':middle_name' => $middleName,
                ':last_name' => $lastName,
                ':birthdate' => $birthdate,
                ':gender' => $gender,
                ':date_baptized' => $date_baptized,
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

    public function delete($id = 0)
    {
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
