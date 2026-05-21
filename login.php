<?php
require_once 'config.php';

// Si déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];

            // Cookie  : sauvegarder l'email si "Se souvenir" est coché
            if (isset($_POST['remember'])) {
                setcookie('user_email', $email, time() + 30 * 24 * 3600);  // 30 jours
            } else {
                setcookie('user_email');  // Supprimer le cookie
            }

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }     
}

// Lire le cookie pour pré-remplir l'email
$saved_email = $_COOKIE['user_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - CovoiturageApp</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php" class="logo">CovoiturageApp</a>
    <div class="nav-links">
        <a href="login.php" class="active">Connexion</a>
        <a href="register.php">Inscription</a>
    </div>
</div>

<div class="container">
    <div class="auth-box">
        <div class="card">
            <div class="icon-header">
                <div class="icon-circle">🔐</div>
            </div>
            <h2>Bon retour !</h2>
            <p class="auth-subtitle">Connectez-vous pour accéder à vos trajets</p>

            <?php if ($error): ?>
                <div class="error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= e($saved_email) ?>" placeholder="votre@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember" <?= $saved_email ? 'checked' : '' ?>>
                    <label for="remember">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>

            <div class="auth-divider">ou</div>

            <p>
                Pas encore inscrit ? <a href="register.php">Créer un compte</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
