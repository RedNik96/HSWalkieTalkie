    <?php
    require_once '../config.php';
    require_once LIBRARY_PATH . '/dannyvankooten-AltoRouter-39c5009/AltoRouter.php';

    // initialize variables
    $template_data = [];
    global $dbh;

    // Erstellung des Routers und koniguration des BasePath sowie der Routen
    $router = new AltoRouter();
    $router->setBasePath('/HSWalkieTalkie/src/public');

    $router->map( 'GET', '/', function() {
        TimelineHandler::get();
    }, 'timeline');

    $router->map( 'GET', '/settings/[i:tab]/', function($tab) {
        SettingsHandler::get($tab);
    }, 'settingsGet');

    $router->map('POST', '/settings/', function () {
        echo "scheiße";
        die;
    }, 'settingsPost');

    $router->map('POST', '/settings/personalInformation/', function () {
        SettingsHandler::personalInformation();
    }, 'settingsPersonalInformationPost');

    $router->map('POST', '/settings/checkUser/', function () {
        SettingsHandler::checkUser();
    }, 'settingsCheckUserPost');

    $router->map('POST', '/register/checkUser/', function () {
        RegisterHandler::checkUser();
    }, 'registerCheckUserPost');

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

    $router->map('POST', '/search/', function () {
        SearchHandler::search();
    }, 'searchPost');

    $router->map('GET', '/showUser/[*:user]/', function( $user ) {
        $user=str_replace('%C3%A4','ä',$user);
        $user=str_replace('%C3%84','Ä',$user);
        $user=str_replace('%C3%BC','ü',$user);
        $user=str_replace('%C3%9C','Ü',$user);
        $user=str_replace('%C3%B6','ö',$user);
        $user=str_replace('%C3%96','Ö',$user);
        ProfileHandler::showUser($user);
    }, 'showUserGet');

    $router->map('POST', '/showUser/', function () {
        ProfileHandler::showUserPost();
    }, 'showUserPost');

    $router->map('GET', '/notFound/', function () {
        SearchHandler::notFound();
    }, 'notFoundGet');

    $router->map('GET', '/showMoreUser/[*:name]/', function ($name) {
        ProfileHandler::showMoreUser($name);
    }, 'showMoreUserGet');

    $router->map('GET', '/showCashTag/[i:cashtag]/', function ($cashtag) {
        CashTagHandler::get($cashtag);
    }, 'showCashTagGet');

    $router->map('GET', '/register/', function () {
        Template::render('register', [], array(
            'template_top'      => null,
            'template_right'    => null,
            'template_left'     => null
        ));
    }, 'registrierungGet');

    $router->map('POST', '/register/', function () {
        RegisterHandler::regsister();
    }, 'registrierungPost');

    $router->map('GET', '/profile/', function () {
        ProfileHandler::GET();
    }, 'profile');

    $router->map('POST', '/profile/followUser', function () {
        ProfileHandler::followUser();
    }, 'followUserPOST');

    $router->map('POST', '/login/', function() {
        LoginHandler::post();
    }, "loginPost");

    $router->map('GET', '/logout/', function() {
        LogoutHandler::logout();
    }, "logoutGet");

    $router->map('POST', '/newpost/', function() {
        PostHandler::create();
    }, "newpostPost");

    $router->map('POST', '/repost/', function() {
        PostHandler::repost();
    }, "repostPost");

    $router->map('POST', '/vote/', function() {
        PostHandler::vote();
    }, 'votePost');

    $router->map('POST', '/statisticsToggle/', function(){
        StatisticHandler::toggle();
    }, 'statisticsTogglePost');

    $router->map('GET', '/post/[i:id]/', function($id) {
        PostHandler::get($id);
    }, 'viewPostGet');

    $router->map('POST', '/post/[i:id]/', function($id) {
        PostHandler::post($id);
    }, 'viewPostPost');

    // Ermittlung der der aktuellen Route
    $match = $router->match();

    //Wenn keine Anmeldung vorliegt, soll direkt auf die Login-Seite verlinkt werden
    //Ausnahme: Der Aufruf kommt von bestimmten Seiten, wie z.B.
    // von der Login-Seite auf die Registrierungsseite,
    // von der Registrierungsseite (nach POST) zur RegisterHandler Seite
    // von der Login-Seite (nach Submit der Anmeldedaten)
    if((!LoginHandler::authenticated()) && (!(in_array($match['name'], array('registrierungGet', 'registrierungPost', 'loginPost', 'registerCheckUserPost'))))) {
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
        ErrorHandler::showError('Fehler 404: Seite nicht gefunden');
    }
