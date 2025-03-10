<?php 

include '../db/db.php';

$user = '';

if(isset($_POST['user'])){
    $user  = $_POST['user'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Document</title>
</head>
<body>
    <form id="MainInputForm" action="../invoice/invoice.php" method="post">
        <div>
            <label for="">Enter Project Name</label>
            <input type="text" name="projectName" id="projectName" required placeholder="Enter The Name of Bill">
        </div>
        
        <div>
        <label for="">Enter Biller's name and contacts</label>
        <input type="text" name="billerName" id="billerName" placeholder="Enter your name" required>
        <input type="email" name="billerEmail" id="billerContact" placeholder="Enter your email" required>
        <input type="tel" name="billerPhone" id="billerPhone" placeholder="Enter telephone no." required>
        </div>

        <div>
            <label for="">enter billing date</label>
            <input type="date" name="billingDate" id="billingDate" required>
        </div>
        <div>
            <label for="">Enter Due Date</label>
            <input type="date" name="dueDate" id="dueDate">
        </div>

        <div>
            <label for="">Enter Sender's Address</label>
            <input type="text" name="senderAddress" id="senderAddress" required placeholder="Enter sender's Address">
        </div>

        <div>
            <label for="">Enter Receiver's Address</label>
            <input type="text" name="receiverAddress" id="receiverAddress" required placeholder="Enter reciever's Address">
        </div>

        <div class="servicesMain">
            <label for="">enter services</label>
            <div class="services">
                <div class="service">
                    <input type="text" name="serviceInput[]" id="serviceInput" placeholder="product">
                    <input type="number" name="amountInput[]" id="amountInput" placeholder="price">
                </div>
            </div>
            <button class="servicesAdd">+</button>
        </div>

        <div>
            <label for="">Enter Bank Account Detail</label>
            <input type="text" name="bankname" id="bankname" required placeholder="Bank's Name">
            <input type="number" name="accountNumber" id="accountNumber" required placeholder="Account Number">
            <input type="text" name="ifsc" id="ifsc" required placeholder="Enter IFSC code">
            <input type="text" name="pan" id="pan" placeholder="Enter PAN">
        </div>


        <button class="subbtn" onclick="handlesubbtn" type="submit">Submit</button>
        <button type="reset">Reset</button>
        
    </form>

    
</body>
<script>
    


    document.querySelector('.servicesAdd').addEventListener('click', function(event){
    event.preventDefault(); // Prevent form submission when adding new service fields
    let services = document.querySelector('.services');
    let service = document.querySelector('.service');
    let newService = service.cloneNode(true);
    newService.querySelector('input[type=text]').value = ''; 
    newService.querySelector('input[type=number]').value = ''; 
    services.appendChild(newService);
});
    
//     document.querySelector('#MainInputForm').addEventListener('submit', function(event){

// event.preventDefault();

// const formData = new FormData(this);

// for(let [key,values] of formData.entries()){
//     console.log(key,values);
// }
// });
</script>

<script src="../save/index.js"></script>
</html>