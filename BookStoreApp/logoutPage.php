<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
if(!isset($_SESSION['email'])){
    header("Location: index.php");
    exit();
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>myBOOK</title>
    <!-- css file -->
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/logout.css">
    <!-- Boxicons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
    <header>
        <?php 
        //session_start();
        include './components/navbar.php'; 
        ?>
        <script src="./scripts/navbar.js"></script>

        <div class="logout-container">
            <div class="logout-con">
                
              <h2>Αποσύνδεση Χρήστη </h2> 
              <a style="font-size: 32px; text-align: center;"><span><?= htmlspecialchars($username) ?></span></a>
              <button onclick="window.location.href='logoutAction.php'" id="logout-btn">Αποσύνδεση</button>
                
        </div>
         </div>
    </header>
    
</body>
</html>







