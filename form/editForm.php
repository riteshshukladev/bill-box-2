<?php
include '../db/db.php'; // Include the database connection
session_start(); // Start the session

if (!isset($_GET['user']) || !isset($_GET['projectid'])) {
    die("Invalid request");
}

$username = $_GET['user'];
$projectid = $_GET['projectid'];

// Fetch the invoice details from allproject
$stmt = $pdo->prepare("SELECT * FROM allproject WHERE username = ? AND projectid = ?");
$stmt->execute([$username, $projectid]);
$invoice = $stmt->fetch();

// Fetch the address and date details from addressanddate
$stmtAddress = $pdo->prepare("SELECT * FROM addressanddate WHERE username = ? AND projectname = ?");
$stmtAddress->execute([$username, $invoice['projectname']]);
$addressanddate = $stmtAddress->fetch();

// Fetch the bank details
$stmtBank = $pdo->prepare("SELECT * FROM bankdetails WHERE projectname = ? AND username = ?");
$stmtBank->execute([$invoice['projectname'], $username]);
$bankDetails = $stmtBank->fetch();

if (!$invoice) {
    die("Invoice not found");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
    <link rel="stylesheet" href="./editForm.css">
</head>

<body>
    <div class="edit-container">
        <h1>Edit Invoice</h1>
        <form method="POST" action="updateInvoice.php" class="edit-form">
            <input type="hidden" name="projectid" value="<?php echo htmlspecialchars($invoice['projectid']); ?>">

            <div class="form-group">
                <label>Project Name</label>
                <input type="text" name="projectname" value="<?php echo htmlspecialchars($invoice['projectname']); ?>" required readonly>
            </div>

            <div class="form-group">
                <label>Issued Date</label>
                <input type="date" name="issdate" value="<?php echo htmlspecialchars($addressanddate['issdate']); ?>" required>
            </div>

            <div class="form-group">
                <label>Due Date</label>
                <input type="date" name="duedate" value="<?php echo htmlspecialchars($addressanddate['duedate']); ?>" required>
            </div>

            <div class="form-group">
                <label>Sender's Address</label>
                <input type="text" name="sendersadd" value="<?php echo htmlspecialchars($addressanddate['sendersadd']); ?>" required>
            </div>

            <div class="form-group">
                <label>Receiver's Address</label>
                <input type="text" name="receiversadd" value="<?php echo htmlspecialchars($addressanddate['receiversadd']); ?>" required>
            </div>

            <div class="form-group">
                <label>Services</label>
                <div class="services">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM services WHERE projectname = ? AND username = ?");
                    $stmt->execute([$invoice['projectname'], $username]);
                    $services = $stmt->fetchAll();

                    foreach ($services as $service) : ?>
                        <div class="service">
                            <input type="text" name="serviceInput[]" value="<?php echo htmlspecialchars($service['service']); ?>" placeholder="Service Name">
                            <input type="number" name="amountInput[]" value="<?php echo htmlspecialchars($service['price']); ?>" placeholder="Price">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-service-btn">+ Add Service</button>
            </div>

            <div class="form-group">
                <label>Bank Details</label>
                <input type="text" name="bankname" value="<?php echo htmlspecialchars($bankDetails['bankname'] ?? ''); ?>" placeholder="Bank Name">
                <input type="number" name="accountNumber" value="<?php echo htmlspecialchars($bankDetails['accno'] ?? ''); ?>" placeholder="Account Number">
                <input type="text" name="ifsc" value="<?php echo htmlspecialchars($bankDetails['ifsc_no'] ?? ''); ?>" placeholder="IFSC Code">
                <input type="text" name="pan" value="<?php echo htmlspecialchars($bankDetails['pan'] ?? ''); ?>" placeholder="PAN">
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">Save Changes</button>
                <button type="button" class="cancel-btn" onclick="window.history.back()">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        document.querySelector('.add-service-btn').addEventListener('click', function(e) {
            e.preventDefault();
            const servicesContainer = document.querySelector('.services');
            const newService = document.createElement('div');
            newService.classList.add('service');
            newService.innerHTML = `
                <input type="text" name="serviceInput[]" placeholder="Service Name">
                <input type="number" name="amountInput[]" placeholder="Price">
            `;
            servicesContainer.appendChild(newService);
        });
    </script>
</body>

</html>