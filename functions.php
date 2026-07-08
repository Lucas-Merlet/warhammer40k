<?php 

function truncateString($string, $length = 100) {
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
    return 'figurine/' . $id . '-' . slugify($nom) . '.html';
}
?>