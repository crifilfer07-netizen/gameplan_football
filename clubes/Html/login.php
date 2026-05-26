<?php
session_start();

// LIGAÇÃO À BASE DE DADOS
$host = "localhost";
$user = "root";
$pass = "";
$db = "gameplan26";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

$msg = "";

// REGISTO
if (isset($_POST['register'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $msg = "Este email já está registado!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashed_password);

        if ($stmt->execute()) {
            $msg = "Conta criada com sucesso! Faça login.";
        } else {
            $msg = "Erro ao criar conta.";
        }
    }
}

// LOGIN
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            header("Location: main.html");
            exit();
        } else {
            $msg = "Email ou password incorretos";
        }
    } else {
        $msg = "Email ou password incorretos";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GamePlan Football</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --gold: #D4AF37;
    --dark-gray: #1A1A1A;
    --medium-gray: #2A2A2A;
    --light-gray: #CCCCCC;
}

* {
    box-sizing: border-box; /* 🔥 ESSENCIAL PARA CORRIGIR O PROBLEMA */
}

body {
    margin: 0;
    background: #0A0A0A;
    font-family: 'Poppins', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* CONTAINER */
.login-container {
    width: 100%;
    max-width: 420px;
    padding: 20px;
}

/* CARD */
.login-card {
    width: 100%;
    background: #1A1A1A;
    border-radius: 20px;
    padding: 40px 30px; /* 🔥 padding controlado */
    border: 1px solid rgba(212, 175, 55, 0.2);
}

/* LOGO */
.logo-container {
    text-align: center;
    margin-bottom: 30px;
}

.logo-container h1 {
    color: var(--gold);
    font-size: 2.5rem;
    margin: 0;
}

.logo-container p {
    color: var(--light-gray);
}

/* INPUTS */
.input-wrapper {
    position: relative;
    width: 100%;
    margin-bottom: 20px;
}

.input-wrapper i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gold);
}

.input-wrapper input {
    width: 100%;
    padding: 14px 15px 14px 45px;
    border-radius: 12px;
    border: none;
    background: var(--medium-gray);
    color: white;
    font-size: 1rem;
}

/* BOTÕES */
.login-btn {
    width: 100%;
    padding: 14px;
    margin-top: 10px;
    background: linear-gradient(135deg, #B8860B, #D4AF37);
    border: none;
    border-radius: 12px;
    font-weight: bold;
    cursor: pointer;
}

/* ERRO */
.error-message {
    color: red;
    margin-bottom: 15px;
    text-align: center;
}
</style>
</head>

<body>

<div class="login-container">
<div class="login-card">

<div class="logo-container">
<h1>GAMEPLAN</h1>
<p>Where Tactics Build Champions</p>
</div>

<?php if ($msg): ?>
<div class="error-message">
<?= htmlspecialchars($msg) ?>
</div>
<?php endif; ?>

<form method="POST">

<div class="input-wrapper">
<i class="fas fa-envelope"></i>
<input type="email" name="email" placeholder="Email" required>
</div>

<div class="input-wrapper">
<i class="fas fa-lock"></i>
<input type="password" name="password" placeholder="Password" required>
</div>

<button type="submit" name="login" class="login-btn">ENTRAR</button>
<button type="submit" name="register" class="login-btn">CRIAR CONTA</button>

</form>

</div>
</div>

</body>
</html>