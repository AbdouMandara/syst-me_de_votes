<?php
$hostname="localhost";
$username="root";
$password="";
$db_name="système_de_votes";

try {
    $connexion = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $erreur) {
    die('Erreur de connexion à la base de données:'. $erreur->getMessage());
}
    
return $connexion;

?>