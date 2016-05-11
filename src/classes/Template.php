<?php

class Template {
    public static function render($template, $data)
    {
        // registered passed variables as local variables
        $content_left = null;
        $content_right = null;
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
}
