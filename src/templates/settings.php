<link rel="stylesheet" type="text/css" href="/HSWalkieTalkie/src/public/css/settings.css">
    <div class="container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#personal">persönliche Informationen</a></li>
            <li><a data-toggle="tab" href="#pass">Passwort ändern</a></li>
            <li><a data-toggle="tab" href="#account">Konto</a></li>
            <li><a data-toggle="tab" href="#ilias">Ilias-Feed</a></li>
        </ul>

        <div class="tab-content">
            <div id="personal" class="tab-pane fade in active">
                <div class="container-border">
                    <form class="form-horizontal" method="post">
                        <legend>
                            Persönliche Informationen
                        </legend>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email" class=" col-sm-6 control-label">
                                        Email:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="email" id="email" type="email" autofocus class="form-control">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="firstname" class=" col-sm-6 control-label">
                                        Vorname:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="firstname" id="firstname" type="text" autofocus class="form-control">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="lastname" class=" col-sm-6 control-label">
                                        Nachname:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="lastname" id="lastname" type="text" autofocus class="form-control">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="username" class=" col-sm-6 control-label">
                                        Benutzername:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="username" id="username" type="text" autofocus class="form-control">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="birth" class=" col-sm-6 control-label">
                                        Geburtstag:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="birth" id="birth" type="date" autofocus class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="street" class=" col-sm-6 control-label">
                                        Straße:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="street" id="street" type="text" autofocus class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nr" class=" col-sm-6 control-label">
                                        Hausnummer:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="nr" id="nr" type="text" autofocus class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="city" class=" col-sm-6 control-label">
                                        Ort:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="city" id="city" type="text" autofocus class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="zip" class=" col-sm-6 control-label">
                                        PLZ:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="zip" id="zip" type="number" autofocus class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 imagepanel">
                                <div >
                                    <img src="" alt="Profilbild">
                                </div>

                                <button class="btn btn-default">Profilbild ändern</button>
                            </div>
                        </div>
                        <div class="row buttonrow">
                            <div class="form-group">
                                <label class="col-sm-6 control-label"></label>
                                <div class="col-sm-4">
                                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                        <div class="btn-group" role="group">
                                            <button type="submit" class="btn btn-primary" name="change-settings">Änderungen speichern</button>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <a href="../settings/" class="btn btn-default active" role="button">Änderungen verwerfen</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="pass" class="tab-pane fade">
                <div class="container-border">
                    <form class="form-horizontal">
                        <legend>
                            Passwort ändern
                        </legend>
                                <div class="form-group">
                                    <label for="old" class=" col-lg-6 control-label">
                                        Altes Passwort:
                                    </label>
                                    <div class="col-lg-6">
                                        <input name="old" id="old" type="password" autofocus class="form-control">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="new" class=" col-lg-6 control-label">
                                        Neues Passwort:
                                    </label>
                                    <div class="col-lg-6">
                                        <input name="new" id="new" type="password" autofocus class="form-control">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="verify" class=" col-lg-6 control-label">
                                        Wiederhole Passwort:
                                    </label>
                                    <div class="col-lg-6">
                                        <input name="verify" id="verify" type="password" autofocus class="form-control">
                                    </div>
                                </div>
                        <div class="form-group">
                            <label class="col-lg-6 control-label"></label>
                            <div class="col-lg-4">
                                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                    <div class="btn-group" role="group">
                                        <button type="submit" class="btn btn-primary" name="change-pwd">Passwort ändern</button>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <a href="../settings/" class="btn btn-default active" role="button">Änderungen verwerfen</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div id="account" class="tab-pane fade">
                <div class="container-border">
                    <form class="form-horizontal">
                        <legend>
                            Kontodaten ändern
                        </legend>
                        <div class="form-group">
                            <label for="iban" class=" col-lg-6 control-label">
                                IBAN:
                            </label>
                            <div class="col-lg-6">
                                <input name="iban" id="iban" type="text" autofocus class="form-control">
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="bic" class=" col-lg-6 control-label">
                                BIC:
                            </label>
                            <div class="col-lg-6">
                                <input name="big" id="big" type="text" autofocus class="form-control">
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="holder" class=" col-lg-6 control-label">
                                Kontoinhaber:
                            </label>
                            <div class="col-lg-6">
                                <input name="holder" id="holder" type="text" autofocus class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bank" class=" col-lg-6 control-label">
                                Kreditinstitut:
                            </label>
                            <div class="col-lg-6">
                                <input name="bank" id="bank" type="text" autofocus class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-6 control-label"></label>
                            <div class="col-lg-4">
                                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                    <div class="btn-group" role="group">
                                        <button type="submit" class="btn btn-primary" name="change-account">Kontodaten ändern</button>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <a href="../settings/" class="btn btn-default active" role="button">Änderungen verwerfen</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="ilias" class="tab-pane fade">
                <div class="container-border">
                    <form class="form-horizontal">
                        <legend>
                            Ilias-Feed Einstellungen
                        </legend>
                        <div class="form-group">
                            <label for="url" class=" col-lg-6 control-label">
                                RSS-Feed-URL:
                            </label>
                            <div class="col-lg-6">
                                <input name="url" id="url" type="url" autofocus class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-6 control-label"></label>
                            <div class="col-lg-4">
                                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                    <div class="btn-group" role="group">
                                        <button type="submit" class="btn btn-primary" name="change-ilias">Ilias-Feed ändern</button>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <a href="../settings/" class="btn btn-default active" role="button">Änderungen verwerfen</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
