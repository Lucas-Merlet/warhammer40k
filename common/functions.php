<?php

function truncateString($string, $length = 100)
{
    if (strlen($string) > $length) {
        return substr($string, 0, $length) . ' (...)';
    }
    return $string;
}

function libelleEtat($etat)
{
    $libelles = [
        'neuf' => 'Neuf sous blister',
        'monte' => 'Monté',
        'peint' => 'Peint',
        'peinture professionnelle' => 'Peinture professionnelle',
    ];
    return $libelles[$etat] ?? $etat;
}

function slugify($text)
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text); //remplace tout ce qui n'est pas lettre et chiffre par un tiret
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text); //convertit les accents en lettres simples
    $text = preg_replace('~[^-\w]+~', '', $text); //supprime les caractères restants indésirables
    $text = trim($text, '-'); //enlève les tirets en début et fin
    $text = strtolower($text); //tout en minuscules
    return empty($text) ? 'n-a' : $text;
}

function createFigurineUrl($id, $nom)
{
    // Chemin absolu vers le routeur public + belle URL
    return '/warhammer40k/public/figurine/' . $id . '-' . slugify($nom) . '.html';
}

// SYSTÈME DE RÔLES
// L'utilisateur est-il connecté (client OU admin) ?
function isLoggedIn(): bool
{
    return isset($_SESSION['LOGGED_USER']);
}

// L'utilisateur est-il administrateur ?
function isAdmin(): bool
{
    return isset($_SESSION['LOGGED_USER'])
        && $_SESSION['LOGGED_USER']['role'] === 'admin';
}

// Protège une page réservée aux admins :
// si pas admin → redirection vers l'accueil
function requireAdmin(): void
{
    if (!isAdmin()) {
        header('Location: index.php?page=article');
        exit;
    }
}

// DÉCONNEXION
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header('Location: index.php?page=article');
    exit;
}

// Fonction couleurFaction : retourne la couleur d'une faction
function couleurFaction($faction)
{
    $couleurs = [
        'Ultramarines'   => '#0F3D7C',   // bleu ultramar
        'Blood Angels'   => '#8A0303',   // rouge sang
        'Death Guard'    => '#4A5D23',   // vert putride
        'Thousand Sons'  => '#0D4F8B',   // bleu et or de Tzeentch
        'Orks'           => '#3D6B1F',   // vert peau d'ork
        'Demons de Khorne' => '#7B0A0A', // rouge de Khorne
        'World Eaters'   => '#7B0A0A',   // rouge de Khorne
        'Space Marines'  => '#1F4E79',   // bleu Space Marines
        'Necrons' => '#0A5C36',   // vert gauss Nécrons
        'Demons de Slaanesh' => '#6B2D5C',   // violet pourpre de Slaanesh
        'Demons de Tzeentch' => '#1B6CA8',   // bleu changeant de Tzeentch
    ];
    return $couleurs[$faction] ?? '#6C757D'; // gris par défaut
}
