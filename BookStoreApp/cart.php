<?php
session_start();
$role = $_SESSION['role'] ?? 'guest';
$username = $_SESSION['username'] ?? null;
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
    <link rel="stylesheet" href="./styles/cart_style.css">
    <style>

        html, body {
        height: auto;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        background-color: #ac815f;
        }

        header {
            display: block;
            width: 100%;
            height: auto;
            background-color: #ac815f;
        }

    </style>
</head>
<body>
    <header>
        <?php       
        include './components/navbar.php'; 
        if (!isset($role)) {
    $role = 'guest';
        }
        ?>
        <script src="./scripts/navbar.js"></script>
        <div class="cart-container">
            <h2 class="cart-title">Το Καλάθι μου</h2>
            <div class="cart-items"></div>

            <div class="empty" style="display: none;">Το καλάθι σας είναι άδειο.</div>

            <div class="cart-summary">
                <strong>Συνολικό Ποσό: </strong><span id="total-price">€0.00</span>
            </div>

            <div class="cart-actions">

                <?php if ($role === 'customer' || $role === 'guest'): ?>
                    <a href="./index.php#products" class="btn">+ Προσθήκη Βιβλίων</a>
                <?php elseif ($role === 'employee'): ?>
                    <a href="./indexEmployee.php#products" class="btn">+ Προσθήκη Βιβλίων</a>
                <?php elseif ($role === 'admin'): ?>
                    <a href="./indexAdmin.php#products" class="btn">+ Προσθήκη Βιβλίων</a>
                <?php endif; ?>

                
                <?php if ($role === 'customer' || $role === 'employee' || $role === 'admin'): ?>
                    <button id="checkout-btn" class="btn">Συνέχεια προς Τρόπους Πληρωμής</button>
                <?php else: ?>
                    <button id="checkout-btn" class="btn">Συνέχεια προς Τρόπους Πληρωμής</button>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <section class="products">
    <div class="viral-product-header">
        <h2>Προσθέστε Ακόμη...</h2>
        <p>Ανακάλυψε τα πιο αγαπημένα και προτεινόμενα βιβλία της σεζόν!</p>
    </div>

    <div class="viral-product-grid"></div>
    </section> 
<script>
  const userRole = "<?php echo $role; ?>";
</script>
<script src="./scripts/script.js"></script>
<script src="./scripts/cart_script.js"></script>


</body>
</html>