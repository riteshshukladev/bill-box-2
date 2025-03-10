<?php

include '../db/db.php';
session_start();

$total = 0;

if (isset($_SESSION['projectname'], $_SESSION['username'], $_SESSION['addressanddate'], $_SESSION['sendersdetails'], $_SESSION['services'], $_SESSION['bankdetails'])) {
    $projectname = $_SESSION['projectname'];
    $username = $_SESSION['username'];
    $addressanddate = $_SESSION['addressanddate'];
    $sendersdetails = $_SESSION['sendersdetails'];
    $services = $_SESSION['services'];
    $bankdetails = $_SESSION['bankdetails'];
} else {
    die("Project not found");
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Invoice</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../invoice/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <div>
        <div class="container">

            <div id="name" class="name"><?php echo htmlspecialchars($username) ?></div>

            <div class="date-section">
                <div class="date-block">
                    <span class="label">Issued</span>
                    <span id="issued-date" class="date-value"><?php echo htmlspecialchars($addressanddate['issdate']) ?></span>
                </div>

                <div class="date-block">
                    <span class="label">Due</span>
                    <span id="due-date" class="date-value"><?php echo htmlspecialchars($addressanddate['duedate']) ?></span>
                </div>
            </div>

            <div class="address-section">
                <div class="address-block">
                    <span class="label">From</span>
                    <span id="from-address" class="address-value"><?php echo htmlspecialchars($addressanddate['sendersadd']) ?></span>
                </div>

                <div class="address-block">
                    <span class="label">To</span>
                    <span id="to-address" class="address-value"><?php echo htmlspecialchars($addressanddate['receiversadd']) ?></span>
                </div>
            </div>

            <div class="service-label-section">
                <div><span>Service</span></div>
                <div><span>Total</span></div>
            </div>

            <div class="divider"></div>

            <?php foreach ($services as $service) : ?>
                <div class="service-card">
                    <div class="service-name"><?php echo htmlspecialchars($service['service']); ?></div>
                    <div class="service-total"><?php echo htmlspecialchars($service['price']); ?></div>
                </div>
            <?php endforeach; ?>


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
                <div class="bank-detail"><?php echo htmlspecialchars($bankdetails['bankname']) ?></div>
                <div class="bank-detail"><?php echo htmlspecialchars($bankdetails['accno']) ?></div>
                <div class="bank-detail"><?php echo htmlspecialchars($bankdetails['ifsc_no']) ?></div>
                <div class="bank-detail"><?php echo htmlspecialchars($bankdetails['pan']) ?></div>
            </div>

            <div class="message">
                <p>Thanks for collaborating and putting trust in me, If you have any question, feel free to contact me at</p>
                <a href="<?php echo htmlspecialchars($sendersdetails['email']) ?>"><?php echo htmlspecialchars($sendersdetails['phoneno']) ?></a>
            </div>

        </div>

        <div id="three_buttons">
            <button class="print">Print</button>
            <button class="home-button">Home</button>
        </div>
    </div>

</body>

<script>

    const buttons_main = document.getElementById('three_buttons');
    document.addEventListener('DOMContentLoaded' , function(){
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
    })

    document.querySelector('.home-button').addEventListener('click', () => {
    window.location.href = "./profile.php?user=<?php echo htmlspecialchars($username) ?>";
});
});
</script>
</html>