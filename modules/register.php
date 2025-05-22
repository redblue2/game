<?php
require_once 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password_plain = $_POST["password"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Geçerli bir e-posta adresi giriniz.";
    } elseif (strlen($username) < 3) {
        $message = "❌ Kullanıcı adı en az 3 karakter olmalı.";
    } elseif (strlen($password_plain) < 6) {
        $message = "❌ Şifre en az 6 karakter olmalı.";
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $message = "❌ Bu kullanıcı adı veya e-posta zaten kayıtlı.";
        } else {
            $password = password_hash($password_plain, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");

            try {
                $stmt->execute([$username, $password, $email]);
                $message = "✅ Kayıt başarılı! Giriş yapabilirsiniz.";
            } catch (PDOException $e) {
                $message = "⚠️ Bir hata oluştu. Lütfen tekrar deneyin.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol - MK Oyunu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom, #111, #1f1f1f);
            color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .register-box {
            background-color: #2b2b2b;
            padding: 35px 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.7);
            width: 100%;
            max-width: 450px;
        }

        .register-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #6ec6ff;
        }

        .register-box input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 6px;
            background-color: #1a1a1a;
            color: #f0f0f0;
            font-size: 16px;
        }

        .register-box input::placeholder {
            color: #aaa;
        }

        .register-box button {
            width: 100%;
            padding: 12px;
            background-color: #6ec6ff;
            color: #000;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .register-box button:hover {
            background-color: #42a5f5;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            color: #ff8888;
            font-weight: bold;
        }

        .message.success {
            color: #9cff9c;
        }

        .register-box .link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #aaa;
            font-size: 14px;
        }

        .register-box .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Kayıt Ol</h2>
        <form method="POST" autocomplete="on">
            <input type="text" name="username" placeholder="Kullanıcı Adı" required>
            <input type="email" name="email" placeholder="E-Posta Adresi" required>
            <input type="password" name="password" placeholder="Şifre" required>
            <button type="submit">Kayıt Ol</button>
        </form>
        <?php if ($message): ?>
            <p class="message <?php echo (str_starts_with($message, "✅") ? 'success' : ''); ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>
        <a href="login.php" class="link">Zaten hesabınız var mı? Giriş Yap</a>
    </div>
</body>
</html>
