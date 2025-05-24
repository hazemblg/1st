<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "gestion des evenements"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if ($user['role'] == 'admin') {
        header("Location: admin.php"); 
    } else {
        header("Location: Accueil.php"); 
    }
} else {
    echo "Utilisateur ou mot de passe incorrect.";
}

$conn->close();
?>
