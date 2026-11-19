<?php
  session_start();
  $errors = [
    'login' => $_SESSION['login_error'] ?? ''
  ];
  $activeForm = $_SESSION['active_form'] ?? 'login';

  //session_unset();
  unset($_SESSION['login_error'], $_SESSION['active_form']);

  function showError($error){
    return !empty($error) ? "<p class='error-message'> $error </p>" : '';
  }

  function isActiveForm($formName, $activeForm){
    return $formName === $activeForm ? 'active' : '';
  }

?>

<!-- MODAL ΠΑΡΑΘΥΡΟ -->
<div id="login-modal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>

    <div class="modal-columns">
      <!-- Δημιουργία Λογαριασμού -->
      <div class="signup-section">
        <h3>Δεν έχετε λογαριασμό;<br><br></h3>
        <h4> Φτιάξε τώρα:</h4>
        <ul>
          <li>Για να ενημερώνεσαι για τις προσφορές</li>
          <li>Για να παρακολουθείς τις παραγγελίες σου</li>
        </ul>
        <br><button class="create-account-btn">Δημιουργία λογαριασμού</button>
      </div>

      <!-- Σύνδεση -->
      <div class="login-section" id="login-form">
        <h3>Έχετε ήδη λογαριασμό;<br><br></h3>
        <p>Συνδεθείτε με τα στοιχεία σας για γρήγορη αγορά<br><br></p>
        

        
        <form action="login_register.php" method="post" class="login-form">
          <!-- ERROR -->
          <?= showError($errors['login']); ?> 
          <input name = "email" type="email" placeholder="Email*" required>
          <input  name = "password" type="password" placeholder="Κωδικός Πρόσβασης*" required>
          <select class="role-select" id="role-select" required>
                    <option value="customer">Πελάτης</option>
                    <option value="employee">Υπάλληλος</option>
                    <option value="admin">Διαχειριστής</option>
                </select>
          <div class="extra-role-field" id="extra-role-field"></div>
          <button name="login" type="submit" class="login-btn">Είσοδος</button>
        </form>

        <p class="or">ή συνδεθείτε μέσω</p>
        <div class="social-login">
          <button class="google-btn"><i class='bx bxl-google'></i> Google</button>
          <button class="facebook-btn"><i class='bx bxl-facebook'></i> Facebook</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Boxicons -->
<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
<script src="./scripts/script.js"></script>
