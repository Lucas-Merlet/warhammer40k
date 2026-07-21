<?php
// ============================================================
// LECTEUR DE FICHIER .env - ENV.PHP
// ============================================================
// Lit le fichier .env et charge chaque CLÉ=valeur
// dans une variable accessible via getenv() / $_ENV
// ============================================================

function chargerEnv($cheminFichier)
{
    // Si le fichier .env n'existe pas, on arrête proprement
    if (!file_exists($cheminFichier)) {
        die('Fichier de configuration .env introuvable.');
    }

    // On lit le fichier ligne par ligne
    // FILE_IGNORE_NEW_LINES : sans les retours à la ligne
    // FILE_SKIP_EMPTY_LINES : on saute les lignes vides
    $lignes = file($cheminFichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lignes as $ligne) {

        // On ignore les commentaires (lignes commençant par #)
        if (strpos(trim($ligne), '#') === 0) {
            continue;
        }

        // On coupe la ligne en deux au premier =
        // "DB_HOST=localhost" devient ['DB_HOST', 'localhost']
        [$cle, $valeur] = explode('=', $ligne, 2);

        $cle = trim($cle);
        $valeur = trim($valeur);

        // On stocke dans $_ENV pour y accéder partout
        $_ENV[$cle] = $valeur;
    }
}