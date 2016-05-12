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
        <li <? if ($GLOBALS['match']['name']=='settings') { ?>class="active"<? } ?> > <a href="<? global $router; echo $router->generate("settings");?>"><span class="fa fa-gear fa-2x"></span> Einstellungen</a></span></li>
      </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="<?= $GLOBALS['router']->generate('logoutGet'); ?>"><span class="fa fa-power-off fa-2x"></span></a></li>
        </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<!--TODO: URL's für NavBar auslagern-->
