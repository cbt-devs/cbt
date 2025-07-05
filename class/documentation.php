<?php
class Documentation
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function show()
    {
        try {
            // Fetch all documents
            $stmt = $this->conn->prepare("SELECT * FROM documents");
            $stmt->execute();
            $documents_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get unique account IDs
            $ids = array_unique(array_column($documents_r, 'accounts_id'));

            if (empty($ids)) {
                return [];
            }

            // Prepare placeholders for safe IN clause
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // Fetch account info
            $stmt = $this->conn->prepare("SELECT accounts_id, first_name, middle_name, last_name FROM accounts_info WHERE accounts_id IN ($placeholders)");
            $stmt->execute($ids);
            $acc_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Map account info by accounts_id
            $accountMap = [];
            foreach ($acc_r as $info) {
                $accountMap[$info['accounts_id']] = $info;
            }

            // Merge document info with account names
            $data_r = [];
            foreach ($documents_r as $doc) {
                $account_id = $doc['accounts_id'];
                $name = '';

                if (isset($accountMap[$account_id])) {
                    $info = $accountMap[$account_id];
                    $name = trim("{$info['first_name']} {$info['middle_name']} {$info['last_name']}");
                }

                $data_r[] = [
                    'id' => $doc['id'],
                    'name' => $name,
                    'filename' => $doc['file_name'],
                    'created' => date('M d, Y h:i A', strtotime($doc['created_at'])),
                ];
            }

            usort($data_r, function ($a, $b) {
                return $b['id'] <=> $a['id'];
            });

            return $data_r;
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to fetch documents: ' . $e->getMessage()
            ];
        }
    }

    public function add($data = [])
    {
        if (!isset($_FILES['file']) || !isset($data['accounts_id'])) {
            return false;
        }

        $file = $_FILES['file'];
        $uploadDir = '../assets/documents/';
        $fileName = basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $stmt = $this->conn->prepare("INSERT INTO documents (accounts_id, file_name) VALUES (?, ?)");
            $stmt->execute([$data['accounts_id'], $fileName]);
            return 'File uploaded and saved';
        } else {
            return 'Failed to upload file';
        }
    }


    public function delete($id = 0)
    {
        try {
            // Step 1: Get file name from DB
            $stmt = $this->conn->prepare("SELECT file_name FROM documents WHERE id = ?");
            $stmt->execute([$id]);
            $document = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$document) {
                return [
                    'status' => 'error',
                    'message' => 'Document not found.'
                ];
            }

            $fileName = $document['file_name'];
            $filePath = __DIR__ . '/../assets/documents/' . $fileName;

            // Step 2: Delete the file from the directory
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Step 3: Delete the record from the database
            $stmt = $this->conn->prepare("DELETE FROM documents WHERE id = ?");
            $stmt->execute([$id]);

            return [
                'status' => 'success',
                'message' => 'Document deleted successfully.'
            ];
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Delete failed: ' . $e->getMessage()
            ];
        }
    }
}
