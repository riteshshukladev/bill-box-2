<nav class="main-nav">
    <div class="nav-container">
        <div class="nav-brand">
            <a href="/Bill-Box/profile/home.php?user=<?php echo $_SESSION['user_id']; ?>">
                Welcome, <?php echo isset($nav_username) ? htmlspecialchars($nav_username) : 'BillBox'; ?>
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="/Bill-Box/profile/home.php?user=<?php echo $_SESSION['user_id']; ?>">
                    Home</a>
            </li>
            <li><a href="/Bill-Box/profile/invoices.php?user=<?php echo $_SESSION['user_id']; ?>">
                    Invoices</a>
            </li>
            <li><a href="/Bill-Box/profile/profile.php?user=<?php echo $_SESSION['user_id']; ?>">
                    Profile</a>
            </li>
        </ul>
        <!-- <button type="button" onclick="location.href='../authentication/login/login.html'" class="logout-btn">Logout</button> -->
    </div>
</nav>