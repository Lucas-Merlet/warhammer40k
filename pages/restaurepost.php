<?php
require_once(__DIR__ . '/../common/head.php');
requireAdmin();

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo 'Identifiant invalide.';
    return;
}

// RESTAURATION : on remet deleted_at à NULL
// La figurine redevient active
$statement = $mysqlClient->prepare('UPDATE figurines SET deleted_at = NULL WHERE id = :id');
$statement->execute([
    'id' => (int)$_POST['id'],
]);

header('Location: index.php?page=corbeille&restored=1');
exit;