<?php
require_once 'config.php';
requireLogin();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $departure   = trim($_POST['departure'] ?? '');
    $destination = trim($_POST['destination'] ?? '');
    $trip_date   = $_POST['trip_date'] ?? '';
    $seats       = intval($_POST['seats'] ?? 0);
    $price       = floatval($_POST['price'] ?? 0);

    // Validation
    if (empty($departure) || empty($destination) || empty($trip_date) || $seats <= 0 || $price <= 0) {
        $error = "Tous les champs sont obligatoires et doivent être valides.";
    } elseif ($trip_date < date('Y-m-d')) {
        $error = "La date du trajet ne peut pas être dans le passé.";
    } else {
        $stmt = $conn->prepare("INSERT INTO trips (user_id, departure, destination, trip_date, seats, price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $departure, $destination, $trip_date, $seats, $price]);
        $success = "Trajet ajouté avec succès !";
        // Réinitialiser les champs
        $departure = $destination = $trip_date = '';
        $seats = $price = '';
    }
}

include 'header.php';
?>

<div class="card">
    <h2>Ajouter un trajet</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="add_trip.php">
        <div class="form-group">
            <label for="departure">Ville de départ</label>
            <input type="text" id="departure" name="departure" value="<?= e($departure ?? '') ?>" placeholder="Ex: Casablanca" required>
        </div>
        <div class="form-group">
            <label for="destination">Ville de destination</label>
            <input type="text" id="destination" name="destination" value="<?= e($destination ?? '') ?>" placeholder="Ex: Rabat" required>
        </div>
        <div class="form-group">
            <label for="trip_date">Date du trajet</label>
            <input type="date" id="trip_date" name="trip_date" value="<?= e($trip_date ?? '') ?>" min="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
            <label for="seats">Nombre de places</label>
            <input type="number" id="seats" name="seats" value="<?= e($seats ?? '') ?>" min="1" max="8" placeholder="1-8" required>
        </div>
        <div class="form-group">
            <label for="price">Prix par place (DT)</label>
            <input type="number" id="price" name="price" value="<?= e($price ?? '') ?>" min="1" step="0.5" placeholder="Ex: 50" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">➕ Ajouter le trajet</button>
    </form>
</div>

<?php include 'footer.php'; ?>