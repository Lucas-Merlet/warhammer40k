<?php
// ============================================================
// PAGE D'AFFICHAGE D'UNE FIGURINE COMPLÈTE - FIGURINE.PHP
// ============================================================
// Cette page affiche le détail complet d'une figurine
// en récupérant son ID depuis l'URL (paramètre GET).
// ============================================================

// Inclusion de la connexion à la base de données
include('connect.php');
include('functions.php');


// ============================================================
// VÉRIFICATION ET RÉCUPÉRATION DE L'ID DE LA FIGURINE
// ============================================================

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $figurineId = $_GET['id'];

    // ============================================================
    // RÉCUPÉRATION DE LA FIGURINE DEPUIS LA BASE DE DONNÉES
    // ============================================================

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

    ?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>W40k Shop | <?php echo $figurine ? $figurine['nom'] : 'Figurine'; ?></title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-dark text-light">

        <div class="container text-center d-flex flex-wrap justify-content-center">

        <?php
        // ============================================================
        // AFFICHAGE DE LA FIGURINE
        // ============================================================

        if ($figurine) {

            echo "<div class='card col-9 m-5 p-3 text-dark'>";

            // ============================================================
            // GESTION DE L'IMAGE DE LA FIGURINE
            // ============================================================

            $imagePath = "img/" . $figurine['id'] . ".webp";

            if (file_exists($imagePath)) {
                $image = $imagePath;
            } else {
                $image = "https://picsum.photos/800/300";
            }
            echo "<img src=\"$image\" class=\"img-fluid rounded-top mb-2\" alt=\"" . htmlspecialchars($figurine['nom']) . "\">";
            echo "<h1>" . htmlspecialchars($figurine['nom']) . "</h1>";
            echo "<p>
                    <span class='badge bg-primary'>" . htmlspecialchars($figurine['faction']) . "</span>
                    <span class='badge bg-secondary'>" . htmlspecialchars(libelleEtat($figurine['etat'])) . "</span>
                  </p>";
            echo "<p>Ajoutée le : {$figurine['date_ajout']}</p>";
            echo $figurine['prix_vente'] ? "<strong style=\"color:#FF0000\">prix : {$figurine['prix_vente']} €</strong>" : "<em>prix non renseigné</em>";
            echo $figurine['cote_marche'] ? "<p>cote marché : {$figurine['cote_marche']} €</p>" : "";
            echo $figurine['pseudo'] ? "<p>vendu par : <strong>" . htmlspecialchars($figurine['pseudo']) . "</strong> (note : {$figurine['note_moyenne']}/5)</p>" : "";
            echo "<p>" . htmlspecialchars($figurine['description']) . "</p>";
            echo "</div>";

        } else {
            echo "<p>Figurine non trouvée.</p>";
        }

    } else {
        echo "<div class='card m-5 p-5'><p>Identifiant de figurine manquant ou invalide.</p></div>";
    }

        ?>
        <div class="col-12 mb-5">
            <a class="btn btn-primary" role="button" href="article.php">RETOUR</a>
        </div>

        </div>

    </body>

    </html>