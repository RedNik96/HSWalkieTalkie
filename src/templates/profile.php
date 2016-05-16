<link rel="stylesheet" href="/HSWalkieTalkie/src/public/css/profile.css">
<div class="row picture-row">
  <img src="<?= $user_info['picture'] ?>" class="img-responsive" alt="Profil Bild" />
</div>
<div class="row name-row col-md-offset-1">
  <div>
    <?= $user_info['lastName'] ?>,
    <?= $user_info['firstName'] ?>
  </div>
  <div>
    @<?= $user_info['username'] ?>
  </div>
</div>
<div class="row info-row col-md-offset-1">
  <div>
      <i id="addUser" class="fa <?php if($user_info['isFollowing'] == 0) echo "fa-user-plus"; else echo "fa-user-times"; ?> fa-2x" aria-hidden="true"
          data-url="<?= $GLOBALS['router']->generate('followUserPOST'); ?>"
          data-followeduser="<?php echo $user_info['username'] ?>"
          data-followeruser="<?php echo $_SESSION['user']; ?>"
      ></i>
  </div>
  <div><?= $user_info['email'] ?></div>
  <div><?= $user_info['zip'] ?> <?= $user_info['city'] ?></div>
  <div><?= $user_info['birthday'] ?></div>
</div>

<script type="text/javascript">
    $('#addUser').on('click', function(){
        var url = $(this).data('url');
        var userFollowed = $(this).data('followeduser');
        var userFollower = $(this).data('followeruser');

        $.post(url,
            {
                userFollowed: userFollowed,
                userFollower: userFollower
            }, function(returnedData) {
                if (returnedData.trim() == "added") {
                    $('#addUser').removeClass("fa-user-plus");
                    $('#addUser').addClass("fa-user-times");
                } else if (returnedData.trim() == "removed") {
                    $('#addUser').removeClass("fa-user-times");
                    $('#addUser').addClass("fa-user-plus");
                } else {
                    alert('Beim Folgen dieses Users ist ein Fehler aufgetreten.');
                }
            });
    });
</script>