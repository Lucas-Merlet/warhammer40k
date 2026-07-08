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
?>