<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once(__DIR__ . '/../common/head.php'); ?>
    <title>Inscription - W40k Shop</title>
</head>
<body class="bg-light">
    <?php require_once(__DIR__ . '/../common/header.php'); ?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h1 class="h4 mb-0">Devenir client</h1>
                    </div>
                    <div class="card-body">
                        <?php
                        // AFFICHAGE DES MESSAGES D'ERREUR
                        if (isset($_GET['error'])) :

                            $messages = [
                                'champs'    => 'Tous les champs sont obligatoires.',
                                'login'     => 'Cet identifiant est déjà pris, choisissez-en un autre.',
                                'mail'      => 'L\'adresse email n\'est pas valide.',
                                'mail_pris' => 'Cette adresse email est déjà utilisée.',
                                'mdp_court' => 'Le mot de passe doit faire au moins 8 caractères.',
                                'mdp_diff'  => 'Les deux mots de passe ne correspondent pas.',
                            ];

                            $message = $messages[$_GET['error']] ?? 'Une erreur est survenue.';
                            ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($message); ?></div>
                        <?php endif; ?>
                        <p class="text-muted small">
                            Créez votre compte client pour voir toutes nos figurines et leurs prix.
                        </p>
                        <form action="index.php?page=registerpost" method="POST">
                            <div class="mb-3">
                                <label for="login" class="form-label">Identifiant</label>
                                <input type="text" class="form-control" id="login" name="login" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password"
                                       minlength="8" required aria-describedby="mdp-help">
                                <div id="mdp-help" class="form-text">8 caractères minimum.</div>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">Confirmez le mot de passe</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>
                            <div class="mb-3">
                                <label for="mail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="mail" name="mail" required
                                placeholder="vous@exemple.com" aria-describedby="mail-help">
                                <div id="mail-help" class="form-text">L'email servira à vous connecter.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Créer mon compte</button>
                        </form>
                        <hr>
                        <p class="text-muted small text-center mb-0">
                            Déjà client ? <a href="index.php?page=login">Connectez-vous</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once(__DIR__ . '/../common/footer.php'); ?>
</body>
</html>