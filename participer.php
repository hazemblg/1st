<?php
$conn = new mysqli("localhost", "root", "", "gestion des evenements");

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

$event_id = $_POST['event_id'];
$username = $_POST['username'];

$sql = "SELECT participants FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$participants = $row['participants'] ?? '';
$participants_array = array_filter(array_map('trim', explode(',', $participants)));

if (!in_array($username, $participants_array)) {
    $participants_array[] = $username;
    $new_participants = implode(',', $participants_array);

    $update_sql = "UPDATE events SET participants = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_participants, $event_id);
    $update_stmt->execute();
    
    echo "Participation enregistrée.";
} else {
    echo "Vous participez déjà à cet événement.";
}

$conn->close();
?>
