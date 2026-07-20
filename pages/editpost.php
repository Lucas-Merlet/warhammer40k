<?php
// PAGE DE TRAITEMENT DE MODIFICATION - EDITPOST.PHP
require_once(__DIR__ . '/../common/head.php');
requireGestion();

class JsonLogger
{
    private $logFile;

    public function __construct($filename = null)
    {
        // Le fichier de logs vit dans public/
        // Chemin DISQUE depuis pages/
        $this->logFile = $filename ?? __DIR__ . '/../public/figurines_logs.json';

        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, '[]');
        }
    }

    public function log($action, $data = [])
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'data' => $data,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];

        $logs = json_decode(file_get_contents($this->logFile), true) ?? [];
        $logs[] = $logEntry;
        file_put_contents($this->logFile, json_encode($logs, JSON_PRETTY_PRINT));
    }
}

$logger = new JsonLogger();

$logger->log('update_figurine_start', [
    'post_data' => $_POST
]);

$postData = $_POST;

if (
    !isset($postData['id'])
    || !is_numeric($postData['id'])
    || empty($postData['nom'])
    || empty($postData['faction'])
    || empty($postData['description'])
    || empty($postData['etat'])
    || trim(strip_tags($postData['nom'])) === ''
    || trim(strip_tags($postData['faction'])) === ''
    || trim(strip_tags($postData['description'])) === ''
) {
    $logger->log('update_figurine_error', [
        'error' => 'Validation failed',
        'post_data' => $postData
    ]);

    echo 'Il manque des informations pour permettre l\'édition du formulaire.';
    return;
}

$id = (int)$postData['id'];
$nom = trim(strip_tags($postData['nom']));
$faction = trim(strip_tags($postData['faction']));
$description = trim(strip_tags($postData['description']));
$etat = trim(strip_tags($postData['etat']));

try {

    $logger->log('update_figurine_attempt', [
        'id' => $id,
        'nom' => $nom,
        'description_length' => strlen($description)
    ]);

    $insertcontenuStatement = $mysqlClient->prepare('UPDATE figurines SET nom = :nom, faction = :faction, description = :description, etat = :etat WHERE id = :id');

    $insertcontenuStatement->execute([
        'nom' => $nom,
        'faction' => $faction,
        'description' => $description,
        'etat' => $etat,
        'id' => $id,
    ]);

    $logger->log('update_figurine_success', [
        'id' => $id,
        'rows_affected' => $insertcontenuStatement->rowCount()
    ]);

    if (isset($postData['modifierValeur']) && $postData['modifierValeur'] === 'on') {

        $logger->log('update_valeur_attempt', [
            'figurine_id' => $id
        ]);

        if (
            empty($postData['prix_vente'])
            || empty($postData['cote_marche'])
        ) {
            $logger->log('update_valeur_error', [
                'error' => 'Missing valeur fields',
                'figurine_id' => $id
            ]);
            echo 'Tous les champs de la valeur doivent être remplis.';
            return;
        }

        $prixVente = trim(strip_tags($postData['prix_vente']));
        $coteMarche = trim(strip_tags($postData['cote_marche']));

        $sqlQuery = 'SELECT id FROM valeur WHERE figurine_id = :id';
        $valeurStatement = $mysqlClient->prepare($sqlQuery);
        $valeurStatement->execute([
            'id' => $id
        ]);
        $valeur = $valeurStatement->fetch();

        if ($valeur && $valeur['id']) {

            $insertValeur = $mysqlClient->prepare('
                UPDATE valeur
                SET prix_vente = :prix_vente,
                    cote_marche = :cote_marche,
                    date_estimation = :date_estimation
                WHERE id = :id_valeur
            ');

            $insertValeur->execute([
                'prix_vente' => $prixVente,
                'cote_marche' => $coteMarche,
                'date_estimation' => date('Y-m-d'),
                'id_valeur' => (int)$valeur['id']
            ]);

            $logger->log('update_valeur_success', [
                'valeur_id' => $valeur['id'],
                'rows_affected' => $insertValeur->rowCount()
            ]);

        } else {

            $insertValeur = $mysqlClient->prepare('
                INSERT INTO valeur (figurine_id, prix_vente, cote_marche, date_estimation)
                VALUES (:figurine_id, :prix_vente, :cote_marche, :date_estimation)
            ');

            $insertValeur->execute([
                'figurine_id' => $id,
                'prix_vente' => $prixVente,
                'cote_marche' => $coteMarche,
                'date_estimation' => date('Y-m-d'),
            ]);

            $logger->log('insert_valeur_success', [
                'figurine_id' => $id
            ]);
        }
    }

} catch (Exception $e) {

    $logger->log('update_error', [
        'error' => $e->getMessage(),
        'id' => $id
    ]);

    echo "Une erreur est survenue lors de la modification.";
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>figurine <?php echo($id); ?> modifiée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <h1>figurine <?php echo($id); ?> modifiée avec succès !</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo($nom); ?></h5>
                <p class="card-text"><b>Faction : <?php echo $faction; ?> — État : <?php echo $etat; ?></b></p>
                <p class="card-text"><?php echo $description; ?></p>
            </div>
        </div>
    </div>
    <a class="btn btn-primary" role="button" href="index.php?page=article">RETOUR</a>
</body>
</html>