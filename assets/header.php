<?php
  if(getTeamLevel($userid)) {
  ?>
  <div class="nav-scroller admin-nav-dark shadow-sm">
    <nav class="nav nav-underline mr-auto">
      <a class="nav-link active" aria-current="page">Team Panel</a>
      <a class="nav-link" href="<?php base()?>adminpanel/"><i class="fas fa-tachometer-alt"></i> Control-Panel</a>
      <?php
        if($url[0] == "viewrecipe") {
          echo '<a class="nav-link" href="#" data-toggle="modal" data-target="#recipeManager"><i class="fas fa-cogs"></i> Rezept verwalten</a>';
        }
        if($url[0] == "recipes") {
          echo '<a class="nav-link" href="#" data-toggle="modal" data-target="#recipeManager"><i class="fas fa-cogs"></i> Ausstehende Rezepte</a>';
        }
        if($url[0] == "profile") {
          echo '<a class="nav-link" href="#" data-toggle="modal" data-target="#userManager"><i class="fas fa-cogs"></i> Nutzer verwalten</a>';
          echo '<a class="nav-link" href="#"><i class="fas fa-sign-in-alt"></i> Als Nutzer Anmelden</a>';
        }
      ?>
    </nav>
  </div>
  <?php    
  }
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">
      <img src="<?php base() ?>img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
      Pommes Unity 
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?php base() ?>home/">Start</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php base() ?>recipes/">Rezepte</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <?php
          if(isset($_SESSION['pu_login'])) {
            ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i> <span class="badge bg-info">0</span>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
              </ul>
            </li>
            <?php
          }
        ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
            Konto
          </a>
          <?php
            if(isset($_SESSION['pu_login'])) {
              ?>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="<?php base() ?>myaccount/"><i class="fas fa-user"></i> <?php echo getDisplayname(); ?></a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?php base() ?>logout/"><i class="fas fa-power-off"></i> Abmelden</a></li>
              </ul>
              <?php
            } else {
              ?>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="<?php base() ?>login/"><i class="fas fa-sign-in-alt"></i> Anmelden</a></li>
                <li><div class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?php base() ?>register/"><i class="fas fa-user-plus"></i> Registrieren</a></li>
              </ul>
              <?php
            }
          ?>
        </li>
      </ul>
    </div>
  </div>
</nav>
