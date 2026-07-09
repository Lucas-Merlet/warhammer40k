<?php
require_once(__DIR__ . '/../common/head.php');
requireAdmin();

// RÉCUPÉRATION DES DONNÉES DU FORMULAIRE
$postData = $_POST;

// VALIDATION DE L'ID
if (!isset($postData['id']) || !is_numeric($postData['id'])) {
    echo 'Il faut un identifiant valide pour supprimer une figurine.';
    return;
}

// SUPPRESSION EN BASE DE DONNÉES
// D'abord valeur (clé étrangère), puis la figurine
$deleteValeurStatement = $mysqlClient->prepare('DELETE FROM valeur WHERE figurine_id = :id');
$deleteValeurStatement->execute([
    'id' => (int)$postData['id'],
]);

$deleteFigurineStatement = $mysqlClient->prepare('DELETE FROM figurines WHERE id = :id');
$deleteFigurineStatement->execute([
    'id' => (int)$postData['id'],
]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Figurine supprimée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <p>C'est supprimé !</p> <br>
    <a class="btn btn-primary" role="button" href="index.php?page=article">RETOUR</a>
</body>
</html>