<?php
$host = "localhost";
$user = "root";       // utilisateur par défaut dans XAMPP
$password = "";       // mot de passe vide par défaut
$dbname = "gestion_evenements";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
