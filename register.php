<?php
require_once 'config.php';

// Si déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom      = trim($_POST['nom']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if (empty($nom) || empty($email) || empty($password) || empty($confirm)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif ($password != $confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si email existe
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {
            // Insérer l'utilisateur
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (nom, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$nom, $email, $hash]);

            // Connexion automatique
            $_SESSION['user_id']  = $conn->lastInsertId();
            $_SESSION['user_nom'] = $nom;
            header("Location: dashboard.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - CovoiturageApp</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php" class="logo">CovoiturageApp</a>
    <div class="nav-links">
        <a href="login.php">Connexion</a>
        <a href="register.php" class="active">Inscription</a>
    </div>
</div>

<div class="container">
    <div class="auth-box">
        <div class="card">
            <div class="icon-header">
                <div class="icon-circle">🚀</div>
            </div>
            <h2>Créer un compte</h2>
            <p class="auth-subtitle">Rejoignez la communauté de covoiturage</p>

            <?php if ($error): ?>
                <div class="error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" id="nom" name="nom" value="<?= e($_POST['nom'] ?? '') ?>" placeholder="Votre nom" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" placeholder="votre@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="form-group">
                    <label for="confirm">Confirmer le mot de passe</label>
                    <input type="password" id="confirm" name="confirm" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary">Créer mon compte</button>
            </form>

            <div class="auth-divider">ou</div>

            <p>
                Déjà inscrit ? <a href="login.php">Se connecter</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
