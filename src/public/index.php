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
    }, 'registrierung');  //Über den 4. Parameter (register) ist der Pfad mit $router->generate('register') zu bekommen

    $router->map('POST', '/register/', function () {
        include(CLASSES_PATH . "/handler/registerHandler.php");
    });

    $router->map('GET', '/profile/', function () {
        include(CLASSES_PATH . "/handler/ProfileHandler.php");
        ProfileHandler::GET();
    }, 'profile');

    $router->map('POST', '/', function() {
        LoginHandler::post();
    });
    
    $router->map('POST', '/logout/', function() {
        LogoutHandler::logout();
    }, "logout");


    $match = $router->match();
    if((!Session::authenticated()) && (!($match['name'] === 'registrierung'))) {
      if($match['name']!='timeline'){
          header("Location: ".$router->generate("timeline"));
      }
        Template::render("login", array(
            'url' => $router->generate("registrierung")
        ));
        die();
    }
    if( $match && is_callable( $match['target'] ) ) {
    	call_user_func_array( $match['target'], $match['params'] );
    } else {
    	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    }
