<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CovoiturageApp</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php" class="logo">CovoiturageApp</a>

    <!-- Hamburger button (mobile only) -->
    <button class="hamburger" id="hamburger" aria-label="Menu" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Overlay (mobile only) -->
    <div class="nav-overlay" id="navOverlay" onclick="toggleMenu()"></div>

    <div class="nav-links" id="navLinks">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Tableau de bord</a>
            <a href="add_trip.php">Ajouter trajet</a>
            <a href="search.php">Rechercher</a>
            <a href="my_trips.php">Mes trajets</a>
            <a href="my_bookings.php">Réservations</a>
            <a href="logout.php">Déconnexion</a>
        <?php else: ?>
            <a href="login.php">Connexion</a>
            <a href="register.php">Inscription</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">

<script>
function toggleMenu() {
    const nav = document.getElementById('navLinks');
    const hamburger = document.getElementById('hamburger');
    const overlay = document.getElementById('navOverlay');
    nav.classList.toggle('open');
    hamburger.classList.toggle('active');
    overlay.classList.toggle('open');
    document.body.classList.toggle('menu-open');
}
</script>
