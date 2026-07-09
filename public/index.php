<?php

// 1. CHARGEMENT DES COMMUNS

require_once(__DIR__ . '/../common/head.php');

// 2. LISTE DES PAGES AUTORISÉES

$pagesAutorisees = [
    'article', 'figurine',
    'add', 'addpost',
    'edit', 'editpost',
    'delete', 'deletepost',
    'login', 'loginpost',
    'register', 'registerpost',
    'logs',
];

// 3. QUELLE PAGE AFFICHER ?

$page = $_GET['page'] ?? 'article';
if (!in_array($page, $pagesAutorisees)) {
    $page = 'article';
}

// 4. CHARGEMENT DE LA PAGE

require(__DIR__ . '/../pages/' . $page . '.php');
