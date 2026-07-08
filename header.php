<!--
    En-tête du site - header.php
-->
<div class="container d-flex flex-row justify-content-between align-items-center my-4">

    <div class="col-4">
        <a href="article.php" class="text-decoration-none">
            <h2>⚔ W40k Shop</h2>
        </a>
        <p class="text-muted small">
            <?php if (isLoggedIn()) : ?>
                Connecté : <?php echo htmlspecialchars($_SESSION['LOGGED_USER']['pseudo']); ?>
                (<?php echo htmlspecialchars($_SESSION['LOGGED_USER']['role']); ?>) |
            <?php endif; ?>
            Édition du <?php echo date("d/m/Y"); ?>
        </p>
    </div>

    <div class="col-8 text-end">
        <ul class="list-inline">
            <li class="list-inline-item"><a href="article.php">Accueil</a></li>

            <?php if (isAdmin()) : ?>
                <li class="list-inline-item"><a href="add.php">Ajouter</a></li>
                <li class="list-inline-item"><a href="logs.php">Logs</a></li>
            <?php endif; ?>

            <?php if (!isLoggedIn()) : ?>
                <li class="list-inline-item"><a href="login.php">Connexion</a></li>
            <?php endif; ?>
        </ul>
    </div>

</div>
<hr>