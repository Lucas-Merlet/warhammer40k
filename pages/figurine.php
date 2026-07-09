<?php
// ============================================================
// PAGE D'AFFICHAGE D'UNE FIGURINE COMPLÈTE - FIGURINE.PHP
// ============================================================

// 1. CHARGEMENT DES ÉLÉMENTS COMMUNS EN PREMIER
require_once(__DIR__ . '/../common/head.php');

// 2. VÉRIFICATION ET RÉCUPÉRATION DE L'ID
$figurine = null;
$idValide = false;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $idValide = true;
    $figurineId = $_GET['id'];

    // 3. RÉCUPÉRATION DE LA FIGURINE
    $sqlQuery = '
        SELECT f.id, f.nom, f.faction, f.description, f.etat, f.date_ajout,
               v.prix_vente, v.cote_marche,
               ve.pseudo, ve.note_moyenne
        FROM figurines f
        LEFT JOIN valeur v ON v.figurine_id = f.id
        LEFT JOIN vendeurs ve ON f.vendeur_id = ve.id
        WHERE f.id = :id';

    $statement = $mysqlClient->prepare($sqlQuery);
    $statement->bindParam(':id', $figurineId, PDO::PARAM_INT);
    $statement->execute();
    $figurine = $statement->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/warhammer40k/public/">
    <title>W40k Shop | <?php echo $figurine ? htmlspecialchars($figurine['nom']) : 'Figurine'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

    <div class="container text-center d-flex flex-wrap justify-content-center">

    <?php
    // 4. AFFICHAGE : trois cas possibles
    if (!$idValide) {

        echo "<div class='card m-5 p-5 text-dark'><p>Identifiant de figurine manquant ou invalide.</p></div>";

    } elseif (!$figurine) {

        echo "<div class='card m-5 p-5 text-dark'><p>Figurine non trouvée.</p></div>";

    } else {

        echo "<div class='card col-9 m-5 p-3 text-dark'>";

        // ============================================
        // GESTION DE L'IMAGE : règle des deux mondes
        // - chemin DISQUE pour file_exists
        // - chemin NAVIGATEUR pour le src (via <base>)
        // ============================================
        $imageDisque = __DIR__ . "/../public/assets/img/" . $figurine['id'] . ".webp";
        $imageWeb = "assets/img/" . $figurine['id'] . ".webp";

        if (file_exists($imageDisque)) {
            $image = $imageWeb;
        } else {
            $image = "https://picsum.photos/800/300";
        }

        echo "<img src=\"$image\" class=\"img-fluid rounded-top mb-2\" alt=\"" . htmlspecialchars($figurine['nom']) . "\">";

        echo "<h1>" . htmlspecialchars($figurine['nom']) . "</h1>";

        echo "<p>
                <span class='badge' style='background-color: " . couleurFaction($figurine['faction']) . ";'>" . htmlspecialchars($figurine['faction']) . "</span>
                <span class='badge bg-secondary'>" . htmlspecialchars(libelleEtat($figurine['etat'])) . "</span>
              </p>";

        echo "<p>Ajoutée le : {$figurine['date_ajout']}</p>";

        // PRIX : réservés aux connectés (clients + admins)
        if (isLoggedIn()) {
            echo $figurine['prix_vente'] ? "<strong style=\"color:#FF0000\">prix : {$figurine['prix_vente']} €</strong>" : "<em>prix non renseigné</em>";
            echo $figurine['cote_marche'] ? "<p>cote marché : {$figurine['cote_marche']} €</p>" : "";
        } else {
            echo "<p class='text-muted'><em>Prix réservé aux clients — <a href='index.php?page=login'>connectez-vous</a></em></p>";
        }

        echo $figurine['pseudo'] ? "<p>vendu par : <strong>" . htmlspecialchars($figurine['pseudo']) . "</strong> (note : {$figurine['note_moyenne']}/5)</p>" : "";

        echo "<p>" . htmlspecialchars($figurine['description']) . "</p>";

        // BOUTON DE PARTAGE — À L'INTÉRIEUR de la card
        echo "<div class='mt-3'>
                <button id='shareButton' class='btn btn-outline-primary'
                        data-title='" . htmlspecialchars($figurine['nom']) . " - W40k Shop'
                        data-url='" . createFigurineUrl($figurine['id'], $figurine['nom']) . "'>
                    Partager cette figurine
                </button>
              </div>";

        echo "</div>";
    }
?>

    <div class="col-12 mb-5">
        <a class="btn btn-primary" role="button" href="index.php?page=article">RETOUR</a>
    </div>

    </div>

    <script src="assets/js/share.js"></script>
</body>
</html>