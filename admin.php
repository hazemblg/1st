<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "gestion des evenements");
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
// Nombre total d'utilisateurs

$total_users_sql = "SELECT 
    SUM(LENGTH(participants) - LENGTH(REPLACE(participants, ',', '')) + 1) AS total_users 
    FROM events 
    WHERE participants IS NOT NULL AND participants != ''";
$total_users_result = $conn->query($total_users_sql);
$total_users = $total_users_result->fetch_assoc()['total_users'];

// Nombre total d'événements
$total_events_sql = "SELECT COUNT(*) AS total_events FROM events";
$total_events_result = $conn->query($total_events_sql);
$total_events = $total_events_result->fetch_assoc()['total_events'];


$users_sql = "SELECT  username, full_name, status FROM users LIMIT 5";
$users_result = $conn->query($users_sql);

$events_sql = "SELECT  titre, date_event, lieu FROM events ";
$events_result = $conn->query($events_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin</title>
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="Admin.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" 
	rel="stylesheet" 
	integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" 
	crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<link rel="icon" type="image/jfif" href="télécharger.jfif">

</head>
<body>
	<div class="wrapper">
		<aside id="sidebar">
			<!-- Sidebar content -->
			<div class="d-flex justify-content-between p-4">
				<div class="sidebar-logo">
					<a href="#">Admin</a><br><br>
					<a href="Accueil.php">Accueil</a>

				</div>
				<button class="toggle-btn border-0" type="button">
					<i id="icon" class='bx bx-chevrons-right'></i>
				</button>
			</div>
			<ul class="sidebar-nav">
				<li class="sidebar-item">
					<a href="#" class="sidebar-link">
						<i class='bx bxs-user-account' ></i>
						<span>Profile</span>
					</a>
				</li>
				<!-- More sidebar links here -->
			</ul>
			<div class="sidebar-footer">
				<a href="Login_Register.html" class="sidebar-link" >
					<i class='bx bx-log-out'></i>
					<span>Logout</span>
				</a>
			</div>
		</aside>

		<div class="main">
		<nav class="navbar navbar-expand px-4 py-3 bg-light justify-content-between">
    <div></div> 
    <div class="d-flex align-items-center">
        <span class="fw-bold me-3">Admin</span>
        <i class='bx bx-user-circle fs-3'></i>
    </div>
</nav>


			<main class="content px-3 py-4">
				<div class="container-fluid">
					<div class="row">
					<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h5 class="text-muted">TOTAL DES UTILISATEURS INSCRITS</h5>
            <h3><?= $total_users ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h5 class="text-muted">NOMBRE TOTAL D'ÉVÉNEMENTS CRÉÉS</h5>
            <h3><?= $total_events ?></h3>
        </div>
    </div>
    
</div>

						<!-- Section pour afficher les utilisateurs -->
						<div class="col-12 col-md-7">
							<h3 class="fw-bold fs-4 my-3">Users</h3>
							<table class="table table-striped">
								<thead>
									<tr class="highlight">
									  <th scope="col">Username</th>
									  <th scope="col">Full_name</th>
									  <th scope="col">Status</th>
									</tr>
								  </thead>
								  <tbody>
									<?php while($user = $users_result->fetch_assoc()): ?>
									<tr>
									  <td><?= htmlspecialchars($user["username"]) ?></td>
									  <td><?= htmlspecialchars($user["full_name"]) ?></td>
									  <td><?= htmlspecialchars($user["status"]) ?></td>
									</tr>
									<?php endwhile; ?>
								  </tbody>
							</table>
						</div>

						<!-- Section pour afficher les événements -->
						<div class="col-12 col-md-5">
							<h3 class="fw-bold fs-4 my-3">Events</h3>
							<table class="table table-striped">
								<thead>
									<tr class="highlight">
									  <th scope="col">Title</th>
									  <th scope="col">Date</th>
									  <th scope="col">Lieu</th>
									</tr>
								  </thead>
								  <tbody>
									<?php while($event = $events_result->fetch_assoc()): ?>
									<tr>
									  <td><?= htmlspecialchars($event["titre"]) ?></td>
									  <td><?= htmlspecialchars($event["date_event"]) ?></td>
									  <td><?= htmlspecialchars($event["lieu"]) ?></td>
									</tr>
									<?php endwhile; ?>
								  </tbody>
							</table>
						</div>
												<!-- Section pour afficher les participants -->
												<div class="col-12 col-md-5">
							<h3 class="fw-bold fs-4 my-3">Participants</h3>
							<table class="table table-striped">
								<thead>
									<tr class="highlight">
									  <th scope="col">Username</th>
									  <th scope="col">Event</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
$participants_query = $conn->query("SELECT titre, participants FROM events");
while ($row = $participants_query->fetch_assoc()):
    $titre = htmlspecialchars($row['titre']);
    $participants = explode(",", $row['participants']);
    foreach ($participants as $p):
        if (trim($p) !== ''):
?>
<tr>
  <td><?= htmlspecialchars(trim($p)) ?></td>
  <td><?= $titre ?></td>
</tr>
<?php
        endif;
    endforeach;
endwhile;
?>

								  </tbody>
							</table>
						</div>

					</div>
				</div>
			</main>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" 
	integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" 
	crossorigin="anonymous"></script>
	<script src="admin.js"></script>
</body> 
</html>

<?php $conn->close(); ?>
