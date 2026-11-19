<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Λεπτομέρειες Βιβλίου</title>
  <link rel="stylesheet" href="./styles/style.css">
  <link rel="stylesheet" href="./styles/navbar.css">
  <link rel="stylesheet" href="./styles/card.css">
  <link rel="stylesheet" href="./styles/bookPage.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
<header>
        <?php       
        include './components/navbar.php'; 
        ?>
        <script src="./scripts/navbar.js"></script>

        <div class="book-details-container">
            <img id="book-image" alt="Book cover">
                <div class="book-info">
                    <h2 id="book-title"></h2>
                    <p><strong>Συγγραφέας:</strong> <span id="book-author"></span></p>
                    <p><strong>Εκδόσεις:</strong> <span id="book-publisher"></span></p>
                    <p><strong>Αριθμός σελίδων:</strong> <span id="book-pages"></span></p>
                    <p><strong>Τιμή:</strong> <span id="book-price"></span></p>
                    <div id="book-rating"></div>
                    <p id="book-description"></p>
                    <button id="add-to-cart-btn">Προσθήκη στο Καλάθι</button>
                </div>
        </div>
</header>

<!--<section class="products">
  <div class="viral-product-header">
    <h2>Δείτε Επίσης...</h2>
    <p>Ανακάλυψε τα πιο αγαπημένα και προτεινόμενα βιβλία της σεζόν!</p>
  </div>

  <div class="viral-product-grid">
    <!-- Τα προϊόντα θα δημιουργηθούν εδώ δυναμικά με JavaScript 
  </div>
</section> -->
<script src="./scripts/script.js"></script>
<script src="./scripts/book.js"></script>

    
  
</body>
</html>
