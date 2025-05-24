<?php
$conn = new mysqli("localhost", "root", "", "gestion des evenements");

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM events ORDER BY date_event ASC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Événements | EventManager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="Events.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="icon" type="image/jfif" href="télécharger.jfif">

</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="Accueil.html">EventManager</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="Accueil.php">Accueil</a></li>
          <li class="nav-item"><a class="nav-link active" href="events.php">Événements</a></li>
          <li class="nav-item"><a class="nav-link" href="Login_Register.html">Login_Register</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container my-5">
    <h2 class="mb-4">Événements à venir</h2>
    <div class="row g-4">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-md-4">
          <div class="card h-100 shadow">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['titre']) ?></h5>
              <p class="card-text">
                📍 <strong>Lieu :</strong> <?= htmlspecialchars($row['lieu']) ?><br>
                📅 <strong>Date :</strong> <?= htmlspecialchars($row['date_event']) ?><br>
                🧑‍🏫 <strong>Organisateur :</strong> <?= htmlspecialchars($row['organisateur']) ?>
              </p>
              <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#eventDetailsModal" onclick="loadEventDetails(<?= $row['id'] ?>, '<?= addslashes($row['titre']) ?>', '<?= addslashes($row['lieu']) ?>', '<?= $row['date_event'] ?>', '<?= addslashes($row['organisateur']) ?>')">Détails</button>
              <button class="btn btn-outline-primary" onclick="participer(<?= $row['id'] ?>)">Participer</button>
              </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="eventDetailsModalLabel">Détails de l'événement</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="eventDetailsContent">
            <form id="eventDetailsForm">
              <p><strong>Titre :</strong> <span id="eventTitle"></span></p>
              <p><strong>Lieu :</strong> <span id="eventLocation"></span></p>
              <p><strong>Date :</strong> <span id="eventDate"></span></p>
              <p><strong>Organisateur :</strong> <span id="eventOrganizer"></span></p>
              <label for="manualDetails">Détails supplémentaires :</label>
              <?php
                if (isset($_GET['titre'])) {
                  $eventTitle = $_GET['titre'];
              
                  $query = $pdo->prepare("SELECT details FROM events WHERE title = ?");
                  $query->execute([$eventTitle]);
                  $event = $query->fetch(PDO::FETCH_ASSOC);
              
                  if ($event) {
                      $details = $event['details'];
                  } else {
                      $details = "Événement introuvable.";
                  }
              } else {
                  $details = "Aucun événement sélectionné.";
              }
              ?>
              
              <textarea class="form-control" id="manualDetails" rows="4">
                <?php echo htmlspecialchars($details); ?>
              </textarea>
              <button type="button" class="btn btn-primary mt-3" onclick="saveManualDetails()">Enregistrer les détails</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="alert alert-success mt-4" id="successMessage" style="display: none;">
      Participation réussie !
    </div>
  </div>
  <script>
function participer(eventId) {
  const username = prompt("Entrez votre nom d'utilisateur :"); 
  if (!username) return;

  $.post("participer.php", { event_id: eventId, username: username }, function(response) {
    showSuccessMessage();
    location.reload(); 
  });
}
</script>


  <script>
    function loadEventDetails(id, titre, lieu, date_event, organisateur) {
      document.getElementById('eventTitle').innerText = titre;
      document.getElementById('eventLocation').innerText = lieu;
      document.getElementById('eventDate').innerText = date_event;
      document.getElementById('eventOrganizer').innerText = organisateur;

      document.getElementById('manualDetails').value = '';
    }

    function saveManualDetails() {
      var details = document.getElementById('manualDetails').value;
      if (details) {
        alert('Détails enregistrés : ' + details);
      } else {
        alert('Veuillez entrer des détails.');
      }
    }

    function showSuccessMessage() {
      document.getElementById('successMessage').style.display = 'block';
      setTimeout(function() {
        document.getElementById('successMessage').style.display = 'none';
      }, 3000); 
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
