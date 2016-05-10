    <?php

    require_once '../config.php';
    require_once LIBRARY_PATH . '/dannyvankooten-AltoRouter-39c5009/AltoRouter.php';

    // initialize variables
    $template_data = [];
    global $dbh;

    // handle login
    if (isset($_REQUEST['username']) && isset($_REQUEST['password']) && false) {
        if (!Session::check_credentials($_REQUEST['username'], $_REQUEST['password'])) {
            $template_data['message'] = 'Login failed!';
        }
        Template::render('timeline', $template_data);
    }

    #TODO: hier sollte das URL-Routing implementiert werden
    $router = new AltoRouter();
    $router->setBasePath('/HSWalkieTalkie/src/public');

    $router->map( 'GET', '/', function() {
        if (Session::authenticated()) {
            Template::render("login", array());
        } else {
            Template::render("timeline", array());
        }
        $template_data['muell'] = 'sdf';
        Template::render('timeline', $template_data);
    }, 'timeline');  //Über den 4. Parameter (timeline) ist der Pfad mit $router->generate('timeline') zu bekommen

    $router->map( 'GET', '/user/', function() {
        echo 'profil';
    });

    $router->map( 'GET', '/user/[i:id]', function($id) {
        echo 'user' . $id;
    });
    $router->map( 'GET', '/settings', function() {
        $template_data['muell']='sdf';
        Template::render('settings', $template_data);
    }, 'settings');  //Über den 4. Parameter (settings) ist der Pfad mit $router->generate('settings') zu bekommen

    $router->map('GET', '/register/', function () {
        Template::render('register', array());
    }, 'registrierung');  //Über den 4. Parameter (register) ist der Pfad mit $router->generate('register') zu bekommen

    $router->map('POST', '/register/', function () {
        include(CLASSES_PATH . "/handler/registerHandler.php");
    });

    $match = $router->match();

    if( $match && is_callable( $match['target'] ) ) {
    	call_user_func_array( $match['target'], $match['params'] );
    } else {
    	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    }
