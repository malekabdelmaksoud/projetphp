<?php
require_once 'config.php';
requireLogin();

$success = '';
$error   = '';

if (isset($_GET['book'])) {
    $trip_id = intval($_GET['book']);

    
    $stmt = $conn->prepare("SELECT * FROM trips WHERE id = ? AND seats > 0 AND user_id != ?");
    $stmt->execute([$trip_id, $_SESSION['user_id']]);
    $trip = $stmt->fetch();

    if (!$trip) {
        $error = "Trajet non disponible.";
    } else {
    
        $check = $conn->prepare("SELECT id FROM bookings WHERE trip_id = ? AND user_id = ?");
        $check->execute([$trip_id, $_SESSION['user_id']]);

        if ($check->fetch()) {
            $error = "Vous avez déjà réservé ce trajet.";
        } else {
            $conn->prepare("INSERT INTO bookings (trip_id, user_id) VALUES (?, ?)")->execute([$trip_id, $_SESSION['user_id']]);
            $conn->prepare("UPDATE trips SET seats = seats - 1 WHERE id = ?")->execute([$trip_id]);
            $success = "Réservation effectuée avec succès !";
        }
    }
}


$departure   = trim($_GET['departure'] ?? '');
$destination = trim($_GET['destination'] ?? '');
$date        = $_GET['date'] ?? '';

$where  = "WHERE t.trip_date >= CURDATE() AND t.seats > 0 AND t.user_id != ?";
$params = [$_SESSION['user_id']];

if (!empty($departure)) {
    $where   .= " AND t.departure LIKE ?";
    $params[] = "%$departure%";
}
if (!empty($destination)) {
    $where   .= " AND t.destination LIKE ?";
    $params[] = "%$destination%";
}
if (!empty($date)) {
    $where   .= " AND t.trip_date = ?";
    $params[] = $date;
}

$sql = "SELECT t.*, u.nom AS conducteur,
        (SELECT COUNT(*) FROM bookings b WHERE b.trip_id = t.id AND b.user_id = ?) AS already_booked
        FROM trips t
        JOIN users u ON t.user_id = u.id
        $where
        ORDER BY t.trip_date ASC";

$stmt = $conn->prepare($sql);
$stmt->execute(array_merge([$_SESSION['user_id']], $params));
$trips = $stmt->fetchAll();

include 'header.php';
?>

<div class="card">
    <h2>Rechercher un trajet</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="GET" action="search.php">
        <div class="search-row">
            <div class="form-group">
                <label>Départ</label>
                <input type="text" name="departure" value="<?= e($departure) ?>" placeholder="Ville de départ">
            </div>
            <div class="form-group">
                <label>Destination</label>
                <input type="text" name="destination" value="<?= e($destination) ?>" placeholder="Ville d'arrivée">
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" value="<?= e($date) ?>">
            </div>
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </div>
    </form>
</div>

<div class="card">
    <h2>Résultats (<?= count($trips) ?>)</h2>

    <?php if (count($trips) > 0): ?>
    <div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Départ</th>
                <th>Destination</th>
                <th>Date</th>
                <th>Places</th>
                <th>Prix (DT)</th>
                <th>Conducteur</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($trips as $trip): ?>
            <tr>
                <td data-label="Départ"><?= e($trip['departure']) ?></td>
                <td data-label="Destination"><?= e($trip['destination']) ?></td>
                <td data-label="Date"><?= e($trip['trip_date']) ?></td>
                <td data-label="Places">
                    <?= e($trip['seats']) ?>
                    <span class="badge badge-available">Dispo</span>
                </td>
                <td data-label="Prix"><?= e($trip['price']) ?> DT</td>
                <td data-label="Conducteur"><?= e($trip['conducteur']) ?></td>
                <td data-label="Action">
                    <?php if ($trip['already_booked'] > 0): ?>
                        <span style="color:#94a3b8; font-size:13px;">Déjà réservé</span>
                    <?php else: ?>
                        <a href="search.php?book=<?= $trip['id'] ?>&departure=<?= e($departure) ?>&destination=<?= e($destination) ?>&date=<?= e($date) ?>"
                           class="btn btn-success btn-sm"
                           onclick="return confirm('Confirmer la réservation ?')">Réserver</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php else: ?>
        <p>Aucun trajet trouvé.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
