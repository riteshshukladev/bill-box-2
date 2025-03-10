<?php
include '../db/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();

        $projectname = $_POST['projectname'];
        $username = $_POST['username'];
        $projectid = $_POST['projectid'];

        
        $tables = [
            'services',      // Delete from child tables first
            'bankdetails',   // These tables have foreign key references
            'sendersdetails',
            'addressanddate',
            'allproject'     // Delete from parent table last
        ];

        foreach ($tables as $table) {
            $sql = "DELETE FROM $table WHERE username = ? AND projectname = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $projectname]);
        }

        $pdo->commit();
        echo json_encode([
            "status" => "success",
            "message" => "Invoice deleted successfully"
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode([
            "status" => "error",
            "message" => "Failed to delete invoice: " . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method"
    ]);
}
