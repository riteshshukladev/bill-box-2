<?php
include '../db/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectid = $_POST['projectid'];
    $projectname = $_POST['projectname'];
    $issdate = $_POST['issdate'];
    $duedate = $_POST['duedate'];
    $sendersadd = $_POST['sendersadd'];
    $receiversadd = $_POST['receiversadd'];
    $serviceInput = $_POST['serviceInput'];
    $amountInput = $_POST['amountInput'];
    $bankname = $_POST['bankname'];
    $accountNumber = $_POST['accountNumber'];
    $ifsc = $_POST['ifsc'];
    $pan = $_POST['pan'];
    $username = $_SESSION['user_id']; // Retrieve username from session

    try {
        $pdo->beginTransaction();

        // Update the main invoice details in allproject
        $stmt = $pdo->prepare("UPDATE allproject SET projectname = ? WHERE projectid = ?");
        $stmt->execute([$projectname, $projectid]);

        // Update address and date details in addressanddate
        $stmtAddress = $pdo->prepare("UPDATE addressanddate SET issdate = ?, duedate = ?, sendersadd = ?, receiversadd = ? WHERE projectname = ?");
        $stmtAddress->execute([$issdate, $duedate, $sendersadd, $receiversadd, $projectname]);

        // Update services
        $stmt = $pdo->prepare("DELETE FROM services WHERE projectname = ? AND username = ?");
        $stmt->execute([$projectname, $username]);

        $stmt = $pdo->prepare("INSERT INTO services (username, projectname, service, price) VALUES (?, ?, ?, ?)");
        for ($i = 0; $i < count($serviceInput); $i++) {
            $stmt->execute([$username, $projectname, $serviceInput[$i], $amountInput[$i]]);
        }

        // Update bank details
        $stmt = $pdo->prepare("UPDATE bankdetails SET bankname = ?, accno = ?, ifsc_no = ?, pan = ? WHERE projectname = ?");
        $stmt->execute([$bankname, $accountNumber, $ifsc, $pan, $projectname]);

        $pdo->commit();
        header("Location: ../profile/invoices.php?user=" . $_SESSION['user_id']);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error updating invoice: " . $e->getMessage());
    }
}
