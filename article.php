<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once(__DIR__ . '/head.php'); ?>
    <title>W40k Shop - Figurines Warhammer 40,000</title>
</head>
<body class="bg-light">

    <?php require_once(__DIR__ . '/header.php'); ?>

    <!-- ============================================
         BANDEAU VISITEUR : invitation à s'inscrire
         ============================================ -->
         
    <?php if (!isLoggedIn()) : ?>
        <div class="container">
            <div class="alert alert-info text-center">
                <strong>Vous ne voyez qu'un aperçu de notre collection !</strong><br>
                Inscrivez-vous pour devenir client, découvrir toutes nos figurines et leurs prix.
                <br>
                <a href="login.php" class="btn btn-primary btn-sm mt-2">Se connecter</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">

        <?php

        // RÉCUPÉRATION DES FIGURINES

        $sqlQuery = '
            SELECT f.id, f.nom, f.faction, f.description, f.etat, f.date_ajout, v.prix_vente, v.cote_marche
            FROM figurines f
            LEFT JOIN valeur v ON v.figurine_id = f.id
            ORDER BY f.date_ajout DESC';

        if (!isLoggedIn()) {
            $sqlQuery .= ' LIMIT 6';
        }

        $figurinesStatement = $mysqlClient->prepare($sqlQuery);
        $figurinesStatement->execute();
        $figurines = $figurinesStatement->fetchAll();
        ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Nos figurines</h1>
            <?php if (isAdmin()) : ?>
                <a href="add.php" class="btn btn-success">+ Ajouter une figurine</a>
            <?php endif; ?>
        </div>

        <p class="text-muted mb-4"><?= count($figurines); ?> figurine(s) affichée(s)</p>

        <div class="row">
            <?php foreach ($figurines as $figurine) : ?>

                <div class="col-md-4">
                    <div class="card mb-4">

                        <?php

                        $imageWebp = "img/" . $figurine['id'] . ".webp";
                        if (file_exists($imageWebp)) {
                            $image = $imageWebp;
                        } else {
                            $image = "https://picsum.photos/400/250";
                        }
                        ?>

                        <a href="<?= createFigurineUrl($figurine['id'], $figurine['nom']); ?>">
                            <img src="<?= $image; ?>"
                                 class="card-img-top"
                                 loading="lazy"
                                 style="height: 200px; object-fit: cover;"
                                 alt="<?= htmlspecialchars($figurine['nom']); ?>">
                        </a>

                        <div class="card-body">

                            <h5 class="card-title">
                                <a href="<?= createFigurineUrl($figurine['id'], $figurine['nom']); ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($figurine['nom']); ?>
                                </a>
                            </h5>

                            <p class="card-text">
                                <span class="badge" style="background-color: <?= couleurFaction($figurine['faction']); ?>;">
                                    <?= htmlspecialchars($figurine['faction']); ?>
                                </span>
                                <span class="badge bg-secondary"><?= htmlspecialchars(libelleEtat($figurine['etat'])); ?></span>
                            </p>

                            <!-- ============================================
                                 PRIX : clients et admins seulement
                                 ============================================ -->

                            <?php if (isLoggedIn()) : ?>
                                <p class="card-text">
                                    <strong style="color:#FF0000">
                                        <?= htmlspecialchars($figurine['prix_vente'] ?? 'N/C'); ?> €
                                    </strong>
                                    (cote : <?= htmlspecialchars($figurine['cote_marche'] ?? 'N/C'); ?> €)
                                </p>
                            <?php else : ?>
                                <p class="card-text text-muted">
                                    <em>Prix réservé aux clients</em>
                                </p>
                            <?php endif; ?>

                            <p class="card-text">
                                <?= htmlspecialchars(truncateString($figurine['description'])); ?>
                            </p>

                            <p class="card-text">
                                <small class="text-muted">Ajouté le <?= htmlspecialchars($figurine['date_ajout']); ?></small>
                            </p>

                            <!-- ============================================
                                 BOUTONS D'ADMINISTRATION : admins seulement
                                 ============================================ -->
                                 
                            <?php if (isAdmin()) : ?>
                                <a href="edit.php?id=<?= $figurine['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                                <a href="delete.php?id=<?= $figurine['id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

    </div>

    <?php require_once(__DIR__ . '/footer.php'); ?>

</body>
</html>