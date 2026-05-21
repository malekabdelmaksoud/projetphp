<?php
require_once 'config.php';
requireLogin();

$success = '';

// --- Annulation ---
if (isset($_GET['cancel'])) {
    $id = intval($_GET['cancel']);

    $stmt = $conn->prepare("SELECT id, trip_id FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $booking = $stmt->fetch();

    if ($booking) {
        $conn->prepare("DELETE FROM bookings WHERE id = ?")->execute([$id]);
        $conn->prepare("UPDATE trips SET seats = seats + 1 WHERE id = ?")->execute([$booking['trip_id']]);
        $success = "Réservation annulée avec succès.";
    }
}

// Récupérer les réservations de l'utilisateur
$stmt = $conn->prepare("SELECT b.id AS booking_id, b.created_at, t.departure, t.destination, t.trip_date, t.price, u.nom AS conducteur
    FROM bookings b
    JOIN trips t ON b.trip_id = t.id
    JOIN users u ON t.user_id = u.id
    WHERE b.user_id = ?
    ORDER BY t.trip_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();

include 'header.php';
?>

<div class="card">
    <h2>Mes réservations</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>

    <?php if (count($bookings) > 0): ?>
    <div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Départ</th>
                <th>Destination</th>
                <th>Date trajet</th>
                <th>Prix (DT)</th>
                <th>Conducteur</th>
                <th>Réservé le</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
            <tr>
                <td data-label="Départ"><?= e($booking['departure']) ?></td>
                <td data-label="Destination"><?= e($booking['destination']) ?></td>
                <td data-label="Date"><?= e($booking['trip_date']) ?></td>
                <td data-label="Prix"><?= e($booking['price']) ?> DT</td>
                <td data-label="Conducteur"><?= e($booking['conducteur']) ?></td>
                <td data-label="Réservé le"><?= e($booking['created_at']) ?></td>
                <td data-label="Action">
                    <a href="my_bookings.php?cancel=<?= $booking['booking_id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Annuler cette réservation ?')">Annuler</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php else: ?>
        <p>Aucune réservation. <a href="search.php">Rechercher un trajet</a></p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
