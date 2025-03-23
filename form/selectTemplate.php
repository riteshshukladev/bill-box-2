<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../authentication/login/login.html");
    exit;
}
$username = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Invoice Template</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        .template-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
            padding: 20px;
        }

        .template-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            width: 300px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .template-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .template-preview {
            width: 100%;
            height: 400px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <h1 style="text-align: center;">Select an Invoice Template</h1>
    <div class="template-container">
        <!-- Template 1 -->
        <div class="template-card">

            <h3>Classic Template</h3>
            <form action="inputForm.php" method="POST">
                <input type="hidden" name="template" value="classic">
                <input type="hidden" name="user" value="<?php echo htmlspecialchars($username); ?>">
                <button type="submit" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                    Select Classic
                </button>
            </form>
        </div>

        <!-- Template 2 -->
        <div class="template-card">

            <h3>Modern Template</h3>
            <form action="inputForm.php" method="POST">
                <input type="hidden" name="template" value="modern">
                <input type="hidden" name="user" value="<?php echo htmlspecialchars($username); ?>">
                <button type="submit" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                    Select Modern
                </button>
            </form>
        </div>
    </div>
</body>

</html>