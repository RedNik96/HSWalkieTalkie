<div class="row picture-row">
  <img src="<?= $user_info['picture'] ?>" class="img-responsive" alt="Profil Bild" />
</div>
<div class="row name-row">
  <div>
    <?= $user_info['lastName'] ?>,
    <?= $user_info['firstName'] ?>
  </div>
  <div>
    @<?= $user_info['username'] ?>
  </div>
</div>
<div class="row info-row">
  <div><?= $user_info['email'] ?></div>
  <div><?= $user_info['zip'] ?> <?= $user_info['city'] ?></div>
  <div><?= $user_info['birthday'] ?></div>
</div>

<style>
  .name-row {
    font-weight: bold;
    margin-top: 1em;
  }
  .info-row {
    margin-top: 1em;
  }
</style>
