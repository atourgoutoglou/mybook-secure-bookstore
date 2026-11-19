<?php
  session_start();
  $errors = [
    'register' => $_SESSION['register_error'] ?? ''
  ];
  $activeForm = $_SESSION['active_form'] ?? 'register';

  //session_unset();
  unset($_SESSION['login_error'], $_SESSION['active_form']);

  function showError($error){
    return !empty($error) ? "<p class='error-message'> $error </p>" : '';
  }

  function isActiveForm($formName, $activeForm){
    return $formName === $activeForm ? 'active' : '';
  }

?>



<div id="signup-modal" class="modal">
  <div class="modal-content" id="register-form">
    <span class="close-btn">&times;</span>
    <button id="back-to-login" class="back-btn">&#8592;</button>
    <h3>Δημιουργία Λογαριασμού</h3>
    <form class="signup-form" action="login_register.php" method="post">
        <div class="modal-columns">
            <div class="signup-section">
                <!-- ERROR -->
                <?= showError($errors['register']); ?> 
                <input name="username" type="text" placeholder="Όνομα Χρήστη*" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                <input name="password" type="password" placeholder="Κωδικός Πρόσβασης*" required>
                <input type="password" placeholder="Επιβεβαίωση Κωδικού Πρόσβασης*" required>
                
                <input name="email" type="email" placeholder="Email*" required>
                <select name="role" id="role-select" required>
                    <option value="customer">Πελάτης</option>
                    <option value="employee">Υπάλληλος</option>
                </select>
                <input name="number_id" type="text" placeholder="Κωδικός Αναγνώρισης (αν έχετε)" maxlength="5" minlength="5" pattern="[0-9]{5}">
            </div>
            <div class="login-section">
                <input type="text" placeholder="Όνομα*" required>
                <input type="text" placeholder="Επώνυμο*" required>
                <input name="phonenumber" type="tel" placeholder="Κινητό*" required maxlength="10" minlength="10" pattern="[0-9]{10}">
                <input type="text" placeholder="Πόλη*" required>
                <input type="text" placeholder="Διεύθυνση*" required>
                <input type="text" placeholder="ΤΚ*" required pattern="[0-9]+">               
            </div>
            
      </div>
      <button name="register" type="submit" class="signup-btn">Εγγραφή</button>
    </form>
  </div>
</div>
<script src="./scripts/script.js"></script>