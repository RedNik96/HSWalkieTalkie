<link rel="stylesheet" type="text/css" href="/HSWalkieTalkie/src/public/css/settings.css">
    <div class="container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#personal">persönliche Informationen</a></li>
            <li><a data-toggle="tab" href="#pass">Passwort ändern</a></li>
            <li><a data-toggle="tab" href="#konto">Konto</a></li>
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
                                    <label for="vorname" class=" col-sm-6 control-label">
                                        Vorname:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="vorname" id="vorname" type="text" autofocus class="form-control">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="nachname" class=" col-sm-6 control-label">
                                        Nachname:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="nachname" id="nachname" type="text" autofocus class="form-control">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="username" class=" col-sm-6 control-label">
                                        Username:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="username" id="username" type="text" autofocus class="form-control">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="geb" class=" col-sm-6 control-label">
                                        Geburtstag:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="geb" id="geb" type="date" autofocus class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ort" class=" col-sm-6 control-label">
                                        Ort:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="ort" id="ort" type="text" autofocus class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="plz" class=" col-sm-6 control-label">
                                        PLZ:
                                    </label>
                                    <div class="col-sm-6">
                                        <input name="plz" id="plz" type="number" autofocus class="form-control">
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
            <div id="konto" class="tab-pane fade">
                <h3>Kontoinformationen</h3>
                <div class="row">
                        <div>
                            <label for="email" class="inputlabel">
                                Email:
                            </label>
                            <input name="email" id="email" type="email" autofocus class="input">
                        </div>
                        <div>
                            <label for="vorname" class="inputlabel">
                                Vorname:
                            </label>
                            <input name="vorname" id="vorname" type="text" class="input">
                        </div>
                        <div>
                            <label for="nachname" class="inputlabel">
                                Nachname:
                            </label>
                            <input name="nachname" id="nachname" type="text" class="input">
                        </div>
                        <div>
                            <label for="username" class="inputlabel">
                                Username:
                            </label>
                            <input name="username" id="username" type="text" class="input">
                        </div>
                        <div>
                            <label for="geb" class="inputlabel">
                                Geburtstag:
                            </label>
                            <input name="geb" id="geb" type="date" class="input">
                        </div>
                        <div>
                            <label for="ort" class="inputlabel">
                                Wohnort:
                            </label>
                            <input name="ort" id="ort" type="text" class="input">
                        </div>
                        <div>
                            <label for="plz" class="inputlabel">
                                PLZ:
                            </label>
                            <input name="plz" id="plz" type="number" class="input">
                        </div>
                    </div>
            </div>
            <div id="ilias" class="tab-pane fade">
                <h3>Ilias Einstellungen</h3>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
            </div>
        </div>
    </div>
