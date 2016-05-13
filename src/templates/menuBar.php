<link href="/HSWalkieTalkie/src/libraries/select2-4.0.2/dist/css/select2.min.css" rel="stylesheet" />
<script src="/HSWalkieTalkie/src/libraries/select2-4.0.2/dist/js/select2.min.js"></script>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">HSWalkieTalkie</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li <? if ($GLOBALS['match']['name']=='timeline') { ?>class="active"<? } ?> > <a href="<? global $router; echo $router->generate("timeline");?>"><span class="fa fa-newspaper-o fa-2x"></span> Neuigkeiten</a></span></li>
        <li <? if ($GLOBALS['match']['name']=='profile') { ?>class="active"<? } ?> > <a href="<? global $router; echo $router->generate("profile");?>"><span class="fa fa-user fa-2x"></span> Profil</a></span></li>
        <li <? if ($GLOBALS['match']['name']=='settingsGet') { ?>class="active"<? } ?> > <a href="<? global $router; echo $router->generate("settingsGet");?>"><span class="fa fa-gear fa-2x"></span> Einstellungen</a></span></li>
        <form class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <select type="search" id="searchBar" type="text" class="form-control" placeholder="Suchen">
              <option></option>
            </select>
          </div>
          <button type="submit" class="btn btn-default"><i class="fa fa-search" aria-hidden="true"></i> Suchen</button>
        </form>
      </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="<?= $GLOBALS['router']->generate('logoutGet'); ?>"><span class="fa fa-power-off fa-2x"></span></a></li>
        </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<script type="text/javascript">
  $('#searchBar').select2({
    placeholder: 'Name oder $cashtag suchen',
  });
  $.ajax({
    method: 'POST',
        url: '/HSWalkieTalkie/src/public/searchData/',
    }).then(function (data) {
      $data=data;
    for (var i = 0; i < data.names.length; i++) {
      var $option = $('<option></option>').val('');
      $option.text($data.names[i]).val($data.names[i]); // update the text that is displayed (and maybe even the value)
      $('#searchBar').append($option).trigger('change'); // append the option and update Select2
    }
    for (var i = 0; i < data.fullNames.length; i++) {
      var $option = $('<option></option>').val('');
      $option.text($data.fullNames[i]).val($data.fullNames[i]); // update the text that is displayed (and maybe even the value)
      $('#searchBar').append($option).trigger('change'); // append the option and update Select2
    }
  });


</script>

<!--TODO: URL's für NavBar auslagern-->
