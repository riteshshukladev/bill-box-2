<?php
session_start();

$username = "";
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['user_id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $template = $_POST['template'] ?? 'classic';
    $templateClass = $template === 'modern' ? 'invoice-modern' : 'invoice-classic';

    $projectName = $_POST['projectName'];
    $billerName = $_POST['billerName'];
    $billerEmail = $_POST['billerEmail'];
    $billerPhone = $_POST['billerPhone'];
    $billingDate = $_POST['billingDate'];
    $dueDate = $_POST['dueDate'];
    $senderAddress = $_POST['senderAddress'];
    $receiverAddress = $_POST['receiverAddress'];
    $serviceInput = isset($_POST['serviceInput']) ? (is_array($_POST['serviceInput']) ? $_POST['serviceInput'] : [$_POST['serviceInput']]) : [];
    $amountInput = isset($_POST['amountInput']) ? (is_array($_POST['amountInput']) ? $_POST['amountInput'] : [$_POST['amountInput']]) : [];
    $bankName = $_POST['bankname'];
    $accountNumber = $_POST['accountNumber'];
    $ifsc = $_POST['ifsc'];
    $pan = $_POST['pan'];

    $subtotal = 0;
    $total = 0;

    foreach ($amountInput as $amount) {
        $subtotal += $amount;
    }

    $total = $subtotal;
} else {
    header("Location: ../form/inputForm.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Invoice</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <?php if ($template === 'modern') : ?>
        <link rel="stylesheet" href="../profile/modern.css">
    <?php endif; ?>
    <link rel="stylesheet" href="./print.css" media="print">
</head>

<body>
    <div class="<?php echo $templateClass; ?>">
        <div class="container">
            <div id="name" class="name"><?php echo htmlspecialchars($billerName) ?></div>
            <div class="date-section">
                <div class="date-block">
                    <span class="label">Issued</span>
                    <span id="issued-date" class="date-value"><?php echo htmlspecialchars($billingDate) ?></span>
                </div>
                <div class="date-block">
                    <span class="label">Due</span>
                    <span id="due-date" class="date-value"><?php echo htmlspecialchars($dueDate) ?></span>
                </div>
            </div>

            <!-- Address Section -->
            <div class="address-section">
                <div class="address-block">
                    <span class="label">From</span>
                    <span id="from-address" class="address-value"><?php echo htmlspecialchars($senderAddress) ?></span>
                </div>

                <div class="address-block">
                    <span class="label">To</span>
                    <span id="to-address" class="address-value"><?php echo htmlspecialchars($receiverAddress) ?></span>
                </div>
            </div>

            <!-- Services Section -->
            <div class="service-label-section">
                <div><span>Service</span></div>
                <div><span>Total</span></div>
            </div>

            <div class="divider"></div>

            <?php for ($i = 0; $i < count($serviceInput); $i++) : ?>
                <div class="service-card">
                    <div class="service-name"><?php echo htmlspecialchars($serviceInput[$i]) ?></div>
                    <div class="service-total"><?php echo htmlspecialchars($amountInput[$i]) ?></div>
                </div>
            <?php endfor; ?>

            <!-- Subtotal Section -->
            <div class="sub-total-section">
                <div class="sub-total">
                    <div>Sub Total</div>
                    <div class="sub-total-value"><?php echo htmlspecialchars($total) ?></div>
                </div>
            </div>

            <!-- Total Section -->
            <div class="total-card">
                <span class="label">Total</span>
                <span class="total-value"><?php echo htmlspecialchars($total) ?></span>
            </div>

            <!-- Bank Details Section -->
            <!-- Bank Details Section -->
            <div class="bank-details">
                <div class="bank-detail">
                    <span class="label">Bank Name:</span>
                    <span><?php echo htmlspecialchars($bankName) ?></span>
                </div>
                <div class="bank-detail">
                    <span class="label">Account Number:</span>
                    <span><?php echo htmlspecialchars($accountNumber) ?></span>
                </div>
                <div class="bank-detail">
                    <span class="label">IFSC Code:</span>
                    <span><?php echo htmlspecialchars($ifsc) ?></span>
                </div>
                <div class="bank-detail">
                    <span class="label">PAN:</span>
                    <span><?php echo htmlspecialchars($pan) ?></span>
                </div>
            </div>

            <!-- Footer Message -->
            <div class="message">
                <p>Thanks for collaborating and putting trust in me, If you have any question, feel free to contact me at</p>
                <a href="<?php echo htmlspecialchars($billerEmail) ?>"><?php echo htmlspecialchars($billerEmail) ?></a>
            </div>

        </div>

        <!-- Action Buttons -->
        <div id="three_buttons">
            <button class="edit">Edit</button>
            <button class="print">Print</button>
            <button class="saveBtn" type="button">Save</button>
            <button class="home-button">Home</button>
        </div>
    </div>

</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // PHP variables to JS
        const invoiceData = {
            username: "<?php echo $username; ?>",
            projectName: "<?php echo addslashes($projectName); ?>",
            billerName: "<?php echo addslashes($billerName); ?>",
            billerEmail: "<?php echo addslashes($billerEmail); ?>",
            billerPhone: "<?php echo addslashes($billerPhone); ?>",
            billingDate: "<?php echo addslashes($billingDate); ?>",
            dueDate: "<?php echo addslashes($dueDate); ?>",
            senderAddress: "<?php echo addslashes($senderAddress); ?>",
            receiverAddress: "<?php echo addslashes($receiverAddress); ?>",
            serviceInput: <?php echo json_encode($serviceInput); ?>,
            amountInput: <?php echo json_encode($amountInput); ?>,
            bankName: "<?php echo addslashes($bankName); ?>",
            accountNumber: "<?php echo addslashes($accountNumber); ?>",
            ifsc: "<?php echo addslashes($ifsc); ?>",
            pan: "<?php echo addslashes($pan); ?>",
            subtotal: "<?php echo $subtotal; ?>",
            total: "<?php echo $total; ?>",
            template: "<?php echo $template; ?>" // Include template here
        };

        // Save Button
        document.querySelector('.saveBtn').addEventListener('click', function() {
            sendInvoiceData(invoiceData);
        });

        // Edit Button
        const buttons_main = document.querySelector('#three_buttons');
        document.querySelector('.edit').addEventListener('click', () => {
            window.history.back();
        });

        // Print Button
        document.querySelector('.print').addEventListener('click', () => {
            buttons_main.style.display = 'none';
            window.print();
            window.location.reload();
        });

        // Home Button
        document.querySelector('.home-button').addEventListener('click', () => {
            window.location.href = "../profile/profile.php?user=<?php echo htmlspecialchars($username) ?>";
        });
    });
