<?php

// TRAITEMENT CHANGEMENT DE RÔLE

require_once(__DIR__ . '/../common/head.php');
requireAdmin();

if (!isset($_post['id']) || !is_numeric($_POST['id'])) {
    echo 'Identifiant invalide.';
    return;
}

$rolesAutorises = ['client', 'vendeur', 'admin'];

if (!isset($_POST['role']) || !in_array($_POST['role'], $rolesAutorises)) {
    echo 'Rôle invalide.';
    return;
}

$statement = $mysqlClient->prepare('UPDATE users SET role = :role WHERE id = :id');
$statement->execute([
    'role' => $_POST['role'],
    'id' => $_POST['id'],
]);

header('Location: index.php?page=users&success=1');
exit;