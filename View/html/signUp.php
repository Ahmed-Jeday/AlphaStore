<?php
session_start();


require_once("../../config/Database.php");
include("../../Controller/AuthController.php");
include("../../Controller/ProfileController.php");

$errors_login = []; 
$errors_signup = [];


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {

    // CAS 1 : INSCRIPTION
    if ($_POST['action'] === 'register') {
        if (isset($_POST['user_name'], $_POST['email'], $_POST['password'])) {
            $result = AddUser($_POST);

            if (is_numeric($result)) {
                // On ne connecte pas encore l'utilisateur, il doit d'abord vérifier son email
                header("Location:verifie.php?email=" . urlencode($_POST['email']));
                exit;
            } else {
                $errors_signup = $result; // Récupère le tableau d'erreurs
            }
        }
    }

    // CAS 2 : CONNEXION
    elseif ($_POST['action'] === 'login') {
        if (isset($_POST['login_user'], $_POST['login_password'])) {
            // On prépare les données pour la fonction de login
            $loginData = [
                'email' => $_POST['login_user'], // ou username selon ta DB
                'password' => $_POST['login_password']
            ];

            $result = loginUser($dbConnection, $loginData);

            if ($result === true) {
                header("Location:../my-account/my-account.php");
                exit;
            } else {
                $errors_login = [$result]; // Stocke le message d'erreur (ex: "Identifiants incorrects")
            }
        }
    }
}

?>







<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alpha Store - Login/Signup</title>
    <link rel="stylesheet" href="../css/style_1.css">
    <link rel="stylesheet" href="../css/signUp.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .password-box {
    position: relative;
}

.password-box .toggle-password {
    position: absolute;
    right: 40px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 20px;
    color: #555;
}
    </style>
</head>

<body>
    
    
    <div class="container">
        <div class="form-box login">
            <form action="signUp.php" method="post">
                <h1>Login</h1>
                <?php if (isset($_GET['verified'])): ?>
                    <div class="input_success" style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 0.85rem; border: 1px solid #bbf7d0;">
                         <i class='bx bx-check-circle'></i> Email vérifié ! Vous pouvez maintenant vous connecter.
                    </div>
                <?php endif; ?>
                <?php if (!empty($errors_login)): ?>
                    <div class="input_error" style="background: #fee2e2; color: #dc2626; padding: 15px; border-radius: 5px; margin-bottom: 10px; font-size: 0.8rem;">
                        <?php foreach ($errors_login as $error) echo "• $error<br>"; ?>
                    </div>
                <?php endif; ?>

                <div class="input-box">
                    <input type="email" name="login_user" placeholder="Email" 
                    value="<?= htmlspecialchars($_POST["login_user"] ?? "") ?>">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box password-box">
    <input type="password" name="login_password" placeholder="Password" minlength="8" required>
    <i class='bx bxs-lock-alt'></i>
    <i class='bx bx-hide toggle-password'></i>
</div>
                <div class="forgot-link">
                    <a href="forget-password.php">Forgot Password?</a>
                </div>
                <button type="submit" name="action" value="login" class="btn">Login</button>
                <p>or login with social platforms</p>
                <div class="social-icons">
                    <a href="#" id="google"><i class='bx bxl-google'></i></a>
                    <a href="#" id="facebook"><i class='bx bxl-facebook'></i></a>
                    <a href="#" id="instagram"><i class='bx bxl-instagram'></i></a>
                    <a href="#" id="linkedin"><i class='bx bxl-linkedin'></i></a>
                </div>
            </form>
        </div>

        <div class="form-box register">
            <form action="signUp.php" method="post">
                <h1>Registration</h1>
                  <?php if (!empty($errors_signup )): ?>
                    <div class="input_error" style="background: #fee2e2; color: #dc2626; padding: 10px; border-radius: 5px; margin-bottom: 10px; font-size: 0.8rem;">
                        <?php foreach ($errors_signup as $error) echo "• $error<br>"; ?>
                    </div>
                <?php endif; ?>
                <div class="input-box">
                    <input type="text" name="user_name" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box password-box">
    <input type="password" name="password" placeholder="Password" required>
    <i class='bx bxs-lock-alt'></i>
    <i class='bx bx-hide toggle-password'></i>
</div>
                <button type="submit" value="register" name="action" class="btn">Register</button>
                <p>or register with social platforms</p>
                <div class="social-icons">
                    <a href="#" id="google"><i class='bx bxl-google'></i></a>
                    <a href="#" id="facebook"><i class='bx bxl-facebook'></i></a>
                    <a href="#" id="instagram"><i class='bx bxl-instagram'></i></a>
                    <a href="#" id="linkedin"><i class='bx bxl-linkedin'></i></a>
                </div>
            </form>
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <button class="btn register-btn">Register</button>
            </div>

            <div class="toggle-panel toggle-right">
                <h1>Welcome Back!</h1>
                <p>Already have an account?</p>
                <button class="btn login-btn">Login</button>
            </div>
        </div>
    </div>

    <script src="../javaScript/signUp.js"></script>


    <script>
        document.querySelectorAll(".toggle-password").forEach(icon => {
    icon.addEventListener("click", () => {
        const input = icon.parentElement.querySelector("input");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bx-hide");
            icon.classList.add("bx-show");
        } else {
            input.type = "password";
            icon.classList.remove("bx-show");
            icon.classList.add("bx-hide");
        }
    });
});
    </script>
</body>



</html>