<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" xmlns="http://www.w3.org/1999/html">
<link rel="stylesheet" type="text/css" href="../css/register.css">

<? global $router; ?>
<div class="container">
    <form action="<?= $router->generate('registrierungPost'); ?>" class="form-horizontal" method="POST">

        <legend>Verpflichtende Angaben</legend>
        <div class="form-group"> <!-- form-group form-group-sm macht es kleiner, form-group form-group-lg macht es größer -->
            <label for="firstName" class="col-lg-6 control-label">Vorname*</label>
            <div class="col-lg-6">
                <input pattern="[A-Za-zÄÜÖäüöß][A-Za-zÄÜÖäüöß- ]+" type="text" class="form-control" id="firstName" name="firstName" placeholder="Vorname" maxlength="255" autofocus required autocomplete="off">
            </div>
        </div>
        <div class="form-group"> <!-- form-group form-group-sm macht es kleiner, form-group form-group-lg macht es größer -->
            <label for="lastName" class="col-lg-6 control-label">Nachname*</label>
            <div class="col-lg-6">
                <input pattern="[A-Za-zÄÜÖäüöß][A-Za-zÄÜÖäüöß- ]+" type="text" class="form-control" id="lastName" name="lastName" placeholder="Nachname" maxlength="255" required autocomplete="off">
            </div>
        </div>
        <div class="form-group"> <!-- form-group form-group-sm macht es kleiner, form-group form-group-lg macht es größer -->
            <label for="email" class="col-lg-6 control-label">E-Mail-Adresse*</label>
            <div class="col-lg-6">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" maxlength="255" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="username" class="col-lg-6 control-label">Benutzername*</label>
            <div class="col-lg-6">
                <input url="<? global $router; echo $router->generate('registerCheckUserPost');?>" pattern="[A-Za-zÄÜÖäüöß0-9-_]+" type="text" class="form-control" id="username" name="username" placeholder="Benutzername" maxlength="100" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-lg-6 control-label">Passwort*</label>
            <div class="col-lg-6">
                <input type="password" class="form-control" id="password" name="password" placeholder="Passwort" maxlength="255" required>
            </div>
        </div>
        <div class="form-group">
            <label for="confirmedPassword" class="col-lg-6 control-label">Passwort erneut eingeben*</label>
            <div class="col-lg-6">
                <input type="password" class="form-control" id="confirmedPassword" name="confirmedPassword" placeholder="Passwort" maxlength="255" required>
            </div>
        </div>
        <div class="form-group">
            <label for="birthday" class="col-lg-6 control-label">Geburtstag*</label>
            <div class="col-lg-6">
                <input type="date" class="form-control" id="birthday" name="birthday" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="street" class="col-lg-6 control-label">Straße*</label>
            <div class="col-lg-6">
                <input pattern="[A-Za-zÄÜÖäüöß][A-Za-zÄÜÖäüöß.- ]+" type="text" class="form-control" id="street" name="street" placeholder="Straße" maxlength="255" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="housenumber" class="col-lg-6 control-label">Hausnummer*</label>
            <div class="col-lg-6">
                <input pattern="[0-9][0-9a-z-]+" type="text" class="form-control" id="housenumber" name="housenumber" placeholder="Hausnummer" maxlength="255" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="zip" class="col-lg-6 control-label">PLZ*</label>
            <div class="col-lg-6">
                <select class="form-control" id="zip" name="zip" data-codes="<?= $zips; ?>" required>
                    <option></option>
                    <?php
                        global $dbh;
                        $zips = array();
                        $stmt = $dbh->prepare("SELECT zip, city from city");
                        $stmt->execute();
                        while($result = $stmt->fetch()) {
                            echo "<option>" . $result[0] . "</option>";
                            $zips[$result[0]] = $result[1];
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="city" class="col-lg-6 control-label">Stadt</label>
            <label id="city" class="col-lg-6 control-label" style="overflow: hidden; text-align:left;"></label>
        </div>
        <legend>Optionale Angaben</legend>
        <div class="form-group">
            <label for="iban" class="col-lg-3 control-label">IBAN</label>
            <div class="col-lg-9">
                <input pattern="[A-Z0-9]+" type="text" class="form-control" id="iban" name="iban" placeholder="IBAN" maxlength="34" autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="bic" class="col-lg-3 control-label">BIC</label>
            <div class="col-lg-5">
                <select class="form-control" id="bic" name="bic">
                    <option></option>
                    <?php
                        $bics = array();
                        $stmt = $dbh->prepare("SELECT bic, bank from bic");
                        $stmt->execute();
                        while($result = $stmt->fetch()) {
                            echo "<option>" . $result[0] . "</option>";
                            $bics[$result[0]] = $result[1];
                        }
                    ?>
                </select>
                <!--<input type="text" class="form-control" id="bic" name="bic" placeholder="BIC" autocomplete="off">-->
            </div>
        </div>
        <div class="form-group">
            <label for="city" class="col-lg-3 control-label">Kreditinstitut</label>
            <label class="col-lg-9 control-label" id="bankname" style="text-align:left;"></label>
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

<script type="text/javascript">


    $('#confirmedPassword').on('change', function(){
        if ((this).value!==$('#password').val()) {
            $('#confirmedPassword').focus();
            $('#confirmedPassword').val('');
            $('#confirmedPassword').attr("placeholder", "Passwort stimmt nicht überein");
            $('#confirmedPassword').addClass('wrong');
        } else {
            $('#confirmedPassword').attr("placeholder", "");
            $('#confirmedPassword').removeClass('wrong');
        }
    });
    $('#username').on('change', function(){
        var username=(this).value;
        var url=document.getElementById('username').getAttribute('url');
        $.post(url,
            {
                check_user: "true",
                username: ""+username
            },
            function(data){
                if (data==="    false") {
                    $('#username').focus();
                    $('#username').val('');
                    $('#username').attr("placeholder", "Benutzername schon vergeben");
                    $('#username').addClass('wrong');
                } else {
                    $('#username').attr("placeholder", "");
                    $('#username').removeClass('wrong');
                }

            });
    });
</script>