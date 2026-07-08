<?php

require_once(__DIR__ . '/connect.php');
requireAdmin();

?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de figurine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h1 class="h4 mb-0">Ajouter une figurine</h1>
                    </div>

                    <div class="card-body">
                        
                        <form action="addpost.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom de la figurine</label>
                                <input type="text" class="form-control" id="nom" name="nom">
                            </div>
                            <div class="mb-3">
                                <label for="faction" class="form-label">Faction</label>
                                <input type="text" class="form-control" id="faction" name="faction" aria-describedby="faction-help">
                                <div id="faction-help" class="form-text">Ultramarines, Death Guard, Orks, Thousand Sons...</div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description de la figurine</label>
                                <textarea class="form-control" rows="4" placeholder="État de la peinture, socle, détails..." id="description" name="description"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="etat" class="form-label">État</label>
                                <select class="form-select" id="etat" name="etat">
                                    <option value="neuf">Neuf</option>
                                    <option value="monte">Monté</option>
                                    <option value="peint">Peint</option>
                                    <option value="pro-painted">Peinture-professionnelle</option>
                                </select>

                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="prix_vente" class="form-label">Prix de vente (€)</label>
                                    <input type="text" class="form-control" id="prix_vente" name="prix_vente">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="cote_marche" class="form-label">Cote marché (€)</label>
                                    <input type="text" class="form-control" id="cote_marche" name="cote_marche">
                                </div>

                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label">Photo de la figurine</label>
                                <input type="file" class="form-control" id="image" name="image" accept=".webp,.jpg,.jpeg,.png">
                                <div class="form-text">Format recommandé : WebP (éco-conception). Max 2 Mo.</div>
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
</body>
</html>