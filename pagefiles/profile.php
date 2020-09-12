<?php
  if($url[1] == "" || !is_numeric($url[1])) {
    header("Location: home/");
  }
?>

<div class="row">
  <div class="col-md-2" style="border-right: 1px solid white;">
      <img src="<?php base() ?>img/background.gif" alt="" class="sticky-top overflow-auto rounded shadow profile-img" style="margin-bottom: 15px;">
      <h5>Nutzername</h5>
  </div>
  <div class="col-md-10">
    <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Profil</a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Alle Rezepte</a>
      </li>
      <li class="nav-item" role="presentation" title="Folgen wurde deaktiviert.">
        <a class="nav-link disabled shadow-sm bg-white rounded" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-disabled="true">Folgen</a>
      </li>
  </ul>
  <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">...</div>
      <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
    </div>
  </div>
</div>


<div class="modal fade" id="reportUser" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Nutzer melden</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="disabledTextInput" class="form-label">Nutzer ID</label>
                    <input type="text" id="disabledTextInput" class="form-control" placeholder="<?= $data['pu_recipeid']; ?>" value="<?= $data['pu_recipeid']; ?>" required disabled>
                </div>
                <div class="mb-3">
                    <label for="inputPassword5" class="form-label">Grund in wenigen Worten</label>
                    <input type="password" id="inputPassword5" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-dark float-right">Jetzt melden</button>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
          </div>
        </div>
      </div>
    </div>

<?php
  if(getTeamLevel($userid)) {
    ?>
    <div class="modal fade" id="userManager" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Rezept verwalten</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="disabledTextInput" class="form-label">Nutzer ID</label>
                    <input type="text" id="disabledTextInput" class="form-control" placeholder="<?= $data['pu_recipeid']; ?>" value="<?= $data['pu_recipeid']; ?>" required disabled>
                </div>
                <div class="mb-3">
                    <label for="inputPassword5" class="form-label">Grund in wenigen Worten</label>
                    <input type="password" id="inputPassword5" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-dark float-right">Jetzt melden</button>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
?>