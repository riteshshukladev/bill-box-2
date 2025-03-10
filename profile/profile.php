<?php
include '../db/db.php';
session_start();
$username = "";

if (isset($_SESSION['user_id']) && $_GET['user'] == $_SESSION['user_id']) {
    $username = $_GET['user'];
    $userinfo = [];

    $stmt = $pdo->prepare("SELECT name, email, mobile_number, address FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $userinfo = $stmt->fetch();
    if (!$userinfo) {
        die("User not found");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - BillBox</title>
    <link rel="stylesheet" href="./profile.css">
    <link rel="stylesheet" href="/Bill-Box/components/nav.css">
</head>

<body>
    <?php
    $nav_username = $username;
    include '../components/nav.php';
    ?>

    <div class="profile-container">
        <div class="profile-modal">
            <div class="profile-header">
                <img src="../images/img/profile.png" alt="Profile Picture">
                <h2>Profile Settings</h2>
            </div>
            <form id="profileForm" class="profile-form">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" value="<?php echo htmlspecialchars($username); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($userinfo['email']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($userinfo['name']); ?>">
                </div>
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="number" name="mobile_num" id="mobile_num" placeholder="Add mobile number" value="<?php echo htmlspecialchars($userinfo['mobile_number']); ?>">
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" id="new_password" placeholder="Enter new password">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="added_add" id="added_add" placeholder="Add Address" value="<?php echo htmlspecialchars($userinfo['address']); ?>">
                </div>
                <div class="form-actions">
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>
                <p id="update-message" class="update-message"></p>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const mobileNum = document.getElementById('mobile_num').value;
            const address = document.getElementById('added_add').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const updateMessage = document.getElementById('update-message');

            if (newPassword && newPassword !== confirmPassword) {
                updateMessage.textContent = "Passwords don't match!";
                updateMessage.className = 'update-message error';
                return;
            }

            const formData = new FormData();
            formData.append('name', name);
            formData.append('mobile_num', mobileNum);
            formData.append('added_add', address);
            formData.append('new_password', newPassword);
            formData.append('username', '<?php echo $username; ?>');

            fetch('update_profile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        updateMessage.textContent = 'Profile updated successfully!';
                        updateMessage.className = 'update-message success';

                        if (newPassword) {
                            document.getElementById('new_password').value = '';
                            document.getElementById('confirm_password').value = '';
                        }
                    } else {
                        updateMessage.textContent = data.message || 'Update failed!';
                        updateMessage.className = 'update-message error';
                    }
                })
                .catch(error => {
                    updateMessage.textContent = 'An error occurred!';
                    updateMessage.className = 'update-message error';
                    console.error('Error:', error);
                });
        });
    </script>
</body>

</html>