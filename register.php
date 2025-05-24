<?php
session_start();

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "gestion des evenements");

if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation basique
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: Login_Register.html?error=Tous+les+champs+sont+obligatoires");
        exit();
    }

    // Vérification email existant
    $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();
    
    if ($check_email->num_rows > 0) {
        header("Location: Login_Register.html?error=Email+déjà+utilisé");
        exit();
    }

    // Hachage du mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insertion
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        // Redirection avec message de succès
        header("Location: Login_Register.html?success=Vous+pouvez+vous+connecter+maintenant");
        exit();
    } else {
        header("Location: Login_Register.html?error=Erreur+d'inscription");
        exit();
    }
}
$conn->close();
?>