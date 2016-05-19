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
      <a class="navbar-brand" href="<? global $router; echo $router->generate("timeline");?>">HSWalkieTalkie</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li <? if ($GLOBALS['match']['name']=='timeline') { ?>class="active"<? } ?> > <a href="<? global $router; echo $router->generate("timeline");?>"><span class="fa fa-newspaper-o fa-2x"></span> Neuigkeiten</a></span></li>
        <li <? if ($GLOBALS['match']['name']=='profile') { ?>class="active"<? } ?> > <a href="<? global $router; echo $router->generate("profile");?>"><span class="fa fa-user fa-2x"></span> Profil</a></span></li>
        <li <? if ($GLOBALS['match']['name']=='settingsGet') { ?>class="active"<? } ?> > <a href="<? global $router; echo $router->generate("settingsGet",array('tab'=>0));?>"><span class="fa fa-gear fa-2x"></span> Einstellungen</a></span></li>
        <form id="searchForm" display="inline" class="navbar-form navbar-left" method="post" action="<? global $router;echo $router->generate('searchPost') ?>">
          <div class="form-group">
            <select name="search" type="search" id="searchBar" type="text" class="select2-input" placeholder="Suchen">
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
  $.ajax({
    method: 'POST',
    url: '/HSWalkieTalkie/src/public/searchData/',
  }).then(function (data) {
    var names= '[' +
      '{ "text":"Benutzer" , "children": [ ';
    
    for (var i = 0; i < data.names.length; i++) {
      if (i>0) {
        names+=',';
      }
      names+='{ "id":"u'+data.names[i]+'", "text":"'+data.names[i]+'"}';
    }
    names+=']},{ "text":"Benutzernamen" , "children": [ ';
    for (var i = 0; i < data.fullNames.length; i++) {
      if (i>0) {
        names+=',';
      }
      names+='{ "id":"n'+data.fullNames[i]+'", "text":"'+data.fullNames[i]+'"}';
    }
    if(data.tags) {
      names += ']},{ "text":"$chashtags" , "children": [ ';
      for (var i = 0; i < data.tags.length; i++) {
        if (i > 0) {
          names += ',';
        }
        names += '{ "id":"' + data.tags[i] + '", "text":"' + data.tags[i] + '"}';
      }
    }
    names+=']}]';
    var obj = JSON.parse(names);

    $('#searchBar').select2({
      placeholder: 'Name oder $cashtag suchen',
      data: obj,
      closeOnSelect: false
    });

  });



</script>

<!--TODO: URL's für NavBar auslagern-->
