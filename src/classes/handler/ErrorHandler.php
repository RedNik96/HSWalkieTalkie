<?php

/**
 * Class ErrorHandler Ist dafür da die Fehlerseite zu rendern
 */
class ErrorHandler
{
    /**
     * rendert die Fehlerseite ohne die Menubar und die Anzeigen rechts und links
     */
    public static function showError($error="") {
        $templates=array(
            'template_right' => null,
            'template_left' => null,
            'template_top' => null
        );
        $data=array('error' => $error);
        Template::render('error',$data,$templates);
    }
}