
<!--link rel="stylesheet" type="text/css" href="/HSWalkieTalkie/src/public/css/login.css"-->
<form action="index.php" method="post" class="login">
    <header>$$Heißer Scheiß du Wixxer$$</header>
    <main>
        <form action="index.php" method="post" class="form-horizontal">
            <fieldset >
                <legend>LogIn</legend>

                <div class="username">
                    <label for="username" class="col-sm-6 control-label">
                        Benutzername:
                    </label>
                    <input name="username" id="username" type="text" placeholder="Benutzername" autofocus autocomplete="off">
                </div>

                <div class="password">
                    <label for="password" class="col-sm-6 control-label">
                        Passwort:
                    </label>
                <input name="password" id="password" type="password" placeholder="Passwort" autocomplete="off">
                </div>

                <div class="login-button">
                <button type="submit" id="login-button" class="btn btn-default">
                    <i class="fa fa-sign-in" aria-hidden="true"> Login</i>
                </button>
                </div>

                <div class="register-link">
                    <a href="register.php">$Rich Bitch werden - Registieren</a>
                </div>
            </fieldset>
        </form>
    </main>
    <footer>
    </footer>
</form>