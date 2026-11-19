<?php
session_start();
if(!isset($_SESSION['email']) || !in_array($_SESSION['role'], ['admin', 'employee'])){
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['register_success'])) {
    $message = htmlspecialchars($_SESSION['register_success'], ENT_QUOTES, 'UTF-8');
    echo "<script>alert('$message');</script>";
    unset($_SESSION['register_success']);
}

if (isset($_SESSION['register_error'])) {
    $message = htmlspecialchars($_SESSION['register_error'], ENT_QUOTES, 'UTF-8');
    echo "<script>alert('$message');</script>";
    unset($_SESSION['register_error']);
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
  <link rel="stylesheet" href="./styles/bookPage.css">
  <link rel="stylesheet" href="./styles/addBook.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
<header>
    <?php       
        include './components/navbar.php'; 
    ?>
    <script src="./scripts/navbar.js"></script>
        
    <div class="book-details-container">
        <h2 id="h2">Προσθήκη Βιβλίου</h2>
        <form class="book-columns" action="addBook.php" method="post">
            <div class="book-image-section">
                <img id="book-image" alt="Book cover" src="" style="display:none;">
                <input type="hidden" id="image-path-input" name="image_path">
                <div class="image-actions">
                    <button id="upload-btn" type="button">Επιλογή Εξωφύλλου</button>
                    <button id="remove-btn" style="display:none;">Αφαίρεση Εξωφύλλου</button>
                    <input type="file" id="file-input" accept="image/png, image/jpeg" style="display:none;">
                </div>
            </div>
            <div class="book-info-section">
                <input type="text" name="title" placeholder="Τίτλος*" required>
                <input type="text" name="author" placeholder="Συγγραφέας*" required>
                <input type="text" name="publisher" placeholder="Εκδόσεις*" required>
                <input type="text" name="pages" placeholder="Σελίδες*" min="1" required>
                <input type="text" name="price" placeholder="Τιμή*" min="0" step="0.01" required>
                <input type="text" name="stars" placeholder="Αξιολόγηση (1-5 αστέρια)*" min="1" max="5" step="0.1" required>
                <textarea placeholder="Περίληψη*" name="description" rows="4" required></textarea>
                <button name="addBook" id="add-to-cart-btn" type="submit">Προσθήκη Βιβλίου</button>
            </div>
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

<script>
document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.getElementById("file-input");
    const bookImage = document.getElementById("book-image");
    const removeBtn = document.getElementById("remove-btn");

    if (!fileInput) return; 

    fileInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = () => {
            bookImage.src = reader.result;
            bookImage.style.display = "block";
            removeBtn.style.display = "inline-block";

            const imagePath = "Images/" + file.name;
            console.log("Image path:", imagePath); 
            document.getElementById("image-path-input").value = imagePath;
        };
        reader.readAsDataURL(file);
    });
});
</script>
</body>
</html>
