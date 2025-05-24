<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil | EventManager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
  rel="stylesheet">
  <link rel="stylesheet" href="Accueil.css">
  <link rel="icon" type="image/jfif" href="télécharger.jfif">

</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">EventManager</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="events.php">Événements</a></li>
          <li class="nav-item"><a class="nav-link"  onclick="admin()">Admin</a></li>
          <li class="nav-item"><a class="nav-link" href="Login_Register.html">Login_Register</a></li>

        </ul>
      </div>
    </div>
  </nav>

  <header class="hero-section text-white text-center">
    <div class="container py-5">
      <h1 class="display-4">Organisez ou découvrez des événements intéressants</h1>
      <p class="lead">Créez, gérez et explorez facilement des événements avec EventManager</p>
      <a href="events.php" class="btn btn-primary btn-lg">Voir les événements</a>
    </div>
  </header>
  <script>
    function admin(){
      const pass =prompt("entrez votre mot de passe 'Admin'");
      if(pass==="ahmed_hazem"){
      window.location.href="admin.php"      
    }else{
      alert("Mot de passe incorrect")
    }
    }
  </script>
</body>
</html>

