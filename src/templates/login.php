<!--Dieses Template stellt die Loginseite der Anwendung dar.-->

<link rel="stylesheet" type="text/css" href="/HSWalkieTalkie/src/public/css/login.css">
<header>$$HSWalkieTalkie$$</header>
<main>
    <? global $router; ?>
    <div class="container">
    <form action="<?= $router->generate('loginPost'); ?>" method="post" class="form-horizontal">
        <fieldset>
            <legend>LogIn</legend>
            <!-- USERNAME ---------------------------------------------------------------- -->
            <div>
                <div class="form-group">
                    <label for="username" class="col-sm-4 control-label">
                        Benutzername:
                    </label>
                    <div class="col-xs-8">
                        <input name="username" class="form-control" id="username" type="text" placeholder="Benutzername" autofocus autocomplete="off">
                    </div>
                </div>
            </div>
            <!-- PASSWORT ---------------------------------------------------------------- -->
            <div>
                <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">
                        Passwort:
                    </label>
                    <div class="col-xs-8">
                        <input name="password" class="form-control" id="password" type="password" placeholder="Passwort" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="login-button" >
                <label for="password" class="col-sm-4 control-label"></label>
                <label for="password" class="col-sm-5 control-label" style="text-align:left;">
                    <?php
                        if(isset($message)){
                            echo $message;
                        }
                    ?>
                </label>
                <div class="form-group">
                    <button type="submit" id="login-button" class="btn btn-primary col-sm-3">
                        <i class="fa fa-sign-in" aria-hidden="true"> Login</i>
                    </button>
                </div>
            </div>

            <div class="register-link col-xs-offset-6">
                <a href="<?= $router->generate('registrierungGet'); ?>">Jetzt registieren und reich werden!</a>
            </div>
        </fieldset>
    </form>
    </div>
</main>