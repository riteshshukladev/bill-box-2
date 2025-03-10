<?php
include '../db/db.php';
session_start();
$username = "";

if (isset($_SESSION['user_id']) && $_GET['user'] == $_SESSION['user_id']) {
    $username = $_GET['user'];
    
    $stmt = $pdo->prepare("SELECT * FROM allproject WHERE username = ?");
    $stmt->execute([$username]);
    $invoices = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Invoices - BillBox</title>
    <link rel="stylesheet" href="./profile.css">
    <link rel="stylesheet" href="/Bill-Box/components/nav.css">
</head>
<body>
    <?php 
    $nav_username = $username;
    include '../components/nav.php'; 
    ?>

    <section id="invoices">
        <h3>My Invoices</h3>
        <div class="invoiceList">
            <?php foreach ($invoices as $invoice) : ?>
                <div class="invoice">
                    <h6><?php echo $invoice['projectname']; ?></h6>
                    <div>
                        <button data-username="<?php echo $invoice['username']; ?>" 
                                data-projectname="<?php echo $invoice['projectname']; ?>" 
                                class="viewbtn">View</button>
                        <button data-username="<?php echo $invoice['username']; ?>" 
                                data-projectname="<?php echo $invoice['projectname']; ?>" 
                                data-projectid="<?php echo $invoice['projectid']; ?>" 
                                class="deletebtn">Delete</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <script src="./deletefolder/deletefolder.js"></script>
    <script src="./viewFolder/viewfolder.js"></script>
</body>
</html>