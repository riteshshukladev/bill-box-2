<?php
include '../db/db.php';
session_start();
$username = "";

if (isset($_SESSION['user_id']) && $_GET['user'] == $_SESSION['user_id']) {
    $username = $_GET['user'];

    $limit = 5; // Number of invoices per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $search = $_GET['search'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM allproject WHERE username = ? AND projectname ILIKE ? LIMIT ? OFFSET ?");
    $stmt->execute([$username, "%$search%", $limit, $offset]);
    $invoices = $stmt->fetchAll();

    // Check if there are more invoices for the next page
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM allproject WHERE username = ? AND projectname ILIKE ?");
    $stmt->execute([$username, "%$search%"]);
    $totalInvoices = $stmt->fetchColumn();
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

    <div class="home-container">
        <h1>My Invoices</h1>
        <p>View and manage all your invoices</p>

        <!-- Add Search Form -->
        <form method="GET" action="" class="search-form">
            <input type="hidden" name="user" value="<?php echo htmlspecialchars($username); ?>">
            <input type="text" name="search" placeholder="Search invoices by project name" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            <button type="submit" class="search-btn">Search</button>
        </form>

        <?php if (!empty($invoices)) : ?>
            <section class="recent-invoices">
                <div class="invoiceList">
                    <?php foreach ($invoices as $invoice) : ?>
                        <div class="invoice">
                            <div class="invoice-info">
                                <h6><?php echo htmlspecialchars($invoice['projectname']); ?></h6>
                            </div>
                            <div>
                                <button data-username="<?php echo htmlspecialchars($invoice['username']); ?>"
                                    data-projectname="<?php echo htmlspecialchars($invoice['projectname']); ?>"
                                    class="viewbtn">View</button>
                                <button data-username="<?php echo htmlspecialchars($invoice['username']); ?>"
                                    data-projectname="<?php echo htmlspecialchars($invoice['projectname']); ?>"
                                    data-projectid="<?php echo htmlspecialchars($invoice['projectid']); ?>"
                                    class="deletebtn">Delete</button>
                                <!-- Add Edit Button -->
                                <button onclick="window.location.href='../form/editForm.php?user=<?php echo htmlspecialchars($invoice['username']); ?>&projectid=<?php echo htmlspecialchars($invoice['projectid']); ?>'"
                                    class="editbtn">Edit</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else: ?>
            <section class="no-invoices">
                <h3>No Invoices Found</h3>
                <p>Create your first invoice to get started</p>
            </section>
            <div class="create-section">
                <button class="createbtn" onclick="window.location.href='../form/inputForm.php?user=<?php echo $username ?>'">
                    +
                </button>
                <span>Create New Invoice</span>
            </div>
        <?php endif; ?>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?user=<?php echo $username; ?>&page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" class="pagination-btn">Previous</a>
            <?php endif; ?>

            <?php if ($page * $limit < $totalInvoices): ?>
                <a href="?user=<?php echo $username; ?>&page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" class="pagination-btn">Next</a>
            <?php else: ?>
                <button class="pagination-btn disabled" disabled>Next</button>
            <?php endif; ?>
        </div>
    </div>

    <script src="./deletefolder/deletefolder.js"></script>
    <script src="./viewFolder/viewfolder.js"></script>
</body>

</html>