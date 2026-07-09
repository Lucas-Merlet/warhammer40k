<?php

// ============================================================
// TRAITEMENT DE LA CONNEXION
// ============================================================
require_once(__DIR__ . '/../common/head.php');

// 1. Validation des champs
if (empty($_POST['login']) || empty($_POST['password'])) {
    header('Location: index.php?page=login&error=1');
    exit;
}

// 2. Recherche de l'utilisateur PAR LOGIN SEUL
$statement = $mysqlClient->prepare('SELECT * FROM users WHERE login = :login');
$statement->execute([
    'login' => $_POST['login'],
]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

// 3. Vérification du mot de passe avec password_verify
if ($user && password_verify($_POST['password'], $user['password_hash'])) {


    session_regenerate_id(true);


    // 4. Connexion réussie : le RÔLE entre en session
    $_SESSION['LOGGED_USER'] = [
        'id' => $user['id'],
        'pseudo' => $user['login'],
        'role' => $user['role'],
    ];

    header('Location: index.php?page=article');
    exit;

} else {
    // 5. Échec : message volontairement vague
    header('Location: index.php?page=login&error=1');
    exit;
}
