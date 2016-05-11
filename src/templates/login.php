
<link rel="stylesheet" type="text/css" href="/HSWalkieTalkie/src/public/css/login.css">
<form action="index.php" method="post" class="login">
    <header>$$HSWalkieTalkie$$</header>
    <main>
        <div class="container">
        <form action="index.php" method="post" class="form-horizontal">
            <fieldset>
                <legend>LogIn</legend>
                <div class="username">
                    <div class="form-group">
                        <label for="username" class="col-sm-4 control-label">
                            Benutzername:
                        </label>
                        <div class="col-xs-8">
                            <input name="username" class="form-control" id="username" type="text" placeholder="Benutzername" autofocus autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="password">
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
                    <div class="form-group">
                        <button type="submit" id="login-button" class="btn btn-primary col-xs-3">
                            <i class="fa fa-sign-in" aria-hidden="true"> Login</i>
                        </button>
                    </div>
                </div>

                <div class="register-link col-xs-offset-6">
                    <a href="<?= $url ?>">$Rich Bitch werden - Registieren</a>
                </div>
            </fieldset>
        </form>
        </div>
    </main>
</form>