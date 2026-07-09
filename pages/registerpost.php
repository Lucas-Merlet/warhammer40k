<?php

// ============================================================
// TRAITEMENT DE L'INSCRIPTION - REGISTERPOST.PHP
// ============================================================
require_once(__DIR__ . '/../common/head.php');

// ============================================
// 1. VALIDATION : tous les champs présents ?
// ← AJOUT : le mail dans la liste des champs obligatoires
// ============================================
if (
    empty($_POST['login'])
    || empty($_POST['mail'])
    || empty($_POST['password'])
    || empty($_POST['password_confirm'])
) {
    header('Location: index.php?page=register&error=champs');
    exit;
}

// ============================================
// 2. NETTOYAGE DU LOGIN
// ============================================
$login = trim(strip_tags($_POST['login']));

if ($login === '') {
    header('Location: index.php?page=register&error=champs');
    exit;
}

// ============================================
// 2bis. VALIDATION DU FORMAT EMAIL ← NOUVEAU BLOC
// filter_var vérifie la structure : xxx@yyy.zzz
// Le type="email" HTML5 est contournable,
// on revalide TOUJOURS côté serveur !
// ============================================
$mail = trim($_POST['mail']);

if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.php?page=register&error=mail');
    exit;
}

// ============================================
// 3. VALIDATION DU MOT DE PASSE
// ============================================
if (strlen($_POST['password']) < 8) {
    header('Location: index.php?page=register&error=mdp_court');
    exit;
}

if ($_POST['password'] !== $_POST['password_confirm']) {
    header('Location: index.php?page=register&error=mdp_diff');
    exit;
}

// ============================================
// 4. LE LOGIN EST-IL DÉJÀ PRIS ?
// ============================================
$statement = $mysqlClient->prepare('SELECT id FROM users WHERE login = :login');
$statement->execute([
    'login' => $login,
]);

if ($statement->fetch()) {
    header('Location: index.php?page=register&error=login');
    exit;
}

// ============================================
// 4bis. L'EMAIL EST-IL DÉJÀ UTILISÉ ? ← NOUVEAU BLOC
// Même logique que pour le login : la colonne est
// UNIQUE en base, on vérifie avant pour un message propre
// ============================================
$statement = $mysqlClient->prepare('SELECT id FROM users WHERE mail = :mail');
$statement->execute([
    'mail' => $mail,
]);

if ($statement->fetch()) {
    header('Location: index.php?page=register&error=mail_pris');
    exit;
}

// ============================================
// 5. CRÉATION DU COMPTE (rôle FORCÉ à client)
// ← MODIFIÉ : la colonne mail entre dans l'INSERT
// ============================================
$insertStatement = $mysqlClient->prepare('
    INSERT INTO users (login, mail, password_hash, role, date_inscription)
    VALUES (:login, :mail, :password_hash, :role, :date_inscription)
');
$insertStatement->execute([
    'login' => $login,
    'mail' => $mail,
    'password_hash' => password_hash($_POST['password'], PASSWORD_DEFAULT),
    'role' => 'client',
    'date_inscription' => date('Y-m-d'),
]);

// ============================================
// 6. CONNEXION AUTOMATIQUE
// ← AJOUT : session_regenerate_id (chantier 4 de la grille,
// protection contre la fixation de session)
// ============================================
session_regenerate_id(true);

$_SESSION['LOGGED_USER'] = [
    'id' => $mysqlClient->lastInsertId(),
    'pseudo' => $login,
    'role' => 'client',
];

header('Location: index.php?page=article');
exit;
