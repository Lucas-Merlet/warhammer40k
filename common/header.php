<!--
    En-tête du site - header.php
-->
<div class="container d-flex flex-row justify-content-between align-items-center my-4">
    <div class="col-4">
        <a href="index.php?page=article" class="text-decoration-none">
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
            <li class="list-inline-item"><a href="index.php?page=article">Accueil</a></li>
            <?php if (isAdmin()) : ?>
                <li class="list-inline-item"><a href="index.php?page=add">Ajouter</a></li>
                <li class="list-inline-item"><a href="index.php?page=logs">Logs</a></li>
            <?php endif; ?>
            <?php if (!isLoggedIn()) : ?>
                <li class="list-inline-item"><a href="index.php?page=login">Connexion</a></li>
                <li class="list-inline-item"><a href="index.php?page=register">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<hr>

<?php
// ============================================
// MODALE DE BIENVENUE
// Affichée UNE seule fois après la connexion
// ============================================
if (isLoggedIn() && !isset($_SESSION['MODAL_SHOWN'])) :
    $_SESSION['MODAL_SHOWN'] = true;
    ?>
    <div class="modal fade" id="welcomeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Bienvenue <?php echo htmlspecialchars($_SESSION['LOGGED_USER']['pseudo']); ?> !</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    Heureux de vous revoir sur W40k Shop.<br>
                    Vous avez accès à l'intégralité de la collection et aux prix.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const myModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
            myModal.show();
        });
    </script>
<?php endif; ?>