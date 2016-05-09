<!-- Font Awesome-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/HSWalkieTalkie/src/public/css/login.css">
<form action="index.php" method="post" class="login">
    
    <fieldset >
        <legend>LogIn</legend>

        <div class="username">
            <label for="username">
                Benutzername:
            </label>
            <input name="username" id="username" type="text" placeholder="Benutzername" autofocus autocomplete="off">
        </div>

        <div class="password">
            <label for="password">
                Passwort:
            </label>
            <input name="password" id="password" type="password" placeholder="Passwort" autocomplete="off">
        </div>

        <div class="login-button">
            <button type="submit" id="login-button">
            <i class="fa fa-sign-in" aria-hidden="true"> Login</i>
            </button>
        </div>

        <div class="register-link">
            <a href="register.php" class="button">Rich Bitch werden - Registieren</a>
        </div>


    </fieldset>
    
</form>