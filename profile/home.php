<?php
include '../db/db.php';
session_start();
$username = "";

if (isset($_SESSION['user_id']) && $_GET['user'] == $_SESSION['user_id']) {
    $username = $_GET['user'];

    // Get latest 5 invoices for the user, ordered by projectid (assuming it's auto-incremented)
    $stmt = $pdo->prepare("SELECT * FROM allproject WHERE username = ? ORDER BY projectid DESC LIMIT 5");
    $stmt->execute([$username]);
    $latest_invoices = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - BillBox</title>
    <link rel="stylesheet" href="./profile.css">
    <link rel="stylesheet" href="/Bill-Box/components/nav.css">
</head>

<body>
    <?php
    $nav_username = $username;
    include '../components/nav.php';
    ?>

    <div class="home-container">
        <h1>Welcome to BillBox</h1>
        <p>Create and manage your invoices easily</p>

        <div class="create-section">
            <button class="createbtn">
                +
            </button>
            <span>Create New Invoice</span>
        </div>

        <?php if (!empty($latest_invoices)) : ?>
            <section class="recent-invoices">
                <h3>Recent Invoices</h3>
                <div class="invoiceList">
                    <?php foreach ($latest_invoices as $invoice) : ?>
                        <div class="invoice">
                            <div class="invoice-info">
                                <h6><?php echo htmlspecialchars($invoice['projectname']); ?></h6>
                                <p>Created at: <?php echo date('d M Y, h:i A', strtotime($invoice['created_at'])); ?></p>
                            </div>
                            <div>
                                <button data-username="<?php echo htmlspecialchars($invoice['username']); ?>"
                                    data-projectname="<?php echo htmlspecialchars($invoice['projectname']); ?>"
                                    class="viewbtn">View</button>
                                <button data-username="<?php echo htmlspecialchars($invoice['username']); ?>"
                                    data-projectname="<?php echo htmlspecialchars($invoice['projectname']); ?>"
                                    data-projectid="<?php echo htmlspecialchars($invoice['projectid']); ?>"
                                    class="deletebtn">Delete</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>

    <script>
        function HandleCreateBtnClick() {
            window.location.href = "../form/inputForm.php?user=<?php echo $username ?>";
        }
        document.querySelector('.createbtn').addEventListener('click', HandleCreateBtnClick);
    </script>

    <!-- Include the same scripts used in invoices.php for view/delete functionality -->
    <script src="./deletefolder/deletefolder.js"></script>
    <script src="./viewFolder/viewfolder.js"></script>
</body>

</html>