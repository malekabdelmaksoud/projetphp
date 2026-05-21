<?php
require_once 'config.php';
requireLogin();

$success = '';
$error   = '';

// --- delete trajet ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $stmt = $conn->prepare("SELECT id FROM trips WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);

    if ($stmt->fetch()) {
        $conn->prepare("DELETE FROM bookings WHERE trip_id = ?")->execute([$id]);
        $conn->prepare("DELETE FROM trips WHERE id = ?")->execute([$id]);
        $success = "Trajet supprimé avec succès.";
    }
}

// Récupérer les trajets de l'utilisateur
$stmt = $conn->prepare("SELECT t.*,
    (SELECT COUNT(*) FROM bookings b WHERE b.trip_id = t.id) AS total_reservations
    FROM trips t WHERE t.user_id = ? ORDER BY t.trip_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$trips = $stmt->fetchAll();

include 'header.php';
?>

<div class="card">
    <h2>Mes trajets</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>

    <?php if (count($trips) > 0): ?>
    <div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Départ</th>
                <th>Destination</th>
                <th>Date</th>
                <th>Places restantes</th>
                <th>Réservations</th>
                <th>Prix</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($trips as $trip): ?>
            <tr>
                <td data-label="Départ"><?= e($trip['departure']) ?></td>
                <td data-label="Destination"><?= e($trip['destination']) ?></td>
                <td data-label="Date"><?= e($trip['trip_date']) ?></td>
                <td data-label="Places"><?= e($trip['seats']) ?></td>
                <td data-label="Réservations"><?= e($trip['total_reservations']) ?></td>
                <td data-label="Prix"><?= e($trip['price']) ?> DT</td>
                <td data-label="Action">
                    <a href="my_trips.php?delete=<?= $trip['id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Supprimer ce trajet ?')">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php else: ?>
        <p>Vous n'avez publié aucun trajet. <a href="add_trip.php">Ajouter un trajet</a></p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
