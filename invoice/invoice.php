<?php
session_start();

$username = "";
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['user_id'];
}


// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assign form data to variables
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
} else {
    header("Location: ../form/inputForm.php");
    exit;
}

$subtotal = 0;
$total = 0;


foreach ($amountInput as $amount) {
    $subtotal += $amount;
}

$total = $subtotal;

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Invoice</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <div>
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

            <div class="sub-total-section">
                <div class="sub-total">
                    <div>Sub Total</div>
                    <div class="sub-total-value"><?php echo htmlspecialchars($total) ?></div>
                </div>
            </div>

            <div class="total-card">
                <span class="label">Total</span>
                <span class="total-value"><?php echo htmlspecialchars($total) ?></span>
            </div>

            <div class="bank-details">
                <div class="bank-detail"><?php echo htmlspecialchars($bankName) ?></div>
                <div class="bank-detail"><?php echo htmlspecialchars($accountNumber) ?></div>
                <div class="bank-detail"><?php echo htmlspecialchars($ifsc) ?></div>
                <div class="bank-detail"><?php echo htmlspecialchars($pan) ?></div>
            </div>

            <div class="message">
                <p>Thanks for collaborating and putting trust in me, If you have any question, feel free to contact me at</p>
                <a href="<?php echo htmlspecialchars($billerEmail) ?>"><?php echo htmlspecialchars($billerEmail) ?></a>
            </div>

        </div>

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
        username : "<?php echo $username; ?>",
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
        total: "<?php echo $total; ?>"
    };
    
    
    document.querySelector('.saveBtn').addEventListener('click', function() {
        sendInvoiceData(invoiceData);
    });


    const buttons_main = document.querySelector('#three_buttons');
    document.querySelector('.edit').addEventListener('click', () => {
        window.history.back();
    });

    document.querySelector('.print').addEventListener('click', () => {
        buttons_main.style.display = 'none';
        window.print();
        window.location.reload();
    });

    



    document.addEventListener('keydown', function(event) {

    if (event.ctrlKey && (event.key === 'p' || event.key === 'P')) {
        event.preventDefault(); 
        console.log('Ctrl + P intercepted');
        buttons_main.style.display = 'none';
        window.print();
        window.location.reload();


    }
});

document.querySelector('.home-button').addEventListener('click', () => {
    window.location.href = "../profile/profile.php?user=<?php echo htmlspecialchars($username) ?>";
});


});

</script>

<script>
    function sendInvoiceData(invoiceData) {
        var xhr = new XMLHttpRequest();
        console.log(invoiceData);

        xhr.open("POST", "save.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) { // Request completed
                if (xhr.status >= 200 && xhr.status < 300) { // Success response
                    var responseText = JSON.parse(xhr.responseText);
                    console.log(responseText); // Corrected the position of this line

                    if (responseText.status === "success") {
                        alert("Invoice saved successfully"); 
                        
                    } else {
                        alert("Invoice not saved");
                    }
                } else {
                    // Handle HTTP error responses (4xx, 5xx codes)
                    console.log("HTTP Error:", xhr.statusText);
                }
            }
        };

        xhr.send(JSON.stringify(invoiceData));
    }
</script>

<!-- <script src="../save/index.js"></script> -->
</html>