</script>
<script>
    const invoiceData = {
        username: "<?php echo $username; ?>",
        projectName: "<?php echo addslashes($projectName); ?>",
        billerName: "<?php echo addslashes($billerName); ?>",
        billerEmail: "<?php echo addslashes($billerEmail); ?>",
        billerPhone: "<?php echo addslashes($billerPhone); ?>",
        billingDate: "<?php echo addslashes($billingDate); ?>",
        dueDate: "<?php echo addslashes($dueDate); ?>",
        senderAddress: "<?php echo addslashes($senderAddress); ?>",
        receiverAddress: "<?php echo addslashes($receiverAddress); ?>",
        serviceInput: <?php echo json_encode($serviceInput); ?>,
        amountInput: <?php echo json_encode($amountInput); ?>,
        bankName: "<?php echo addslashes($bankName); ?>",
        accountNumber: "<?php echo addslashes($accountNumber); ?>",
        ifsc: "<?php echo addslashes($ifsc); ?>",
        pan: "<?php echo addslashes($pan); ?>",
        subtotal: "<?php echo $subtotal; ?>",
        total: "<?php echo $total; ?>",
        template: "<?php echo $template; ?>"
    };

    function sendInvoiceData(invoiceData) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "save.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");

        const jsonData = JSON.stringify(invoiceData);
        console.log("Sending JSON data:", jsonData); // Log the JSON data

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status >= 200 && xhr.status < 300) {
                    var responseText = JSON.parse(xhr.responseText);
                    if (responseText.status === "success") {
                        alert("Invoice saved successfully");
                    } else {
                        alert("Invoice not saved");
                    }
                } else {
                    console.log("HTTP Error:", xhr.statusText);
                }
            }
        };

        xhr.send(jsonData);
    }
</script>

</html>