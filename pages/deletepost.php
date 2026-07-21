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

// ============================================
// SOFT DELETE : on MARQUE la figurine comme supprimée
// au lieu de l'effacer. NOW() = date et heure actuelles
// La figurine et son prix restent en base, récupérables
// ============================================
$statement = $mysqlClient->prepare('UPDATE figurines SET deleted_at = NOW() WHERE id = :id');
$statement->execute([
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
    <div class="container my-5">
        <div class="alert alert-success">La figurine a été mise à la corbeille.</div>
        <a class="btn btn-primary" role="button" href="index.php?page=article">Retour au catalogue</a>
        <a class="btn btn-outline-secondary" role="button" href="index.php?page=corbeille">Voir la corbeille</a>
    </div>
</body>
</html>