<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db/db.php';
session_start();

header('Content-Type: application/json');

try { // Wrap the entire script in a try...catch block
    $username = "";
    if (isset($_SESSION['user_id'])) {
        $username = $_SESSION['user_id'];
    } else {
        error_log("Session expired or user not logged in."); // Log session issue
        echo json_encode(['status' => 'error', 'message' => 'Session expired or user not logged in.']);
        exit;
    }

    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    error_log("Received data: " . print_r($data, true));

    if (isset($data['projectName'], $data['billerName'], $data['billerEmail'], $data['billerPhone'], $data['billingDate'], $data['dueDate'], $data['senderAddress'], $data['receiverAddress'], $data['serviceInput'], $data['amountInput'], $data['bankName'], $data['accountNumber'], $data['ifsc'], $data['pan'], $data['template'])) {
        $projectName = $data['projectName'];
        $billerName = $data['billerName'];
        $billerEmail = $data['billerEmail'];
        $billerPhone = $data['billerPhone'];
        $billingDate = $data['billingDate'];
        $dueDate = $data['dueDate'];
        $senderAddress = $data['senderAddress'];
        $receiverAddress = $data['receiverAddress'];
        $serviceInput = $data['serviceInput'];
        $amountInput = $data['amountInput'];
        $bankName = $data['bankName'];
        $accountNumber = $data['accountNumber'];
        $ifsc = $data['ifsc'];
        $pan = $data['pan'];
        $template = $data['template'];

        try {
            $pdo->beginTransaction();

            $createdAt = date('Y-m-d H:i:s'); // Get current timestamp in the correct format
            $stmt = $pdo->prepare('INSERT INTO allproject (username, projectname, created_at, template) VALUES (?, ?, ?, ?)');
            $stmt->execute([$username, $projectName, $createdAt, $template]);

            $stmt2 = $pdo->prepare('INSERT INTO sendersdetails (username, projectname, email, phoneno) VALUES (?, ?, ?, ?)');
            $stmt2->execute([$username, $projectName, $billerEmail, $billerPhone]);

            $stmt3 = $pdo->prepare('INSERT INTO addressanddate (username, projectname, issdate, duedate, sendersadd, receiversadd) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt3->execute([$username, $projectName, $billingDate, $dueDate, $senderAddress, $receiverAddress]);

            $stmt4 = $pdo->prepare("INSERT INTO services (username, projectname, service, price) VALUES (?, ?, ?, ?)");
            for ($i = 0; $i < count($serviceInput); $i++) {
                $stmt4->execute([$username, $projectName, $serviceInput[$i], $amountInput[$i]]);
            }

            $stmt5 = $pdo->prepare('INSERT INTO bankdetails (username, projectname, bankname, accno, ifsc_no, pan) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt5->execute([$username, $projectName, $bankName, $accountNumber, $ifsc, $pan]);

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Data added successfully', 'redirect' => '../profile/profile.php']);
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Database update failed: " . $e->getMessage() . " - " . print_r($pdo->errorInfo(), true));
            echo json_encode(['status' => 'error', 'message' => 'Database update failed: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data not received']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'General error: ' . $e->getMessage()]);
}
