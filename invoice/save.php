<?php

include '../db/db.php';
session_start();

$username = "";

if(isset($_SESSION['user_id'])){
    $username = $_SESSION['user_id'];
}
else{
    echo json_encode(['success' => false, 'message' => 'Session expired or user not logged in.']);
     exit;
}
header('Content-Type: application/json');

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if(isset($data['projectName'] , $data['billerName'], $data['billerEmail'] ,$data['billerPhone'], $data['billingDate'], $data['dueDate'], $data['senderAddress'], $data['receiverAddress'], $data['serviceInput'], $data['amountInput'], $data['bankName'], $data['accountNumber'], $data['ifsc'], $data['pan'], $data['subtotal'], $data['total'])){


    

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
    $subtotal = $data['subtotal'];
    $total = $data['total'];

    // echo json_encode(['status' => 'success', 'message' => 'Data received']);

    try{
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('INSERT INTO allproject (username , projectname) VALUES (?,?)');
        $stmt->execute([$username, $projectName]);

        $stmt2 = $pdo->prepare('INSERT INTO sendersdetails (username , projectname ,email ,phoneno ) VALUES (?,?,?,?)');
        $stmt2->execute([$username, $projectName , $billerEmail , $billerPhone]);

        $stmt3 = $pdo->prepare('INSERT INTO addressanddate (username , projectname ,issdate ,duedate ,sendersadd ,receiversadd) VALUES (?,?,?,?,?,?)');
        $stmt3->execute([$username, $projectName , $billingDate , $dueDate , $senderAddress , $receiverAddress]);

        $stmt4 = $pdo->prepare("INSERT INTO services (username , projectname,service, price) VALUES (?, ?, ?, ?)");

        for ($i = 0; $i < count($serviceInput); $i++) {
            $stmt4->execute([$username, $projectName, $serviceInput[$i], $amountInput[$i]]);
        }
        
        $stmt5 = $pdo->prepare('INSERT INTO bankdetails (username , projectname ,bankname ,accno ,ifsc_no ,pan) VALUES (?,?,?,?,?,?)');
        $stmt5->execute([$username, $projectName , $bankName , $accountNumber , $ifsc , $pan]);


        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Data Added successfully', 'redirect' => '../profile/profile.php']);
        

    }
    catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database update failed. ' . $e->getMessage()]);
}
}
else{
    echo json_encode(['success' => false, 'message' => 'Data not received']);
}

?>