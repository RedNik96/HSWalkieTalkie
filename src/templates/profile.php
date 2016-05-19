<link rel="stylesheet" href="/HSWalkieTalkie/src/public/css/profile.css">
<div class="row picture-row">
  <img src="/HSWalkieTalkie/src/img/profile/<?= $user_info['picture'] ?>" class="img-responsive" alt="Profil Bild" />
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
  <?php if($_SESSION['user'] != $user_info['username']) { ?>
  <div>
      <button id="followUser" type="button" class="btn btn-primary"
          data-url="<?= $GLOBALS['router']->generate('followUserPOST'); ?>"
          data-followeduser="<?php echo $user_info['username'] ?>"
          data-followeruser="<?php echo $_SESSION['user']; ?>"
          data-isFollowing="<?php echo $user_info['isFollowing']; ?>"
      >
          <i id="iconFollowUser" class="fa fa-2x" aria-hidden="true"></i>
          <span id="textFollowUser"></span>
      </button>
  </div>
    <?php } ?>
  <div><?= $user_info['email'] ?></div>
  <div><?= $user_info['zip'] ?> <?= $user_info['city'] ?></div>
  <div><?= $user_info['street']?> <?= $user_info['housenumber']?></div>
  <div><?= date("d.m.Y",strtotime($user_info['birthday'])) ?></div>
  <div>
      <?  $date=new DateTime(date('Y-m-d',strtotime($user_info['birthday'])));
      $today=new DateTime(date('Y-m-d'));
      $diff=$date->diff($today);
      echo $diff->format('%y'); ?> Jahre
  </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        var isFollowing = document.getElementById('followUser').getAttribute('data-isFollowing');
        if(isFollowing == 0)
            replaceIcon('removed');
        else
            replaceIcon('added');
    });
    $('#followUser').on('click', function(){

        var url = $(this).data('url');
        var userFollowed = $(this).data('followeduser');
        var userFollower = $(this).data('followeruser');

        var span = (this).getElementsByTagName("span")[0];

        $.post(url,
            {
                userFollowed: userFollowed,
                userFollower: userFollower
            }, function(returnedData) {
                replaceIcon(returnedData);
            });
    });
    function replaceIcon(data) {
        var span = document.getElementById('textFollowUser');
        if (data.trim() == "added") {
            $('#iconFollowUser').removeClass("fa-user-plus");
            $('#iconFollowUser').addClass("fa-user-times");
            span.innerHTML = 'Nicht mehr folgen';
        } else if (data.trim() == "removed") {
            $('#iconFollowUser').removeClass("fa-user-times");
            $('#iconFollowUser').addClass("fa-user-plus");
            span.innerHTML = 'Folgen';
        } else {
            alert('Beim Folgen dieses Users ist ein Fehler aufgetreten.');
        }
    };
</script>