<?php


include '../db/db.php';
// header('Content-Type: application/json');

$addressanddate = [];
$sendersdetails = [];
$services = [];
$bankdetails = [];



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['projectname'], $_POST['username'])) {
    $projectname = $_POST['projectname'];
    $username = $_POST['username'];

    try{
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT * FROM addressanddate WHERE projectname = ? AND username = ?");
        $stmt->execute([$projectname, $username]);
        $addressanddate = $stmt->fetch();

        $stmt2 = $pdo->prepare("SELECT * FROM sendersdetails WHERE projectname = ? AND username = ?");
        $stmt2->execute([$projectname, $username]);
        $sendersdetails = $stmt2->fetch();

        $stmt3 = $pdo->prepare("SELECT * FROM services WHERE projectname = ? AND username = ?");
        $stmt3->execute([$projectname, $username]);
        $services = $stmt3->fetchAll();

        $stmt4 = $pdo->prepare("SELECT * FROM bankdetails WHERE projectname = ? AND username = ?");
        $stmt4->execute([$projectname, $username]);
        $bankdetails = $stmt4->fetch();

        $pdo->commit();

        echo json_encode(["status" => "success","projectname"=> $projectname , "username"=>$username, "addressanddate" => $addressanddate, "sendersdetails" => $sendersdetails, "services" => $services, "bankdetails" => $bankdetails]);

        session_start();
        $_SESSION['projectname'] = $projectname;
        $_SESSION['username'] = $username;
        $_SESSION['addressanddate'] = $addressanddate;
        $_SESSION['sendersdetails'] = $sendersdetails;
        $_SESSION['services'] = $services;
        $_SESSION['bankdetails'] = $bankdetails;
    }   

    catch(Exception $e){
        $pdo->rollBack();
        echo json_encode(["status" => "error", "message" => "An error occurred. Please try again."]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

?>


