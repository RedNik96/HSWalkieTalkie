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
                    <legend>
                        Persönliche Informationen
                    </legend>
                    <div >
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email" class=" col-lg-6 control-label">
                                    Email:
                                </label>
                                <div class="col-lg-6">
                                    <input name="email" id="email" type="email" autofocus class="form-control">
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="vorname" class=" col-lg-6 control-label">
                                    Vorname:
                                </label>
                                <div class="col-lg-6">
                                    <input name="vorname" id="vorname" type="text" autofocus class="form-control">
                                </div>

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
                        <div class="col-lg-6 imagepanel">
                            <div >
                                <img src="" alt="Profilbild">
                            </div>

                            <button class="btn btn-default">Profilbild ändern</button>
                        </div>
                    </div>
                    <div class="row buttonrow">
                        <div class="col-lg-10">
                            <button class="btn btn-primary">Änderungen speichern</button>
                            <button class="btn btn-default">Änderungen verwerfen</button>
                        </div>

                    </div>
                </div>

            </div>
            <div id="pass" class="tab-pane fade">
                <h3>Passwort ändern</h3>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
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
