<?php
// ============================================
// 1. CONNEXION À LA BASE DE DONNÉES
// ============================================
include('connect.php');
// ============================================
// 2. RÉCUPÉRATION DES DONNÉES DU FORMULAIRE
// ============================================
$postData = $_POST;
// ============================================
// 3. VALIDATION DES DONNÉES
// ============================================
if (
    empty($postData['nom'])                              // Vérifie si le champ 'nom' existe et n'est pas vide
    || empty($postData['faction'])                       // Vérifie si le champ 'faction' existe et n'est pas vide
    || empty($postData['description'])                   // Vérifie si le champ 'description' existe et n'est pas vide
    || empty($postData['etat'])                          // Vérifie si le champ 'etat' existe et n'est pas vide
    || trim(strip_tags($postData['nom'])) === ''         // Vérifie que le nom ne contient pas QUE des espaces/balises HTML
    || trim(strip_tags($postData['faction'])) === ''     // Vérifie que la faction ne contient pas QUE des espaces/balises HTML
    || trim(strip_tags($postData['description'])) === '' // Vérifie que la description ne contient pas QUE des espaces/balises HTML
) {
    echo 'Il faut un nom + une faction + une description + un état pour soumettre le formulaire, sinon ça marche pas.';
    return;
}
// ============================================
// 4. NETTOYAGE DES DONNÉES (SÉCURITÉ)
// ============================================
$nom = trim(strip_tags($postData['nom']));
$faction = trim(strip_tags($postData['faction']));
$description = trim(strip_tags($postData['description']));
$etat = trim(strip_tags($postData['etat']));
// ============================================
// 5. INSERTION EN BASE DE DONNÉES
// ============================================
$insertcontenu = $mysqlClient->prepare('INSERT INTO figurines(nom, faction, description, etat, en_exposition, vendeur_id, date_ajout) VALUES (:nom, :faction, :description, :etat, :en_exposition, :vendeur_id, :date_ajout)');
$insertcontenu->execute([
    'nom' => $nom,                     // Remplace :nom par la valeur de $nom
    'faction' => $faction,             // Remplace :faction par la valeur de $faction
    'description' => $description,     // Remplace :description par la valeur de $description
    'etat' => $etat,                   // Remplace :etat par la valeur de $etat
    'en_exposition' => 0,              // Par défaut la figurine n'est pas en exposition
    'vendeur_id' => 1,                 // Pour l'instant on met 1, mais on pourrait lier la figurine au vendeur connecté
    'date_ajout' => date('Y-m-d'),     // Génère automatiquement la date du jour au format YYYY-MM-DD
]);

// ============================================
// RÉCUPÉRATION DE L'ID DE LA FIGURINE CRÉÉE
// IMPORTANT : hors de tout if, juste après l'INSERT
// car les blocs prix ET image en ont besoin
// ============================================
$figurineId = $mysqlClient->lastInsertId();

// ============================================
// 6. INSERTION DU PRIX SI RENSEIGNÉ
// ============================================
if (!empty($postData['prix_vente']) && !empty($postData['cote_marche'])) {

    $insertValeur = $mysqlClient->prepare('INSERT INTO valeur(figurine_id, prix_vente, cote_marche, date_estimation) VALUES (:figurine_id, :prix_vente, :cote_marche, :date_estimation)');
    $insertValeur->execute([
        'figurine_id' => $figurineId,                                // L'id de la figurine créée juste au-dessus
        'prix_vente' => trim(strip_tags($postData['prix_vente'])),   // Nettoyage comme les autres champs
        'cote_marche' => trim(strip_tags($postData['cote_marche'])), // Nettoyage comme les autres champs
        'date_estimation' => date('Y-m-d'),                          // Date du jour
    ]);
}

// ============================================
// 7. TRAITEMENT DE L'IMAGE UPLOADÉE
// ============================================

// On vérifie qu'un fichier a été envoyé sans erreur
// UPLOAD_ERR_OK = code 0 = tout s'est bien passé
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    // ============================================
    // VALIDATION DU FICHIER (SÉCURITÉ)
    // ============================================

    // 1. Vérification de la taille : max 2 Mo
    // La taille est en octets : 2 * 1024 * 1024 = 2097152
    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
        echo 'Image trop lourde : 2 Mo maximum.';
        return;
    }

    // 2. Vérification de l'extension autorisée
    // pathinfo() extrait l'extension du nom de fichier
    $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $extensionsAutorisees = ['webp', 'jpg', 'jpeg', 'png'];

    if (!in_array($extension, $extensionsAutorisees)) {
        echo 'Format non autorisé. Formats acceptés : webp, jpg, jpeg, png.';
        return;
    }

    // 3. Vérification que c'est VRAIMENT une image
    // getimagesize() retourne false si le fichier n'est pas une image
    // (protection contre un fichier .php renommé en .jpg)
    if (getimagesize($_FILES['image']['tmp_name']) === false) {
        echo 'Le fichier n\'est pas une image valide.';
        return;
    }

    // ============================================
    // DÉPLACEMENT DU FICHIER VERS img/
    // ============================================

    // jpeg est renommé en jpg pour matcher le file_exists d'article.php
    if ($extension === 'jpeg') {
        $extension = 'jpg';
    }

    // On nomme le fichier avec l'id de la figurine créée
    $destination = 'img/' . $figurineId . '.' . $extension;

    // move_uploaded_file() déplace le fichier
    // du dossier temporaire vers notre dossier img/
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
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">
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
        <a class="btn btn-primary" role="button" href="article.php">RETOUR</a>
    </div>
</body>
</html>