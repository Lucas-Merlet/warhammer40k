<!DOCTYPE html>
<html lang="fr">
<head>
    <?php
    require_once(__DIR__ . '/../common/head.php');
    requireAdmin();   // corbeille réservée à l'admin
    ?>
    <title>Corbeille - W40k Shop</title>
</head>
<body class="bg-light">

    <?php require_once(__DIR__ . '/../common/header.php'); ?>

    <div class="container my-5">

        <h1 class="mb-4">Corbeille</h1>

        <?php if (isset($_GET['restored'])) : ?>
            <div class="alert alert-success">Figurine restaurée avec succès.</div>
        <?php endif; ?>

        <?php
        // On récupère UNIQUEMENT les figurines supprimées
        // deleted_at IS NOT NULL = celles qui ont une date de suppression
        $statement = $mysqlClient->prepare('
            SELECT id, nom, faction, deleted_at
            FROM figurines
            WHERE deleted_at IS NOT NULL
            ORDER BY deleted_at DESC
        ');
        $statement->execute();
        $supprimees = $statement->fetchAll();
        ?>

        <?php if (empty($supprimees)) : ?>
            <p class="text-muted">La corbeille est vide.</p>
        <?php else : ?>
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Faction</th>
                        <th>Supprimée le</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($supprimees as $figurine) : ?>
                        <tr>
                            <td><?= htmlspecialchars($figurine['nom']); ?></td>
                            <td><?= htmlspecialchars($figurine['faction']); ?></td>
                            <td><?= htmlspecialchars($figurine['deleted_at']); ?></td>
                            <td>
                                <form action="index.php?page=restaurepost" method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $figurine['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-success">Restaurer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>

    <?php require_once(__DIR__ . '/../common/footer.php'); ?>

</body>
</html>