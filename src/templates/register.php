<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" xmlns="http://www.w3.org/1999/html">
<link rel="stylesheet" type="text/css" href="../css/register.css">

<body>
    <div class="container">
        <form action="../register/" class="form-horizontal" method="POST">

            <legend>Verpflichtende Angaben</legend>
            <div class="form-group"> <!-- form-group form-group-sm macht es kleiner, form-group form-group-lg macht es größer -->
                <label for="firstName" class="col-lg-6 control-label">Vorname*</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Vorname" autofocus required autocomplete="off">
                </div>
            </div>
            <div class="form-group"> <!-- form-group form-group-sm macht es kleiner, form-group form-group-lg macht es größer -->
                <label for="lastName" class="col-lg-6 control-label">Nachname*</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Nachname" required autocomplete="off">
                </div>
            </div>
            <div class="form-group"> <!-- form-group form-group-sm macht es kleiner, form-group form-group-lg macht es größer -->
                <label for="email" class="col-lg-6 control-label">E-Mail-Adresse*</label>
                <div class="col-lg-6">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label for="username" class="col-lg-6 control-label">Benutzername*</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Benutzername" required autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-lg-6 control-label">Passwort*</label>
                <div class="col-lg-6">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
            </div>
            <div class="form-group">
                <label for="confirmedPassword" class="col-lg-6 control-label">Passwort erneut eingeben*</label>
                <div class="col-lg-6">
                    <input type="password" class="form-control" id="confirmedPassword" name="confirmedPassword" placeholder="Password" required>
                </div>
            </div>
            <legend>Optionale Angaben</legend>
            <div class="form-group">
                <label for="iban" class="col-lg-6 control-label">IBAN</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control" id="iban" name="iban" placeholder="IBAN" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label for="bic" class="col-lg-6 control-label">BIC</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control" id="bic" name="bic" placeholder="BIC" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-6 control-label"></label>
                <div class="col-lg-6">
                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
                        <div class="btn-group" role="group">
                            <button type="submit" class="btn btn-primary" name="register">Registrieren</button>
                        </div>
                        <div class="btn-group" role="group">
                            <a href="../" class="btn btn-default active" role="button">Abbrechen</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>