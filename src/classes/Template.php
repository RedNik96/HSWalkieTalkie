<?php

class Template {
    public static function render($template, $data)
    {
        // registered passed variables as local variables
        extract($data);

        // load passed template and store contents for usage in layout

        // load and show layout
        if ($template=='login') {
            ob_start();
            include(TEMPLATES_PATH . '/login.php');
            $content_for_layout = ob_get_clean();
        } else {
            ob_start();
            include(TEMPLATES_PATH . '/menuBar.php');
            $content_top = ob_get_clean();
            ob_start();
            include(TEMPLATES_PATH . '/' . $template .'.php');
            $content_for_layout = ob_get_clean();
            if ($template!=='settings') {
                ob_start();
                include(TEMPLATES_PATH . '/rss.php');
                $content_left = ob_get_clean();
                ob_start();
                include(TEMPLATES_PATH . '/rightBar.php');
                $content_right = ob_get_clean();
            }
            
            
        }
        include(TEMPLATES_PATH . "/layout.php");
    }
    public static function addLine($data)
    {
        // registered passed variables as local variables
        extract($data);

        // load passed template and store contents for usage in layout
        ob_start();
        include('templates/list.php');

        self::$tableContent= self::$tableContent . ob_get_clean();

    }
    public static function deleteRows() {
        self::$tableContent=null;
    }
}
