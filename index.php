    <?php

    require_once 'config.php';

    // initialize variables
    $template_data = array();
    global $dbh;

    // handle login
    if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
        if (!Session::check_credentials($_REQUEST['username'], $_REQUEST['password'])) {
            $template_data['message'] = 'Login failed!';
        }
        Template::render('timeline', $template_data);
    }
    //if (Session::authenticated()) {
        if ($_REQUEST['nav']==='timeline') {
            Template::render('timeline', $template_data);
        } else {
            if ($_REQUEST['nav']==='profile') {
                Template::render('profile', $template_data);
            } else {
                if ($_REQUEST['nav']==='settings') {
                    Template::render('settings', $template_data);
                } else {
                    if (($_REQUEST['nav']==='logout')) {
                        //Session::logout();
                        Template::render('login', $template_data);
                    } else {
                        Template::render('timeline', $template_data);
                    }
                }
            }
        }

    //} else {
        //$template_data['title'] = 'Login';
        //Template::render('login', $template_data);
       // Template::render('timeline', $template_data);
    //}