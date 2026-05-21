<?php
require_once 'config.php';
requireLogin();

// Statistiques de l'utilisateur
$userId = $_SESSION['user_id'];

$stmtTrips = $conn->prepare("SELECT COUNT(*) FROM trips WHERE user_id = ?");
$stmtTrips->execute([$userId]);
$totalTrips = $stmtTrips->fetchColumn();

$stmtBookings = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ?");
$stmtBookings->execute([$userId]);
$totalBookings = $stmtBookings->fetchColumn();

include 'header.php';
?>

<div class="card">
    <h2>Bienvenue, <?= e($_SESSION['user_nom']) ?> 👋</h2>
    <p style="color: var(--text-secondary);">Voici un résumé de votre activité sur la plateforme.</p>

    <div class="stats-grid">
        <div class="stat-card stat-blue">
            <div class="stat-number"><?= $totalTrips ?></div>
            <div class="stat-label">Trajets publiés</div>
        </div>
        <div class="stat-card stat-green">
            <div class="stat-number"><?= $totalBookings ?></div>
            <div class="stat-label">Réservations</div>
        </div>
    </div>

    <div class="dashboard-actions">
        <a href="add_trip.php" class="btn btn-primary">➕ Ajouter un trajet</a>
        <a href="search.php" class="btn btn-success">🔍 Rechercher un trajet</a>
        <a href="my_trips.php" class="btn btn-primary">🗺️ Mes trajets</a>
        <a href="my_bookings.php" class="btn btn-success">📋 Mes réservations</a>
    </div>
</div>

<?php include 'footer.php'; ?>
