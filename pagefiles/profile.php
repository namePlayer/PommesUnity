<h4>Konto-Name</h4>


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