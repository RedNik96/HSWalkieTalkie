    <?php

    require_once '../config.php';
    require_once LIBRARY_PATH . '/dannyvankooten-AltoRouter-39c5009/AltoRouter.php';

    // initialize variables
    $template_data = [];
    global $dbh;

    // handle login
    if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
        if (!Session::check_credentials($_REQUEST['username'], $_REQUEST['password'])) {
            $template_data['message'] = 'Login failed!';
        }
        Template::render('timeline', $template_data);
    }

    #TODO: hier sollte das URL-Routing implementiert werden
    $router = new AltoRouter();
    $router->setBasePath('/src/public');

    $router->map( 'GET', '/', function() {
        echo 'test1';
    });

    $router->map( 'GET', '/[i:id]', function($id) {
        echo 'test2' . $id;
    });

    $match = $router->match();

    if( $match && is_callable( $match['target'] ) ) {
    	call_user_func_array( $match['target'], $match['params'] );
    } else {
    	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    }
