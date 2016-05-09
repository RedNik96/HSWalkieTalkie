<!--<link rel="stylesheet" type="text/css" href="/HSWalkieTalkie/src/public/css/standard.css">-->
<link rel="stylesheet" type="text/css" href="../css/standard.css">
<link rel="stylesheet" type="text/css" href="../css/register.css">

<form action="registerHandling.php">
    <fieldset>
        <legend>Registrieren</legend>
        <table>
            <tr>
                <td style="width:30px;">
                    <span class="dividingLine">
                        Verpflichtende Angaben
                    </span>
                </td>
                <td>
                    <hr>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="email">E-Mail-Adresse*</label>
                </td>
                <td>
                    <input name="email" id="email" type="text" placeholder="max.mustermann@gmail.com" autofocus autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="username">Benutzername*</label>
                </td>
                <td>
                    <input name="username" id="username" type="text" placeholder="Max Mustermann" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="password">Passwort*</label>
                </td>
                <td>
                    <input name="password" id="password" type="password" placeholder="Passwort" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="passwordConfirm">Passwort erneut eingeben*</label>
                </td>
                <td>
                    <input name="passwordConfirm" id="passwordConfirm" type="password" placeholder="Passwort" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <span class="dividingLine">
                        Optionale Angaben
                    </span>
                </td>
                <td>
                    <hr>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="iban">IBAN</label>
                </td>
                <td>
                    <input name="iban" id="iban" type="text" placeholder="" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="BIC">BIC</label>
                </td>
                <td>
                    <input name="bic" id="bic" type="text" placeholder="" autocomplete="off">
                </td>
            </tr>
        </table>
    </fieldset>
</form>