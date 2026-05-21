<?php
require_once 'config.php';

// Récupérer les derniers trajets disponibles
$stmt = $conn->prepare("SELECT t.*, u.nom AS conducteur 
    FROM trips t 
    JOIN users u ON t.user_id = u.id 
    WHERE t.trip_date >= CURDATE() AND t.seats > 0 
    ORDER BY t.trip_date ASC 
    LIMIT 5");
$stmt->execute();
//fetchAll() permet de récupérer tous les résultats de la requête sous forme de tableau associatif. Chaque élément du tableau représente une ligne de résultat, avec les clés correspondant aux noms des colonnes de la base de données.
$trips = $stmt->fetchAll();

include 'header.php';
?>

<!-- Hero -->
<div class="hero">
    <h1>🚗 Partagez vos trajets</h1>
    <p>Proposez, recherchez et réservez des trajets de covoiturage simplement.</p>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="add_trip.php" class="btn btn-primary">➕ Ajouter un trajet</a>
        <a href="search.php" class="btn btn-success">🔍 Rechercher un trajet</a>
    <?php else: ?>
        <a href="register.php" class="btn btn-primary">🚀 S'inscrire</a>
        <a href="login.php" class="btn btn-success">🔐 Se connecter</a>
    <?php endif; ?>
</div>

<!-- Derniers trajets -->
<div class="card">
    <h2>Derniers trajets disponibles</h2>

    <?php if (count($trips) > 0): ?>
    <div class="table-wrapper">
    <table>
        <thead>
        <tr>
            <th>Départ</th>
            <th>Destination</th>
            <th>Date</th>
            <th>Places</th>
            <th>Prix</th>
            <th>Conducteur</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($trips as $trip): ?>
        <tr>
            <td data-label="Départ"><?= e($trip['departure']) ?></td>
            <td data-label="Destination"><?= e($trip['destination']) ?></td>
            <td data-label="Date"><?= e($trip['trip_date']) ?></td>
            <td data-label="Places"><?= e($trip['seats']) ?> <span class="badge badge-available">Dispo</span></td>
            <td data-label="Prix"><?= e($trip['price']) ?> DT</td>
            <td data-label="Conducteur"><?= e($trip['conducteur']) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php else: ?>
        <p style="color: var(--text-muted); text-align: center; padding: 30px 0;">Aucun trajet disponible pour le moment.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
