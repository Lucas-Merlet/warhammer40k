<?php
// 1. CHARGEMENT DES COMMUNS + PROTECTION ADMIN
require_once(__DIR__ . '/../common/head.php');
requireGestion();

// 2. RÉCUPÉRATION DES DONNÉES DU FORMULAIRE
$postData = $_POST;

// 3. VALIDATION DES DONNÉES
if (
    empty($postData['nom'])
    || empty($postData['faction'])
    || empty($postData['description'])
    || empty($postData['etat'])
    || trim(strip_tags($postData['nom'])) === ''
    || trim(strip_tags($postData['faction'])) === ''
    || trim(strip_tags($postData['description'])) === ''
) {
    echo 'Il faut un nom + une faction + une description + un état pour soumettre le formulaire, sinon ça marche pas.';
    return;
}

// 4. NETTOYAGE DES DONNÉES (SÉCURITÉ)
$nom = trim(strip_tags($postData['nom']));
$faction = trim(strip_tags($postData['faction']));
$description = trim(strip_tags($postData['description']));
$etat = trim(strip_tags($postData['etat']));

// 5. INSERTION EN BASE DE DONNÉES
$insertcontenu = $mysqlClient->prepare('INSERT INTO figurines(nom, faction, description, etat, en_exposition, vendeur_id, date_ajout) VALUES (:nom, :faction, :description, :etat, :en_exposition, :vendeur_id, :date_ajout)');
$insertcontenu->execute([
    'nom' => $nom,
    'faction' => $faction,
    'description' => $description,
    'etat' => $etat,
    'en_exposition' => 0,
    'vendeur_id' => 1,
    'date_ajout' => date('Y-m-d'),
]);

$figurineId = $mysqlClient->lastInsertId();

// 6. INSERTION DU PRIX SI RENSEIGNÉ
if (!empty($postData['prix_vente']) && !empty($postData['cote_marche'])) {

    $insertValeur = $mysqlClient->prepare('INSERT INTO valeur(figurine_id, prix_vente, cote_marche, date_estimation) VALUES (:figurine_id, :prix_vente, :cote_marche, :date_estimation)');
    $insertValeur->execute([
        'figurine_id' => $figurineId,
        'prix_vente' => trim(strip_tags($postData['prix_vente'])),
        'cote_marche' => trim(strip_tags($postData['cote_marche'])),
        'date_estimation' => date('Y-m-d'),
    ]);
}

// 7. TRAITEMENT DE L'IMAGE UPLOADÉE
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    // VALIDATION DU FICHIER (SÉCURITÉ)
    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
        echo 'Image trop lourde : 2 Mo maximum.';
        return;
    }

    $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $extensionsAutorisees = ['webp', 'jpg', 'jpeg', 'png'];

    if (!in_array($extension, $extensionsAutorisees)) {
        echo 'Format non autorisé. Formats acceptés : webp, jpg, jpeg, png.';
        return;
    }

    if (getimagesize($_FILES['image']['tmp_name']) === false) {
        echo 'Le fichier n\'est pas une image valide.';
        return;
    }

    // DÉPLACEMENT VERS public/assets/img/
    // Chemin DISQUE : depuis pages/, on remonte puis
    // on descend dans public/assets/img/
    if ($extension === 'jpeg') {
        $extension = 'jpg';
    }
    $destination = __DIR__ . '/../public/assets/img/' . $figurineId . '.' . $extension;
    move_uploaded_file($_FILES['image']['tmp_name'], $destination);
}
?>
<!-- ============================================
     8. AFFICHAGE DE LA PAGE DE CONFIRMATION
     ============================================ -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ajout dans BDD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <h1>Figurine ajoutée avec succès !</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= $nom; ?></h5>
                <p class="card-text"><b>Faction : <?php echo $faction; ?></b></p>
                <p class="card-text"><b>État : <?php echo $etat; ?></b></p>
                <p class="card-text"><?php echo $description; ?></p>
            </div>
        </div>
        <a class="btn btn-primary" role="button" href="index.php?page=article">RETOUR</a>
    </div>
</body>
</html>