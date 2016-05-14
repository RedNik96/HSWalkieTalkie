    <?php

    require_once '../config.php';
    require_once LIBRARY_PATH . '/dannyvankooten-AltoRouter-39c5009/AltoRouter.php';

    // initialize variables
    $template_data = [];
    global $dbh;

    #TODO: hier sollte das URL-Routing implementiert werden
    $router = new AltoRouter();
    $router->setBasePath('/HSWalkieTalkie/src/public');

    $router->map( 'GET', '/', function() {
        TimelineHandler::get();
    }, 'timeline');

    $router->map( 'GET', '/settings/', function() {
        SettingsHandler::get();
    }, 'settingsGet');  //Über den 4. Parameter (settings) ist der Pfad mit $router->generate('settings') zu bekommen

    $router->map('POST', '/settings/', function () {
        SettingsHandler::post();
    }, 'settingsPost');

    $router->map('POST', '/settings/personalInformation/', function () {
        SettingsHandler::personalInformation();
    }, 'settingsPersonalInformationPost');

    $router->map('POST', '/settings/checkUser/', function () {
        SettingsHandler::checkUser();
    }, 'settingsCheckUserPost');

    $router->map('POST', '/settings/checkPwd/', function () {
        SettingsHandler::checkPwd();
    }, 'settingsCheckPwdPost');

    $router->map('POST', '/settings/account/', function () {
        SettingsHandler::changeAccount();
    }, 'settingsAccountPost');

    $router->map('POST', '/settings/newAccount/', function () {
        SettingsHandler::createAccount();
    }, 'settingsNewAccountPost');

    $router->map('POST', '/settings/changePwd/', function () {
        SettingsHandler::changePwd();
    }, 'settingsChangePwdPost');

    $router->map('POST', '/settings/changeIlias/', function () {
        SettingsHandler::changeIlias();
    }, 'settingsChangeIliasPost');

    $router->map('POST', '/searchData/', function () {
        SearchHandler::getSearchData();
    }, 'searchDataPost');

    $router->map('GET', '/register/', function () {
        Template::render('register', [], array(
            'template_top'      => null,
            'template_right'    => null,
            'template_left'     => null
        ));
    }, 'registrierungGet');  //Über den 4. Parameter (register) ist der Pfad mit $router->generate('register') zu bekommen

    $router->map('POST', '/register/', function () {
      include(CLASSES_PATH . "/handler/registerHandler.php");
    }, 'registrierungPost');

    $router->map('GET', '/profile/', function () {
        ProfileHandler::GET();
    }, 'profile');

    $router->map('POST', '/login/', function() {
        LoginHandler::post();
    }, "loginPost");

    $router->map('GET', '/logout/', function() {
        LogoutHandler::logout();
    }, "logoutGet");

    $router->map('POST', '/newpost/', function() {
        Post::create();
    }, "newpostPost");


    $match = $router->match();

    //Wenn keine Anmeldung vorliegt, soll direkt auf die Login-Seite verlinkt werden
    //Ausnahme: Der Aufruf kommt von bestimmten Seiten, wie z.B.
    // von der Login-Seite auf die Registrierungsseite,
    // von der Registrierungsseite (nach POST) zur RegisterHandler Seite
    // von der Login-Seite (nach Submit der Anmeldedaten)
    if((!LoginHandler::authenticated()) && (!(in_array($match['name'], array('registrierungGet', 'registrierungPost', 'loginPost'))))) {
        //Ist die Url z. B. /HSWalkieTalie/src/public/settings/, aber es liegt noch keine Anmeldung vor, dann soll
        //der Schönheitshalber erst ein Redirect auf /HSWalkieTalkie/src/public/ erfolgen
        if($match['name']!='timeline'){
            header("Location: ".$router->generate("timeline"));
        }
        $template_data = array();
        if(isset($_SESSION['login_failed']) && $_SESSION['login_failed'])
        {
            $template_data['message'] = 'Login failed!';
        }
        //Rendere die login-Seite ohne den standardisierten Seitenaufbau (oben Menubar, links rss_feed etc)
        Template::render("login", $template_data, array(
            'template_top'      => null,
            'template_right'    => null,
            'template_left'     => null
        ));
        die();
    }

    if( $match && is_callable( $match['target'] ) ) {
    	call_user_func_array( $match['target'], $match['params'] );
    } else {
    	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    }
