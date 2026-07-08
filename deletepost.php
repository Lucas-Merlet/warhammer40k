<?php

// 1. CONNEXION À LA BASE DE DONNÉES

include('connect.php');

// 2. RÉCUPÉRATION DES DONNÉES DU FORMULAIRE

$postData = $_POST;

// 3. VALIDATION DE L'ID

if (!isset($postData['id']) || !is_numeric($postData['id'])) {
    echo 'Il faut un identifiant valide pour supprimer une figurine.';
    return;
}

// 4. SUPPRESSION EN BASE DE DONNÉES

$deleteValeurStatement = $mysqlClient->prepare('DELETE FROM valeur WHERE figurine_id = :id');
$deleteValeurStatement->execute([
    'id' => (int)$postData['id'],
]);


$deleteFigurineStatement = $mysqlClient->prepare('DELETE FROM figurines WHERE id = :id');
$deleteFigurineStatement->execute([
    'id' => (int)$postData['id'],
]);
?>

<!-- 5. AFFICHAGE DE LA PAGE DE CONFIRMATION-->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Figurine supprimée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>
    <p>C'est supprimé !</p> <br>
    <a class="btn btn-primary" role="button" href="article.php">RETOUR</a>
</body>
</html>