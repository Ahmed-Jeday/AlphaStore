<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Alpha Store</title>
    <link rel="stylesheet" href="../css/signUp.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }
        .verif-container {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .verif-container h1 {
            color: #222;
            margin-bottom: 10px;
            font-size: 24px;
        }
        .verif-container p {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .input-box {
            position: relative;
            margin-bottom: 20px;
            
        }
        .input-box input {
            width: 80%;
            padding: 15px 20px;
            padding-left: 45px;
            border: 2px solid #eee;
            border-radius: 10px;
            outline: none;
            transition: 0.3s;
            font-size: 16px;
            letter-spacing: 2px;
            text-align: center;
        }
        .input-box input:focus {
            border-color: #222;
        }
        .input-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #888;
        }
        .btn {
            background: #222;
            color: #fff;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn:hover {
            background: #444;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: transparent;
            color: #222;
            border: 2px solid #222;
            padding: 15px;
            width: 100%;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 15px;
            box-sizing: border-box;
        }
        .btn-secondary:hover {
            background: #eee;
            transform: translateY(-2px);
        }
        .error-msg {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .success-msg {
            background: #dcfce7;
            color: #166534;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            border: 1px solid #bbf7d0;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            color: #888;
            text-decoration: none;
            font-size: 13px;
        }
        .back-link:hover {
            color: #222;
        }

    </style>
</head>
<body>
    <div class="verif-container">
        <i class='bx bxs-envelope' style="font-size: 50px; color: #222; margin-bottom: 20px;"></i>
        <h1>Verify Your Email</h1>
        <p>We've sent a 6-digit code to <strong><?= htmlspecialchars($_GET['email'] ?? 'your email') ?></strong></p>
        
        <?php if (isset($_GET['resent'])): ?>
            <div class="success-msg">
                <i class='bx bx-check-circle'></i> Un nouveau code OTP a été envoyé à votre adresse email !
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-msg">
                <i class='bx bx-error-circle'></i> <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="../../index.php?action=verif_code">
            <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
            
            <div class="input-box" id="input_box">
                <input type="text" name="code" placeholder="------" maxlength="6" required autofocus>
                <i class='bx bx-key'></i>
            </div>
            
            <button type="submit" class="btn">Verify Account</button>
        </form>

        <form method="POST" action="../../index.php?action=resend_otp">
            <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
            <button type="submit" class="btn-secondary">
                <i class='bx bx-refresh' style="font-size: 20px;"></i> Renvoyer le code
            </button>
        </form>

        <a href="signUp.php" class="back-link">Back to Registration</a>
    </div>
</body>
</html>