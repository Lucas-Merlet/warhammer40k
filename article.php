<?php

// 1. CONNEXION

include('connect.php');
include('functions.php');


// 2. RÉCUPÉRATION DES FIGURINES + VALEUR

$sqlQuery = '
    SELECT f.id, f.nom, f.faction, f.description, f.etat, f.date_ajout, v.prix_vente, v.cote_marche
    FROM figurines f
    LEFT JOIN valeur v ON v.figurine_id = f.id
    ORDER BY f.date_ajout DESC;';
$figurinesStatement = $mysqlClient->prepare($sqlQuery);
$figurinesStatement->execute();
$figurines = $figurinesStatement->fetchAll();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Warhammer 40k</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
    <div class="container my-5">

        <!-- ============================================
             EN-TÊTE AVEC BOUTON AJOUTER
             ============================================ -->

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Blog Warhammer 40k</h1>
            <a href="add.php" class="btn btn-success">+ Ajouter une figurine</a>
        </div>
        <hr>

        <!-- ============================================
             LISTE DES FIGURINES
             ============================================ -->

        <div class="row">
            <?php foreach ($figurines as $figurine) : ?>

                <div class="col-md-4">
                    <div class="card mb-4 text-dark">

                        <?php

                        // GESTION DE L'IMAGE DE LA FIGURINE

                        $imageWebp = "img/" . $figurine['id'] . ".webp";

                        if (file_exists($imageWebp)) {
                            $image = $imageWebp;
                        } else {
                            $image = "https://picsum.photos/400/250";
                        }
                        ?>
                        <a href="figurine.php?id=<?= $figurine['id']; ?>">
                            <img src="<?= $image; ?>"
                                 class="card-img-top"
                                 loading="lazy"
                                 style="height: 200px; object-fit: cover;"
                                 alt="<?= htmlspecialchars($figurine['nom']); ?>">
                        </a>

                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="figurine.php?id=<?= $figurine['id']; ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($figurine['nom']); ?>
                                </a>
                            </h5>

                            <p class="card-text">
                                <span class="badge bg-primary"><?= htmlspecialchars($figurine['faction']); ?></span>
                                <span class="badge bg-secondary"><?= htmlspecialchars(libelleEtat($figurine['etat'])); ?></span>
                            </p>

                            <p class="card-text">
                                <strong style="color:#FF0000">
                                    <?= htmlspecialchars($figurine['prix_vente'] ?? 'N/C'); ?> €
                                </strong>
                                (cote : <?= htmlspecialchars($figurine['cote_marche'] ?? 'N/C'); ?> €)
                            </p>

                            <p class="card-text">
                                <?= htmlspecialchars(truncateString($figurine['description'])); ?>
                            </p>

                            <p class="card-text">
                                <small class="text-muted">Ajouté le <?= htmlspecialchars($figurine['date_ajout']); ?></small>
                            </p>

                            <!-- ============================================
                                 BOUTONS D'ACTION AVEC L'ID DANS L'URL
                                 ============================================ -->
                                 
                            <a href="edit.php?id=<?= $figurine['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="delete.php?id=<?= $figurine['id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

    </div>
</body>
</html>