<?php



class LogoutHandler
{
    public static function logout()
    {
        // destroy old session
        session_destroy();

        // immediately start a new one
        session_start();

        // create new session_id
        session_regenerate_id(true);
        global $router;
        header("Location: " . $router->generate("timeline"));

    }
}