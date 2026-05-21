<?php
session_start();
session_destroy();

// Supprimer le cookie
setcookie('user_email');

header("Location: index.php");
exit;
?>
