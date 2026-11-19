<?php
$role = $_SESSION['role'] ?? 'guest';
$username = $_SESSION['username'] ?? null;
?>

<nav class="navbar">
    <div class="logo">
        <h1>myBOOK</h1>
        <i class='bx bx-book-library'></i>   
    </div>
    <div class="navmenu">
        <ul>
            <?php if ($role === 'customer' || $role === 'guest'): ?>
                <li><a href="./index.php">Αρχική</a></li>
                <li><a href="#viral-products">Δημοφιλή Προϊόντα</a></li>
                <li><a href="#products">Βιβλία</a></li>
            <?php elseif ($role === 'employee'): ?>
                <li><a href="./indexEmployee.php">Αρχική</a></li>
                <li><a href="#viral-products">Δημοφιλή Προϊόντα</a></li>
                <li><a href="#products">Βιβλία</a></li>
                <li><a href="./addBook.php" onclick="localStorage.setItem('bookAction', 'add'); localStorage.removeItem('selectedBook');">Προσθήκη Βιβλίου</a></li>
            <?php elseif ($role === 'admin'): ?>
                <li><a href="./indexAdmin.php">Αρχική</a></li>
                <li><a href="#viral-products">Δημοφιλή Προϊόντα</a></li>
                <li><a href="#products">Βιβλία</a></li>
                <li><a href="./addBook.php">Προσθήκη Βιβλίου</a></li>
                <li><a href="./addEmployee.php">Προσθήκη Υπαλλήλου</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="nav-actions">
        <?php if ($username): ?>           
             <a style="font-size: 18px;"><span><?= htmlspecialchars($username) ?></span></a>
        <?php endif; ?>
        <a href="javascript:void(0)" id="search-btn"><i class='bx bx-search'></i></a>  
        
        <?php if ($role === 'guest' || $role === null): ?>           
            <a href="#"><i class='bx bx-user' id="open-login-modal"></i></a>
        
        <?php else:?>
            <a href="./logoutPage.php"><i class='bx bx-user'></i></a>
        <?php endif; ?>
        
        <a href="./cart.php"><i class='bx bx-cart' id="cart-btn" ></i></a>

        <div class="search-popup" id="search-popup">
            <input type="text" id="search-input" placeholder="Αναζήτηση...">
            <button id="close-search"><i class='bx bx-x'></i></button>
            <div class="search-results" id="navbar-search-results"></div>
        </div>  
    </div>
</nav>