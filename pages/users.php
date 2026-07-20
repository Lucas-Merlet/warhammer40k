<!DOCTYPE html>
<html lang="fr">
<head>
    <?php
    require_once(__DIR__ . '/../common/head.php');
    requireAdmin();   // ← page réservée à l'admin
    ?>
    <title>Gestion des utilisateurs - W40k Shop</title>
</head>
<body class="bg-light">

    <?php require_once(__DIR__ . '/../common/header.php'); ?>

    <div class="container my-5">

        <h1 class="mb-4">Gestion des utilisateurs</h1>

        <?php
        // Message de confirmation après un changement de rôle
        if (isset($_GET['success'])) : ?>
            <div class="alert alert-success">Rôle mis à jour avec succès.</div>
        <?php endif; ?>

        <?php
        // ============================================
        // RÉCUPÉRATION DE TOUS LES UTILISATEURS
        // ============================================
        $usersStatement = $mysqlClient->prepare('SELECT id, login, mail, role, date_inscription FROM users ORDER BY role, login');
        $usersStatement->execute();
        $users = $usersStatement->fetchAll();
        ?>

        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Rôle actuel</th>
                    <th>Changer le rôle</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?= htmlspecialchars($user['login']); ?></td>
                        <td><?= htmlspecialchars($user['mail']); ?></td>
                        <td>
                            <span class="badge bg-secondary"><?= htmlspecialchars($user['role']); ?></span>
                        </td>
                        <td>
                            <!-- ============================================
                                 FORMULAIRE DE CHANGEMENT DE RÔLE
                                 Un formulaire par ligne, chacun avec l'id caché
                                 ============================================ -->
                            <form action="index.php?page=userspost" method="POST" class="d-flex gap-2">
                                <input type="hidden" name="id" value="<?= $user['id']; ?>">
                                <select name="role" class="form-select form-select-sm" style="width:auto;">
                                    <option value="client" <?= ($user['role'] === 'client') ? 'selected' : ''; ?>>Client</option>
                                    <option value="vendeur" <?= ($user['role'] === 'vendeur') ? 'selected' : ''; ?>>Vendeur</option>
                                    <option value="admin" <?= ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <?php require_once(__DIR__ . '/../common/footer.php'); ?>

</body>
</html>