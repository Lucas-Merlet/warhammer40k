<?php
try {
    $mysqlClient = new PDO(
        'mysql:host=localhost;dbname=warhammer_shop;charset=utf8',  
        'root',                                                   
        ''                                                        
    );
    $mysqlClient->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $error) {
    
    die('Erreur de connexion à la base de données : ' . $error->getMessage());
}

?>