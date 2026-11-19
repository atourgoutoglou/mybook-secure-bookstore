<?php
    // Εκκίνηση session για session-based authentication
    session_start();
    // Σύνδεση με βάση δεδομένων
    require_once 'config.php';

    // Εγγραφή Χρήστη
    if(isset($_POST['register'])){
        // Input Validation για αποτροπή XSS και invalid input
        $username = trim($_POST['username']);     
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $phonenumber =  preg_replace('/[^0-9]/','', $_POST['phonenumber']);
        $number_id =  preg_replace('/[^0-9]/','', $_POST['number_id']);
        $role =  trim($_POST['role'] ?? '');

        // Security fix: only customers and pre-authorised employees can register publicly.
        // Admin accounts cannot be created from the public sign-up form.
        if (!in_array($role, ['customer', 'employee'], true)) {
            $_SESSION['register_error'] = 'Μη έγκυρος ρόλος χρήστη.';
            $_SESSION['active_form'] = 'register';
            header("Location: signUp.php");
            exit();
        }
        // Αποθήκευση κωδικού πρόσβαση με hashing (bcrypt μέσω PASSWORD_DEFAULT)
        $password =   password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT username, password, email FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $checkEmail = $stmt->get_result();

        // Εγγραφή Πελάτη ή Διαχειριστή
        if($role === 'customer'){

            if($checkEmail->num_rows > 0) {
                $_SESSION['register_error'] = 'Το Email υπάρχει ήδη καταχωρημένο!';
                $_SESSION['active_form'] = 'register';
                header("Location: signUp.php");
                exit();
            } 
            // Δημιουγία νέου χρήστη
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, phonenumber, number_id, role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $username, $password, $email, $phonenumber, $number_id, $role);
            
            if($stmt->execute()){
                $_SESSION['register_success'] = 'Η εγγραφή ολοκληρώθηκε επιτυχώς!';
            }else {
                $_SESSION['register_error'] = 'Παρουσιάστηκε σφάλμα κατά την εγγραφή.';
            }
            header("Location: index.php");

            exit();
        }
        
        // Εγγραφή Υπαλλήλου
        if($role === 'employee'){
            // Έλεγχος αποθηκευμένου email
            if ($checkEmail->num_rows === 0) {
                $_SESSION['register_error'] = 'Δεν υπάρχει εξουσιοδότηση για αυτό το email. Επικοινωνήστε με τον διαχειριστή.';
                $_SESSION['active_form'] = 'register';
                header("Location: index.php");
                exit();
            }

            $userData = $checkEmail->fetch_assoc();

            // Έλεγχος αν εχει γίνει ήδη εγγραφή πελάτη από τη μεριά του πελάτη
            if (!empty($userData['username']) || !empty($userData['password'])) {
                $_SESSION['register_error'] = 'Αυτός ο υπάλληλος έχει ήδη ολοκληρώσει την εγγραφή.';
                $_SESSION['active_form'] = 'register';
                header("Location: index.php");
                exit();
            }

            // Έλεγχος κωδικού ασφαλείας για το email
            $stmt = $conn->prepare("SELECT number_id FROM securitycodes WHERE email = ?");
            $stmt->bind_param("s",$email);
            $stmt->execute();
            $checkSecurity = $stmt->get_result();

            if ($checkSecurity->num_rows == 0) {
                $_SESSION['register_error'] = 'Δεν υπάρχει καταχωρημένος κωδικός ασφαλείας για αυτό το email.';
                $_SESSION['active_form'] = 'register';
                header("Location: index.php");
                exit();
            }

            // Έλεγχος σωστού κωδικού ασφαλείας
            $securityData = $checkSecurity->fetch_assoc();
            if ($securityData['number_id'] !== $number_id) {
                $_SESSION['register_error'] = 'Ο κωδικός αναγνώρισης δεν αντιστοιχεί στο email σας.';
                $_SESSION['active_form'] = 'register';
                header("Location: index.php");
                exit();
            }

            // Update του υπάρχοντα υπαλλήλου
            $stmt = $conn->prepare("UPDATE users
                SET username = ?,
                    password = ?,
                    phonenumber = ?,
                    role = ?
                WHERE email = ?"
            );
            $stmt->bind_param("sssss", $username, $password, $phonenumber, $role, $email);

            if ($stmt->execute()) {
                $_SESSION['register_success'] = 'Η εγγραφή ολοκληρώθηκε επιτυχώς!';
                session_regenerate_id(true);
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;
                $_SESSION['username'] = $username;
            } else {
                $_SESSION['register_error'] = 'Παρουσιάστηκε σφάλμα κατά την ενημέρωση των στοιχείων.';
                $_SESSION['active_form'] = 'register';
                header("Location: index.php");
                exit();
            }

            // Ανακατεύθυνση υπαλλήλου
            header("Location: indexEmployee.php");
            exit();
        }      
    } 
    // Σύνδεση Χρήστη
    else if(isset($_POST['login'])){
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];
        $number_id = isset($_POST['number_id']) ? preg_replace('/[^0-9]/','',$_POST['number_id']):null; 

        if(!$email){
            $_SESSION['login_error'] = 'Μη έγκυρο email';
            $_SESSION['active_form'] = 'login';
            header("Location: index.php");
            exit();
        }

        // Prepared Statement for SQL Injection
        $stmt = $conn->prepare("SELECT id, username, password, email, role, number_id FROM users WHERE email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows > 0){
            $user = $result->fetch_assoc();
            // Επαλήθευση ταυτότητας χρήστη μέσω σύγκρισης hashed κωδικού
            if(password_verify($password, $user['password'])){
                // Security fix: employees and admins must also provide their security code.
                // The code is checked BEFORE any session data is written, so a wrong code
                // never results in an authenticated session.
                if(in_array($user['role'], ['employee', 'admin'], true) && $number_id !== $user['number_id']){
                    $_SESSION['login_error'] = 'Λανθασμένος κωδικός ασφαλείας';
                    $_SESSION['active_form'] = 'login';
                    header("Location: index.php");
                    exit();
                }

                // Security fix: new session id after login (prevents session fixation)
                session_regenerate_id(true);

                // Δημιουργία authenticated session και αποθήκευση ρόλου
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Role-based authorization και ανακατεύθυνση χρήστη σε αντίστοιχη σελίδα
                if($user['role'] === 'customer'){
                    header("Location: index.php");
                } elseif($user['role'] === 'employee'){
                    header("Location: indexEmployee.php");
                } else {
                    header("Location: indexAdmin.php");
                }
                exit();
            }
        }

        $_SESSION['login_error'] = 'Λανθασμένο email ή κωδικός πρόσβασης';
        $_SESSION['active_form'] = 'login';
        header("Location: index.php");
        exit();

    } 

    //Εγγραγή Υπαλλήλου από διαχειριστή
    else if(isset($_POST['addEmployee'])){
        // Security fix: server-side authorization, only a logged-in admin can add employees
        if(!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin'){
            http_response_code(403);
            exit('Forbidden');
        }

        $email =  filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $phonenumber =  preg_replace('/[^0-9]/','', $_POST['phonenumber']);
        $number_id =  preg_replace('/[^0-9]/','', $_POST['number_id']);
        $role =  'employee';

        if (!$email || empty($number_id)) {
            $_SESSION['register_error'] = 'Το email και ο κωδικός αναγνώρισης είναι υποχρεωτικά.';
            $_SESSION['active_form'] = 'addEmployee';
            header("Location: indexAdmin.php");
            exit();
        }

        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $checkEmail = $stmt->get_result();
        
        if($checkEmail->num_rows > 0) {
            $_SESSION['register_error'] = 'Το Email υπάρχει ήδη καταχωρημένο!';
            $_SESSION['active_form'] = 'addEmployee';
            header("Location: indexAdmin.php");
            exit();
        }

        // Insert Χρήστη
        $stmt = $conn->prepare("INSERT INTO users (email, phonenumber, number_id, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss",$email, $phonenumber, $number_id, $role);
        $insertUser = $stmt->execute();

        // Insert Security Code
        $stmt = $conn->prepare("INSERT INTO securitycodes (number_id, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $number_id, $email);
        $insertSecurity = $stmt->execute();
         
        if ($insertUser && $insertSecurity) {
            $_SESSION['register_success'] = 'Η εγγραφή του υπαλλήλου ολοκληρώθηκε επιτυχώς!';
        } else {
            $_SESSION['register_error'] = 'Παρουσιάστηκε σφάλμα κατά την εισαγωγή στη βάση.';
        }
        header("Location: indexAdmin.php");
        exit();
    }

    //Εγγραγή Βιβλίου
    else if(isset($_POST['addBook'])){
        $title = trim($_POST['title']);
        $image_path = filter_var($_POST['image_path'], FILTER_SANITIZE_URL);
        $author = trim($_POST['author']);
        $publisher = trim($_POST['publisher']);
        $pages = filter_var($_POST['pages'], FILTER_VALIDATE_INT);
        $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
        $stars = filter_var($_POST['stars'], FILTER_VALIDATE_FLOAT);
        $description = trim($_POST['description']);

        // Security fix: server-side authorization, only logged-in admins/employees can add books
        if(!isset($_SESSION['email']) || !in_array($_SESSION['role'] ?? '', ['admin', 'employee'], true)){
            http_response_code(403);
            exit('Forbidden');
        }
        $role = $_SESSION['role'];

        $redirectPage = ($role === 'admin') ? 'indexAdmin.php' : 'indexEmployee.php';

        // Έλεγχος κενών πεδίων
        $fields = [
            'Τίτλος' => $title,
            'Εικόνα' => $image_path,
            'Συγγραφέας' => $author,
            'Εκδόσεις' => $publisher,
            'Σελίδες' => $pages,
            'Τιμή' => $price,
            'Αξιολόγηση' => $stars,
            'Περιγραφή' => $description
        ];

        $emptyFields = [];

        foreach ($fields as $name => $value) {
            if (empty($value)) {
                $emptyFields[] = $name;
            }
        }

        if (!empty($emptyFields)) {
            $emptyList = implode(", ", $emptyFields);
            $_SESSION['register_error'] = 'Συμπληρώστε τα ακόλουθα πεδία: ' . $emptyList;
            $_SESSION['active_form'] = 'addBook';
            //header("Location: indexAdmin.php");
            exit();
        }

        $stmt = $conn->prepare("SELECT id FROM books WHERE title = ? AND author = ? AND publisher = ?");
        $stmt->bind_param("sss", $title, $author, $publisher);
        $stmt->execute();
        $checkBook = $stmt->get_result();

        if ($checkBook->num_rows > 0) {
            $_SESSION['register_error'] = 'Το βιβλίο υπάρχει ήδη καταχωρημένο!';
            $_SESSION['active_form'] = 'addBook';
            header("Location: $redirectPage");
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO books (title, image_path, author, publisher, pages, price, stars, description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssidds", $title, $image_path, $author, $publisher, $pages, $price, $stars, $description);
        
        if ($stmt->execute()) {
            $_SESSION['register_success'] = 'Το βιβλίο προστέθηκε επιτυχώς!';
        } else {
            $_SESSION['register_error'] = 'Σφάλμα κατά την εισαγωγή!';
        }

        header("Location: $redirectPage");
        exit();
    }
?>