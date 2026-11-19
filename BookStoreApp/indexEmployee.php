<!-- Authorization -->
<?php
session_start();
if(!isset($_SESSION['email']) || $_SESSION['role'] !== 'employee'){
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['register_success'])) {
    echo "<script>alert('" . addslashes($_SESSION['register_success']) . "');</script>";
    unset($_SESSION['register_success']);
}

if (isset($_SESSION['register_error'])) {
    echo "<script>alert('" . addslashes($_SESSION['register_error']) . "');</script>";
    unset($_SESSION['register_error']);
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
    <link rel="stylesheet" href="./styles/card.css">
    <!-- Boxicons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
    <header>
        <?php 
        include './components/navbar.php'; 
        ?>
        <script src="./scripts/navbar.js"></script>

        <div class="main-header">
            <div class="header">
                <h5> Βιβλιοπωλείο myBOOK</h5>
                <h2>Καλώς ήρθες στο myBOOK!</h2>
                <p>Από τα τελευταία best sellers μέχρι διαχρονικά λογοτεχνικά έργα, εδώ θα βρεις το επόμενο <br>ταξίδι του μυαλού σου.
                Για εμάς, κάθε σελίδα κρύβει μια ιστορία που αξίζει να τη ζήσεις.</p>
        
                <div class="search-box"> 
                    <i class='bx bx-search'></i>               
                    <input type="search" class="search" placeholder="Ψάχνετε κάτι;...">
                    
                </div>
            </div>
            <img src="./Images/image1.jpg" alt="" class="main-img">
        </div>

    </header>




    <section class="viral-products" id="viral-products">
        <div class="viral-product-header">
            <h2>Δημοφιλή Βιβλία</h2>
            <p>Ανακάλυψε τα πιο αγαπημένα και προτεινόμενα βιβλία της σεζόν!</p>
        </div>

        <div class="viral-product-grid">
        <!-- Τα προϊόντα θα δημιουργηθούν εδώ δυναμικά με JavaScript -->
        </div>
    </section>

    <section class="products" id="products">
        <div class="viral-product-header">
            <h2>Κατάλογος Βιβλίων</h2>
            <p>Ανακάλυψε τα πιο αγαπημένα και προτεινόμενα βιβλία της σεζόν!</p>
        </div>

        <div class="viral-product-grid">
        <!-- Τα προϊόντα θα δημιουργηθούν εδώ δυναμικά με JavaScript -->
        </div>
    </section>


    <script src="./scripts/scriptEmployee.js"></script>
    


</body>
</html>