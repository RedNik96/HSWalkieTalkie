<link rel="stylesheet" type="text/css" href="/HSWalkieTalkie/src/public/css/settings.css">
<link href="/HSWalkieTalkie/bootstrap-fileinput-master/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<script src="/HSWalkieTalkie/bootstrap-fileinput-master/js/fileinput.min.js"></script>

    <div class="container">
        <script type="text/javascript">
            function deleteFunction() {
                var inputs = document.getElementsByClassName('filled');
                for (var i = 0; i < inputs.length; ++i) {
                    inputs[i].value = inputs[i].getAttribute('data-value');
                }
                $("label[for='username']").text('Benutzername:');
                $("label[for='username']").css('color', 'black');
                $("label[for='old']").text('Altes Passwort:');
                $("label[for='old']").css('color', 'black');
                $("label[for='verify']").text('Wiederhole Passwort:');
                $("label[for='verify']").css('color', 'black');
            }

        </script>

        <ul class="nav nav-tabs">
            <li <? if ($tab===0) {?>class="active" <?;} ?>><a data-toggle="tab" href="#personal">persönliche Informationen</a></li>
            <li <? if ($tab===1) {?>class="active" <?;} ?>><a data-toggle="tab" href="#pass">Passwort ändern</a></li>
            <li <? if ($tab===2) {?>class="active" <?;} ?>><a data-toggle="tab" href="#account">Konto</a></li>
            <li <? if ($tab===3) {?>class="active" <?;} ?>><a data-toggle="tab" href="#ilias">Ilias-Feed</a></li>
        </ul>

        <div class="tab-content">
            <div id="personal" class="tab-pane fade <? if ($tab===0) {?>in active<?;} ?>">
                <div class="container-border">
                    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<? global $router; echo $router->generate('settingsPersonalInformationPost')?>">
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
                                        <input required name="email" id="email" type="email" autofocus class="form-control filled" data-value="<?= $user_info['email']?>" value=<?= $user_info['email']?>>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="firstname" class=" col-sm-6 control-label">
                                        Vorname:
                                    </label>
                                    <div class="col-sm-6">
                                        <input required name="firstname" id="firstname" type="text" autofocus class="form-control filled" data-value="<?= $user_info['firstName']?>" value=<?= $user_info['firstName']?>>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="lastname" class=" col-sm-6 control-label">
                                        Nachname:
                                    </label>
                                    <div class="col-sm-6">
                                        <input required name="lastname" id="lastname" type="text" autofocus class="form-control filled" data-value="<?= $user_info['lastName']?>" value=<?= $user_info['lastName']?>>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="username" class=" col-sm-6 control-label">
                                        Benutzername:
                                    </label>
                                    <div class="col-sm-6">
                                        <input required name="username" id="username" type="text" autofocus class="form-control filled" data-value="<?= $user_info['username']?>" value="<?= $user_info['username']?>" url="<? global $router; echo $router->generate('settingsCheckUserPost');?>">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="birth" class=" col-sm-6 control-label">
                                        Geburtstag:
                                    </label>
                                    <div class="col-sm-6">
                                        <input required name="birth" id="birth" type="date" autofocus class="form-control filled" data-value="<?= $user_info['birthday']?>" value=<?= $user_info['birthday']?>>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="street" class=" col-sm-6 control-label" >
                                        Straße:
                                    </label>
                                    <div class="col-sm-6">
                                        <input required name="street" id="street" type="text" autofocus class="form-control filled" data-value="<?= $user_info['street']?>" value=<?= $user_info['street']?>>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nr" class=" col-sm-6 control-label">
                                        Hausnummer:
                                    </label>
                                    <div class="col-sm-6">
                                        <input required name="nr" id="nr" type="text" autofocus class="form-control filled" data-value="<?= $user_info['housenumber']?>" value=<?= $user_info['housenumber']?>>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="zip" class=" col-sm-6 control-label">
                                        PLZ:
                                    </label>
                                    <div class="col-sm-6">
                                        <select required class="form-control filled" id="zip" name="zip"  data-value="<?= $user_info['zip']?>" >
                                            <option></option>
                                            <?
                                            foreach ($zips as $key => $value) {
                                                ?>
                                                <option <?if ($key==$user_info['zip']) {
                                                    echo 'selected';
                                                } ?>>
                                                    <?= $key ?></option>
                                                <? } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="city" class="col-lg-6 control-label">Ort:</label>
                                    <label class="col-lg-6 control-label" id="city" style="text-align:left;"></label>

                                </div>

                            </div>
                            <div class="col-sm-6 imagepanel">
                                <div >
                                    <img src=<? if ($user_info['picture']) {?>
                                         "/HSWalkieTalkie/src/img/<? print $user_info['picture']."\"";
                                    } else { ?>
                                        "/HSWalkieTalkie/src/img/profile_default.png"
                                    <? } ?>alt="Profilbild">
                                </div>
                                <div class="form-group" id="fileupload">
                                    <label class="control-label">Profilbild ändern:</label>
                                    <input id="userfile" name="userfile" type="file" multiple class="file-loading" upload-url="<? global $router; echo $router->generate('settingsPost');?>">
                                    <button name="deletePicture" id="deletePicture" class="btn btn-default active"><i class="fa fa-trash" aria-hidden="true"></i>Profilbild löschen</button>
                                    <script >
                                        $(document).on('ready', function() {
                                            $("#userfile").fileinput({showCaption: false});
                                        });
                                    </script>
                                </div>
                                <div id="labelpanel">
                                    <label class="label label-danger" id="danger"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row buttonrow">
                            <div class="form-group">
                                <label class="col-sm-6 control-label"></label>
                                <div class="col-sm-4">
                                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                        <div class="btn-group" role="group">
                                            <button type="submit" class="btn btn-primary" id="change-settings" name="change-settings">
                                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                Änderungen speichern
                                            </button>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <a onclick="deleteFunction()" class="btn btn-default active" role="button"><i class="fa fa-times" aria-hidden="true"></i>Änderungen verwerfen</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="pass" class="tab-pane fade <? if ($tab===1) {?>in active<?;} ?>">
                <div class="container-border">
                    <form class="form-horizontal" method="post" action="<? global $router; echo $router->generate('settingsChangePwdPost');?>">
                        <legend>
                            Passwort ändern
                        </legend>
                                <div class="form-group">
                                    <label for="old" class=" col-lg-6 control-label">
                                        Altes Passwort:
                                    </label>
                                    <div class="col-lg-6">
                                        <input required name="old" id="old" type="password" autofocus class="form-control filled" url="<? global $router; echo $router->generate('settingsCheckPwdPost');?>">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="new" class=" col-lg-6 control-label">
                                        Neues Passwort:
                                    </label>
                                    <div class="col-lg-6">
                                        <input required name="new" id="new" type="password" autofocus class="form-control filled">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="verify" class=" col-lg-6 control-label">
                                        Wiederhole Passwort:
                                    </label>
                                    <div class="col-lg-6">
                                        <input required name="verify" id="verify" type="password" autofocus class="form-control filled">
                                    </div>
                                </div>
                        <div class="form-group">
                            <label class="col-lg-6 control-label"></label>
                            <div class="col-lg-4">
                                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                    <div class="btn-group" role="group">
                                        <button id="change-pwd" type="submit" class="btn btn-primary" name="change-pwd"><i class="fa fa-floppy-o" aria-hidden="true"></i>Passwort ändern</button>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <a onclick="deleteFunction()" class="btn btn-default active" role="button"><i class="fa fa-times" aria-hidden="true"></i>Änderungen verwerfen</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div id="account" class="tab-pane fade <? if ($tab===2) {?>in active<?;} ?>">
                <div class="container-border">

                        <legend>
                            Kontodaten ändern
                        </legend>
                    <? $i=0;
                    while ($i<count($bank_info)-1) {
                    ?>
                        <form class="form-horizontal" method="post" action="<? global $router; echo $router->generate('settingsAccountPost');?>">
                        <fieldset>
                            <legend>
                                Konto <?= $i +1?>
                            </legend>

                            <div class="form-group">
                                <label for="iban" class=" col-lg-6 control-label">
                                    IBAN:
                                </label>
                                <div class="col-lg-6">
                                    <input required name="iban" id="iban" type="text" autofocus class="form-control" data-value="<?= $bank_info[$i]['iban']?>" value=<?= $bank_info[$i]['iban']?>>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="bic" class=" col-lg-6 control-label">
                                    BIC:
                                </label>
                                <div class="col-lg-6">
                                    <select required class="form-control bankselect" id="<?= $i ?>" name="bic"  data-value="<?= $bank_info[$i]['bic']?>" >
                                        <option></option>
                                        <?
                                        foreach ($bics as $key => $value) {
                                            ?>
                                            <option <?if ($key==$bank_info[$i]['bic']) {
                                                echo 'selected';
                                            } ?>>
                                                <?= $key ?></option>
                                        <? } ?>
                                    </select>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="bank" class="col-lg-6 control-label">Bankname:</label>
                                <label id="bank<?= $i ?>" name="bank" class="col-lg-6 control-label"  style="text-align:left;"></label>

                            </div>

                            <div class="form-group">
                                <label class="col-lg-6 control-label"></label>
                                <div class="col-lg-4">
                                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                        <div class="btn-group" role="group">
                                            <button type="submit" class="btn btn-primary" name="change-account"><i class="fa fa-floppy-o" aria-hidden="true"></i>Kontodaten ändern</button>
                                        </div>
                                            <input name="ibanalt" type="hidden" value=<?= $bank_info[$i]['iban']?>>
                                            <div class="btn-group" role="group">
                                                <button type="submit" class="btn btn-default active" name="delete-account"><i class="fa fa-trash" aria-hidden="true"></i>Konto löschen</button>
                                            </div>
                                        
                                    </div>
                                </div>
                            </div>

                        </fieldset>
                        </form>
                    <?
                        $i++;
                    }
                    ?>
                    <form class="form-horizontal" method="post" action="<? global $router; echo $router->generate('settingsNewAccountPost');?>">
                        <fieldset>
                            <legend>
                                neues Konto anlegen
                            </legend>

                            <div class="form-group">
                                <label for="iban" class=" col-lg-6 control-label">
                                    IBAN:
                                </label>
                                <div class="col-lg-6">
                                    <input required name="iban" id="iban" type="text" autofocus class="form-control filled">
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="bic" class=" col-lg-6 control-label">
                                    BIC:
                                </label>
                                <div class="col-lg-6">
                                    <select required class="form-control filled" id="bic" name="bic">
                                        <option></option>
                                        <?
                                        foreach ($bics as $key => $value) {
                                            ?>
                                            <option><?= $key ?></option>
                                        <? } ?>
                                    </select>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="bankname" class="col-lg-6 control-label">Bankname:</label>
                                <label id="bankname" class="col-lg-6 control-label"  style="text-align:left;"></label>

                            </div>
                            <div class="form-group">
                                <label class="col-lg-6 control-label"></label>
                                <div class="col-lg-4">
                                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                        <div class="btn-group" role="group">
                                            <button type="submit" class="btn btn-primary" name="new-account"><i class="fa fa-floppy-o" aria-hidden="true"></i>Konto hinzufügen</button>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <a onclick="deleteFunction()" class="btn btn-default active" role="button"><i class="fa fa-times" aria-hidden="true"></i>Änderungen verwerfen</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>



                </div>
            </div>
            <div id="ilias" class="tab-pane fade <? if ($tab===3) {?>in active<?;} ?>">
                <div class="container-border">
                    <form class="form-horizontal" method="post" action="<? global $router; echo $router->generate('settingsChangeIliasPost');?>">
                        <legend>
                            Ilias-Feed Einstellungen
                        </legend>
                        <div class="form-group">
                            <label for="url" class=" col-lg-6 control-label">
                                RSS-Feed-URL:
                            </label>
                            <div class="col-lg-6">
                                <input required name="url" id="url" type="url" autofocus class="form-control filled" data-value="<?= $user_info['feedURL']?>" value=<?= $user_info['feedURL']?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-6 control-label"></label>
                            <div class="col-lg-4">
                                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                    <div class="btn-group" role="group">
                                        <button type="submit" class="btn btn-primary" name="change-ilias"><i class="fa fa-floppy-o" aria-hidden="true"></i>Ilias-Feed ändern</button>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <a onclick="deleteFunction()" class="btn btn-default active" role="button"><i class="fa fa-times" aria-hidden="true"></i>Änderungen verwerfen</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $( document ).ready(function() {
                var zips = <?php echo json_encode($zips); ?>;
                var zip = document.getElementById('zip');
                $('#city').text(zips[zip.value]);
                var bics = <?php echo json_encode($bics); ?>;
                var banks = document.getElementsByClassName('bankselect');
                for (var i = 0; i < banks.length; ++i) {
                    var id = banks[i].getAttribute('id');
                    $('#bank'+id).text(bics[banks[i].value]);
                }
            });
            $('#zip').on('change', function() {
                var zips = <?php echo json_encode($zips); ?>;
                $('#city').text(zips[this.value]);
            });
            $('#bic').on('change', function() {
                var bics = <?php echo json_encode($bics); ?>;
                $('#bankname').text(bics[this.value]);
            });
            $('.bankselect').on('change', function() {
                var bics = <?php echo json_encode($bics); ?>;
                $('#bank'+this.id).text(bics[this.value]);
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
                            $("label[for='username']").text('Benutzername schon vorhanden!');
                            $("label[for='username']").css('color', 'red');
                        } else {
                            $("label[for='username']").text('Benutzername:');
                            $("label[for='username']").css('color', 'black');
                        }

                    });
            });
            $('#old').on('change', function(){
                var pwd=(this).value;
                var url=document.getElementById('old').getAttribute('url');
                $.post(url,
                    {
                        check_pwd: "true",
                        pwd: ""+pwd
                    },
                    function(data){
                        if (data==="    false") {
                            $('#old').focus();
                            $("label[for='old']").text('Passwort falsch!');
                            $("label[for='old']").css('color', 'red');
                        } else {
                            $("label[for='old']").text('Altes Passwort:');
                            $("label[for='old']").css('color', 'black');
                        }
                    });
            });
            $('#verify').on('change', function(){
                if ((this).value!==$('#new').val()) {
                    $('#verify').focus();
                    $("label[for='verify']").text('Passwort stimmt nicht überein!');
                    $("label[for='verify']").css('color', 'red');
                } else {
                    $("label[for='verify']").text('Wiederhole Passwort:');
                    $("label[for='verify']").css('color', 'black');
                }
            });
        </script>
    </div>

