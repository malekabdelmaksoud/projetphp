<?php
session_start();

// Connexion PDO
$conn = new PDO("mysql:host=localhost;dbname=covoiturage;charset=utf8", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// Rediriger si non connecté
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

// Sécuriser l'affichage
function e($val) {
    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}
?>
