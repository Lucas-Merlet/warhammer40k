<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once(__DIR__ . '/../common/head.php'); ?>
    <title>Connexion - W40k Shop</title>
</head>
<body class="bg-light">
    <?php require_once(__DIR__ . '/../common/header.php'); ?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h1 class="h4 mb-0">Connexion</h1>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_GET['error'])) : ?>
                            <div class="alert alert-danger">
                                Identifiants incorrects. Réessayez.
                            </div>
                        <?php endif; ?>
                        <form action="index.php?page=loginpost" method="POST">
                            <div class="mb-3">
                                <label for="login" class="form-label">Identifiant</label>
                                <input type="text" class="form-control" id="login" name="login" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                        </form>
                        <hr>
                        <p class="text-muted small text-center mb-0">
                            Pas encore de compte ? <a href="index.php?page=register">Inscrivez-vous</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once(__DIR__ . '/../common/footer.php'); ?>
</body>
</html>