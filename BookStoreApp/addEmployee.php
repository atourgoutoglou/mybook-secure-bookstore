<?php
session_start();
if(!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin'){
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Λεπτομέρειες Βιβλίου</title>
  
  <link rel="stylesheet" href="./styles/style.css">
  <link rel="stylesheet" href="./styles/navbar.css">
  <link rel="stylesheet" href="./styles/card.css">
  <link rel="stylesheet" href="./styles/addEmployee.css">
  
  
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
<header>
    <?php       
        include './components/navbar.php'; 
    ?>
    <script src="./scripts/navbar.js"></script>
       
    <div class="book-columns">
        <h2 id="h2">Προσθήκη Υπαλλήλου</h2>
        <form class="book-info-section" action="login_register.php" method="post">
            <input type="text" placeholder="Όνομα*" required>
            <input type="text" placeholder="Επώνυμο*" required>
            <input type="text" name="email" placeholder="Email*" required>
            <input type="tel" name="phonenumber" placeholder="Κινητό*" required>
            <div class="id-generator">
                <input class="generator-input" id="generator-input" name="number_id" type="text" placeholder="Κωδικός Αναγνώρισης (5 αριθμοί)*" required readonly maxlength="5" minlength="5" pattern="[0-9]{5}">
                <button type="button" id="generate-id-btn" ><i class='bx bx-shuffle' ></i> </button>
            </div>
            <textarea placeholder="Σχόλια" rows="4"></textarea>
            <button id="add-employee-btn" name="addEmployee">Προσθήκη Υπαλλήλου</button>
        </form>
    </div>      
</header>
<section class="products" id="products">
        <div class="viral-product-header">
            <h2>Κατάλογος Βιβλίων</h2>
            <p>Ανακάλυψε τα πιο αγαπημένα και προτεινόμενα βιβλία της σεζόν!</p>
        </div>
        <div class="viral-product-grid"> </div>
</section>

<script src="./scripts/scriptEmployee.js"></script>
<script src="./scripts/addBook.js"></script>
</body>
</html>
