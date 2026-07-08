<?php
// ============================================
// 1. CONNEXION À LA BASE DE DONNÉES
// ============================================
include('connect.php');

// ============================================
// 2. RÉCUPÉRATION ET VALIDATION DE L'ID
// ============================================
$getData = $_GET;

if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo ('Il faut un identifiant de figurine pour la modifier.');
    return;
}

// ============================================
// 3. RÉCUPÉRATION DE LA FIGURINE EN BASE
// ============================================
$retrieveFigurineStatement = $mysqlClient->prepare('SELECT nom, faction, description, etat FROM figurines WHERE id = :id');
$retrieveFigurineStatement->execute([
    'id' => (int)$getData['id'],
]);
$figurine = $retrieveFigurineStatement->fetch(PDO::FETCH_ASSOC);

// ============================================
// 4. VÉRIFICATION QUE LA FIGURINE EXISTE
// ============================================
if (!$figurine) {
    echo ('Figurine introuvable. Vérifiez l\'ID fourni.');
    return;
}

// ============================================
// 5. RÉCUPÉRATION DE LA VALEUR ASSOCIÉE
// Pour pré-remplir les champs prix si elle existe
// ============================================
$retrieveValeurStatement = $mysqlClient->prepare('SELECT prix_vente, cote_marche FROM valeur WHERE figurine_id = :id');
$retrieveValeurStatement->execute([
    'id' => (int)$getData['id'],
]);
$valeur = $retrieveValeurStatement->fetch(PDO::FETCH_ASSOC);
?>
<!-- ============================================
     6. AFFICHAGE DU FORMULAIRE PRÉ-REMPLI
     ============================================ -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edition de figurine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h1 class="h4 mb-0">Mettre à jour : <?php echo htmlspecialchars($figurine['nom']); ?></h1>
                    </div>
                    <div class="card-body">
                        <!-- ============================================
                             FORMULAIRE DE MODIFICATION
                             ============================================
                        -->
                        <form action="editpost.php" method="POST">

                            <input type="hidden" id="id" name="id" value="<?php echo ($getData['id']); ?>">

                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($figurine['nom']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="faction" class="form-label">Faction</label>
                                <input type="text" class="form-control" id="faction" name="faction" aria-describedby="faction-help" value="<?php echo htmlspecialchars($figurine['faction']); ?>">
                                <div id="faction-help" class="form-text">Ultramarines, Death Guard, Orks, Thousand Sons...</div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" rows="4" id="description" name="description"><?php echo htmlspecialchars($figurine['description']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="etat" class="form-label">État</label>
                                <select class="form-select" id="etat" name="etat">
                                    <option value="neuf" <?php echo ($figurine['etat'] == 'neuf') ? 'selected' : ''; ?>>Neuf</option>
                                    <option value="monte" <?php echo ($figurine['etat'] == 'monte') ? 'selected' : ''; ?>>Monté</option>
                                    <option value="peint" <?php echo ($figurine['etat'] == 'peint') ? 'selected' : ''; ?>>Peint</option>
                                    <option value="pro-painted" <?php echo ($figurine['etat'] == 'pro-painted') ? 'selected' : ''; ?>>Peinture-professionnelle</option>
                                </select>
                            </div>

                            <!-- ============================================
                                 CHECKBOX + CHAMPS PRIX OPTIONNELS
                                 ============================================
                            -->
                            <div class="card bg-light border mb-3">
                                <div class="card-body">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="modifierValeur" name="modifierValeur">
                                        <label class="form-check-label" for="modifierValeur">Modifier aussi le prix</label>
                                    </div>
                                    <div id="detailsValeur" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="prix_vente" class="form-label">Prix de vente (€)</label>
                                                <input type="text" class="form-control" id="prix_vente" name="prix_vente" value="<?php echo htmlspecialchars($valeur['prix_vente'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="cote_marche" class="form-label">Cote marché (€)</label>
                                                <input type="text" class="form-control" id="cote_marche" name="cote_marche" value="<?php echo htmlspecialchars($valeur['cote_marche'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Envoyer</button>
                                <a class="btn btn-outline-secondary" role="button" href="article.php">RETOUR</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.getElementById('modifierValeur').addEventListener('change', function() {
        const detailValeur = document.getElementById('detailsValeur');
        detailsValeur.style.display = this.checked ? 'block' : 'none';
    });
    </script>
</body>
</html>