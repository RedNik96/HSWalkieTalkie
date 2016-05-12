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
        include(CLASSES_PATH . "/handler/TimelineHandler.php");
    }, 'timeline');
    
    $router->map( 'GET', '/user/', function() {
        echo 'profil';
    });

    $router->map( 'GET', '/user/[i:id]', function($id) {
        echo 'user' . $id;
    });

    $router->map( 'GET', '/settings/', function() {
        SettingsHandler::get();
    }, 'settings');  //Über den 4. Parameter (settings) ist der Pfad mit $router->generate('settings') zu bekommen

    $router->map('POST', '/settings/', function () {
        SettingsHandler::post();
    });

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
        include(CLASSES_PATH . "/handler/ProfileHandler.php");
        ProfileHandler::GET();
    }, 'profile');

    $router->map('POST', '/login/', function() {
        LoginHandler::post();
    }, "loginPost");
    
    $router->map('GET', '/logout/', function() {
        LogoutHandler::logout();
    }, "logoutGet");

    $match = $router->match();
    if((!Session::authenticated()) && (!(in_array($match['name'], array('registrierungGet', 'registrierungPost', 'loginPost'))))) {
      if($match['name']!='timeline'){
          header("Location: ".$router->generate("timeline"));
      }
        Template::render("login");
        die();
    }
    
    if( $match && is_callable( $match['target'] ) ) {
    	call_user_func_array( $match['target'], $match['params'] );
    } else {
    	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    }
