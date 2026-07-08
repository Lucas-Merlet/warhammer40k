<?php

// 1. CONNEXION À LA BASE DE DONNÉES

require_once(__DIR__ . '/connect.php');


// 2. RÉCUPÉRATION ET VALIDATION DE L'ID

$getData = $_GET;

if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo ('Il faut un identifiant pour supprimer une figurine.');
    return;
}


// 3. RÉCUPÉRATION DE LA FIGURINE

$retrieveFigurineStatement = $mysqlClient->prepare('SELECT nom, faction FROM figurines WHERE id = :id');
$retrieveFigurineStatement->execute([
    'id' => (int)$getData['id'],
]);
$figurine = $retrieveFigurineStatement->fetch(PDO::FETCH_ASSOC);

if (!$figurine) {
    echo ('Figurine introuvable.');
    return;
}
?>

<!-- ============================================
     4. AFFICHAGE DE LA PAGE DE CONFIRMATION
     ============================================ -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer une figurine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card border-danger shadow">
                    <div class="card-header bg-danger text-white">
                        <h1 class="h4 mb-0">Confirmer la suppression</h1>
                    </div>
                    <div class="card-body text-center">

                        <p>Vous êtes sur le point de supprimer :</p>

                        <p class="fs-5 fw-bold">
                            <?php echo htmlspecialchars($figurine['nom']); ?>
                            <span class="badge bg-secondary"><?php echo htmlspecialchars($figurine['faction']); ?></span>
                        </p>

                        <div class="alert alert-warning" role="alert">
                            Cette action est irréversible. La figurine et son prix seront définitivement supprimés.
                        </div>
                        <form action="deletepost.php" method="POST">
                            <input type="hidden" id="id" name="id" value="<?php echo $getData['id']; ?>">
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="submit" class="btn btn-danger">Oui, supprimer</button>
                                <a class="btn btn-outline-secondary" role="button" href="article.php">Non, RETOUR</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>