<?php
// modules/login.php
require_once 'db.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        header("Location: panel/home.php");
        exit;
    } else {
        $message = "Hatalı kullanıcı adı veya şifre.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap - MK Oyunu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Font & Basit Stil -->
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom, #0f0f0f, #1e1e1e);
            color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background-color: #2c2c2c;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.8);
            width: 100%;
            max-width: 400px;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #5cc6ff;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 6px;
            background-color: #1a1a1a;
            color: #f0f0f0;
            font-size: 16px;
        }

        .login-box input::placeholder {
            color: #aaa;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background-color: #5cc6ff;
            color: #000;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-box button:hover {
            background-color: #42a5f5;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            color: #ff6666;
            font-weight: bold;
        }

        .login-box .link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #999;
            font-size: 14px;
        }

        .login-box .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Giriş Yap</h2>
        <form method="POST" autocomplete="on">
            <input type="text" name="username" placeholder="Kullanıcı Adı" required>
            <input type="password" name="password" placeholder="Şifre" required>
            <button type="submit">Giriş Yap</button>
        </form>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <a href="register.php" class="link">Hesabınız yok mu? Kayıt Ol</a>
    </div>
</body>
</html>
