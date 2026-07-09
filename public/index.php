<?php

<<<<<<< HEAD
=======
// POINT D'ENTRÉE UNIQUE - PUBLIC/INDEX.PHP

>>>>>>> ae80e298b13d2257e5c8c6446b33a0f3b8470326
// 1. CHARGEMENT DES COMMUNS

require_once(__DIR__ . '/../common/head.php');

<<<<<<< HEAD
// 2. LISTE DES PAGES AUTORISÉES
=======
// 2. LISTE BLANCHE DES PAGES AUTORISÉES
>>>>>>> ae80e298b13d2257e5c8c6446b33a0f3b8470326

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
