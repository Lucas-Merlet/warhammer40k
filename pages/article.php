<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once(__DIR__ . '/../common/head.php'); ?>
    <title>W40k Shop - Figurines Warhammer 40,000</title>
</head>
<body class="bg-light">

    <?php require_once(__DIR__ . '/../common/header.php'); ?>

    <!-- ============================================
         BANDEAU VISITEUR
         ============================================ -->
    <?php if (!isLoggedIn()) : ?>
        <div class="container">
            <div class="alert alert-info text-center">
                <strong>Vous ne voyez qu'un aperçu de notre collection !</strong><br>
                Inscrivez-vous pour devenir client, découvrir toutes nos figurines et leurs prix.
                <br>
                <a href="index.php?page=register" class="btn btn-success btn-sm mt-2">Devenir client</a>
                <a href="index.php?page=login" class="btn btn-primary btn-sm mt-2">Se connecter</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">

        <?php
        // ============================================
        // 1. RÉCUPÉRATION DE LA LISTE DES FACTIONS
        // ============================================
        $factionsStatement = $mysqlClient->prepare('SELECT DISTINCT faction FROM figurines ORDER BY faction');
        $factionsStatement->execute();
        $factions = $factionsStatement->fetchAll(PDO::FETCH_COLUMN);

        // ============================================
        // 2. LECTURE ET VALIDATION DU FILTRE FACTION
        // ============================================
        $factionFiltre = isset($_GET['faction']) ? $_GET['faction'] : null;

        if ($factionFiltre !== null && !in_array($factionFiltre, $factions)) {
            $factionFiltre = null;
        }

        //=============================================
        // 2bis. LECTURE RECHERCHE
        //=============================================
        
        $recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : null;

        if ($recherche === '') {
            $recherche = null;
        }

        // ============================================
        // 3. RÉGLAGES DE LA PAGINATION
        // ============================================
        $parPage = 9;  // figurines par page

        // Page demandée (défaut : 1)
        $pageActuelle = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
        if ($pageActuelle < 1) {
            $pageActuelle = 1;
        }

        // ============================================
        // 4. COMPTER LE TOTAL (avec le filtre éventuel)
        // COUNT(*) compte sans récupérer les lignes
        // ============================================
        // Dans le COUNT (bloc 4)
        $sqlCount = 'SELECT COUNT(*) FROM figurines f WHERE f.deleted_at IS NULL';
        $paramsCount = [];

        if ($factionFiltre !== null) {
            $sqlCount .= ' AND f.faction = :faction';   // AND, pas WHERE !
            $paramsCount['faction'] = $factionFiltre;
        }

        if ($recherche !== null) {
            $sqlCount .= 'AND f.nom LIKE :recherche';
            $paramsCount['recherche'] = '%' . $recherche . '%';
        }

        $countStatement = $mysqlClient->prepare($sqlCount);
        $countStatement->execute($paramsCount);
        $totalFigurines = $countStatement->fetchColumn();

        // ceil arrondit au supérieur : 20 / 9 = 2.22 → 3 pages
        $totalPages = ceil($totalFigurines / $parPage);

        // ============================================
        // 5. CONSTRUCTION DE LA REQUÊTE FIGURINES
        // ============================================
        $sqlQuery = '
            SELECT f.id, f.nom, f.faction, f.description, f.etat, f.date_ajout, v.prix_vente, v.cote_marche
            FROM figurines f
            LEFT JOIN valeur v ON v.figurine_id = f.id
            WHERE f.deleted_at IS NULL';

        $params = [];

        if ($factionFiltre !== null) {
            $sqlQuery .= ' AND f.faction = :faction';
            $params['faction'] = $factionFiltre;
        }

        if ($recherche !== null) {
            $sqlQuery .= ' AND f.nom LIKE :recherche';
            $params['recherche'] = '%' . $recherche . '%';
        }

        $sqlQuery .= ' ORDER BY f.date_ajout DESC';

        if (!isLoggedIn()) {
            // VISITEUR : 6 max, pas de pagination
            $sqlQuery .= ' LIMIT 6';
        } else {
            // CONNECTÉ : pagination
            // $offset et $parPage sont des entiers maîtrisés (pas de risque d'injection)
            $offset = ($pageActuelle - 1) * $parPage;
            $sqlQuery .= ' LIMIT ' . $parPage . ' OFFSET ' . $offset;
        }

        $figurinesStatement = $mysqlClient->prepare($sqlQuery);
        $figurinesStatement->execute($params);
        $figurines = $figurinesStatement->fetchAll();
        ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Nos figurines</h1>
            <?php if (peutGerer()) : ?>
                <a href="index.php?page=add" class="btn btn-success">+ Ajouter une figurine</a>
            <?php endif; ?>
        </div>

        <!-- ============================================
            BARRE DE RECHERCHE
            ============================================ -->
        <form action="index.php" method="GET" class="mb-4">
            <input type="hidden" name="page" value="article">
            <div class="input-group">
                <input type="text" name="recherche" class="form-control"
                    placeholder="Rechercher une figurine par nom..."
                    value="<?= htmlspecialchars($recherche ?? ''); ?>">
                <button type="submit" class="btn btn-primary">Rechercher</button>
                <?php if ($recherche !== null) : ?>
                    <a href="index.php?page=article" class="btn btn-outline-secondary">Effacer</a>
                <?php endif; ?>
            </div>
        </form>

        <!-- ============================================
             FILTRES PAR FACTION (tous les profils)
             ============================================ -->
        <div class="mb-4">
            <a href="index.php?page=article"
               class="btn btn-sm mb-1 <?= ($factionFiltre === null) ? 'btn-dark' : 'btn-outline-dark'; ?>">
                Toutes
            </a>
            <?php foreach ($factions as $faction) : ?>
                <a href="index.php?page=article&faction=<?= urlencode($faction); ?>"
                   class="btn btn-sm mb-1 text-white"
                   style="background-color: <?= couleurFaction($faction); ?>;
                          <?= ($factionFiltre === $faction) ? 'outline: 3px solid #000;' : 'opacity: 0.75;'; ?>">
                    <?= htmlspecialchars($faction); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Indicateur filtre / compteur -->
        <?php if ($factionFiltre !== null) : ?>
            <p class="text-muted">
                Filtre actif : <strong><?= htmlspecialchars($factionFiltre); ?></strong>
                — <?= $totalFigurines; ?> figurine(s)
                | <a href="index.php?page=article">réinitialiser</a>
            </p>
        <?php else : ?>
            <p class="text-muted mb-4"><?= $totalFigurines; ?> figurine(s) au catalogue</p>
        <?php endif; ?>

        <!-- ============================================
             LISTE DES FIGURINES
             ============================================ -->
        <div class="row">
            <?php foreach ($figurines as $figurine) : ?>

                <div class="col-md-4">
                    <div class="card mb-4">

                        <?php
                        $imageDisque = __DIR__ . "/../public/assets/img/" . $figurine['id'] . ".webp";
                        $imageWeb = "assets/img/" . $figurine['id'] . ".webp";

                        if (file_exists($imageDisque)) {
                            $image = $imageWeb;
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

                            <?php if (peutGerer()) : ?>
                                <a href="index.php?page=edit&id=<?= $figurine['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <?php endif; ?>

                            <?php if (isAdmin()) : ?>
                                <a href="index.php?page=delete&id=<?= $figurine['id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

        <!-- ============================================
             NAVIGATION PAGINATION (connectés seulement)
             ============================================ -->
        <?php if (isLoggedIn() && $totalPages > 1) : ?>
            <nav aria-label="Navigation des pages">
                <ul class="pagination justify-content-center">

                    <?php
                    // On garde le filtre faction actif dans les liens de page
                    $lienBase = 'index.php?page=article';
                    if ($factionFiltre !== null) {
                        $lienBase .= '&faction=' . urlencode($factionFiltre);
                    }
                    ?>

                    <li class="page-item <?= ($pageActuelle <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?= $lienBase; ?>&p=<?= $pageActuelle - 1; ?>">Précédent</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?= ($i === $pageActuelle) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?= $lienBase; ?>&p=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($pageActuelle >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?= $lienBase; ?>&p=<?= $pageActuelle + 1; ?>">Suivant</a>
                    </li>

                </ul>
            </nav>
        <?php endif; ?>

    </div>

    <?php require_once(__DIR__ . '/../common/footer.php'); ?>

</body>
</html>