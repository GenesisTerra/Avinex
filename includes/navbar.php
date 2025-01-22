<?php 
session_start();
$currentTheme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'Light';
?>

<?php if (isset($_SESSION['user_id'])): ?>
<nav>
    <div>
        <span style="font-size:30px;cursor:pointer" onclick="switchNav()">&#9776; &nbsp;</span>
        <a href="dashboard.php" style="color: white;"><img src="../assets/logo.png" alt="LOGO"
                style="max-width: 100%; height: auto; width: 60px;"></a>
        <span style="font-size:30px;">&nbsp; AVINEX</span>
    </div>
    <div>
        <a href="switch.php" style="text-decoration: none; color:var(--on--primary)">Switch to <?php echo ($currentTheme === 'Dark') ? 'Light' : 'Dark'; ?> Mode</a>
        &nbsp;&nbsp;
        <div class="dropdown">
            <button class="dropdown-button" onclick="navigateButton('profile.php')" onhover=" ">
                <?php echo $_SESSION["user_id"]; ?>
            </button>
            <div class="dropdown-content">
                <a href="profile.php">Profile</a>
                <a href="formAdd.php">addForm</a>
                <a href="logout.php">logout</a>
            </div>
        </div>
    </div>
</nav>
<div id="mySidenav" class="sidenav" style="width: 250px">
    <h2>NAVIGATION</h2><br>
    <a href="dashboard.php">Home</a><br>
    <a href="formAdd.php">Compose</a><br>
    <a href="contactUs.php">Contact Us</a><br>
    <a href="AboutUs.php">About Us</a><br>
</div>
<?php endif; ?>

<?php if (!isset($_SESSION['user_id']) && (basename($_SERVER['PHP_SELF']) == 'login.php')): ?>
<nav>
    <div> <img src="../assets/logo.png" alt="LOGO" style="max-width: 100%; height: auto; width: 60px;">
        <span style="font-size:30px;">&nbsp; AVINEX</span>
    </div>
    <div><a href="switch.php" style="text-decoration: none; color:var(--on--primary)">Switch to <?php echo ($currentTheme === 'Dark') ? 'Light' : 'Dark'; ?> Mode</a>
    &nbsp;&nbsp;<button onclick="navigateButton('register.php')">REGISTER</button></div>
</nav>
<?php endif; ?>

<?php if (!isset($_SESSION['user_id']) && (basename($_SERVER['PHP_SELF']) == 'register.php')): ?>
<nav>
    <div> <img src="../assets/logo.png" alt="LOGO" style="max-width: 100%; height: auto; width: 60px;">
        <span style="font-size:30px;">&nbsp; AVINEX</span>
    </div>
    <div><a href="switch.php" style="text-decoration: none; color:var(--on--primary)">Switch to <?php echo ($currentTheme === 'Dark') ? 'Light' : 'Dark'; ?> Mode</a>
    &nbsp;&nbsp;<button onclick="navigateButton('login.php')">LOGIN</button></div>
</nav>
<?php endif; ?>

<?php if (!isset($_SESSION['user_id']) && (basename($_SERVER['PHP_SELF']) == 'forgotPassword.php')): ?>
<nav>
    <div> <img src="../assets/logo.png" alt="LOGO" style="max-width: 100%; height: auto; width: 60px;">
        <span style="font-size:30px;">&nbsp; AVINEX</span>
    </div>
    <div><a href="switch.php" style="text-decoration: none; color:var(--on--primary)">Switch to <?php echo ($currentTheme === 'Dark') ? 'Light' : 'Dark'; ?> Mode</a>
    &nbsp;&nbsp;<button onclick="navigateButton('login.php')">login</button></div>
</nav>
<?php endif; ?>
<br>

<script>
function switchNav() {
    let nav = document.getElementById("mySidenav").style.width;
    if (nav === "250px") {
        document.getElementById("mySidenav").style.width = "0px";
        document.getElementById("main").style.marginLeft = "0px";
    } else {
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }
}
</script>