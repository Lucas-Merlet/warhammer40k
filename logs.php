<?php

require_once(__DIR__ . '/head.php');
requireAdmin();

// VISIONNEUSE DE LOGS - LOGS.PHP

$fichier_logs = 'figurines_logs.json';

// LECTURE SÉCURISÉE DU FICHIER

if (!file_exists($fichier_logs)) {
    die("Le fichier $fichier_logs n'existe pas.");
}
$contenu_json = file_get_contents($fichier_logs);
if ($contenu_json === false) {
    die("Impossible de lire le fichier $fichier_logs.");
}
$logs = json_decode($contenu_json, true);
if ($logs === null) {
    die("Erreur lors du décodage des logs JSON.");
}

// FONCTION D'AFFICHAGE D'UNE ENTRÉE DE LOG

function afficher_log($log)
{
    echo "<div class='card mb-3'>";
    echo "<div class='card-body'>";
    echo "<span class='badge bg-warning text-dark'>" . htmlspecialchars($log['timestamp']) . "</span> ";
    echo "<span class='badge bg-primary'>" . htmlspecialchars($log['action']) . "</span>";
    echo "<p class='text-muted small mb-1 mt-2'>";
    echo "IP : " . htmlspecialchars($log['ip']) . " — ";
    echo "Navigateur : " . htmlspecialchars($log['user_agent']);
    echo "</p>";
    echo "<pre class='bg-light p-2 rounded small'>" . htmlspecialchars(json_encode($log['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";

    echo "</div>";
    echo "</div>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs des figurines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Journal des modifications</h1>
            <a href="article.php" class="btn btn-outline-secondary">RETOUR</a>
        </div>

        <p class="text-muted"><?= count($logs); ?> entrée(s) enregistrée(s)</p>
        <hr>

        <?php
        foreach (array_reverse($logs) as $log) {
            afficher_log($log);
        }
        ?>

    </div>
</body>
</html>