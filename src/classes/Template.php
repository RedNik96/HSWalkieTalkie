<?php
class Template {
    public static function render($template_center, $data=array(), $templates=array()) {
        // define valid args and their defaults
        $template_top = 'menuBar';
        $template_left = 'rss';
        $template_right = 'statistics';
        // extraction magic!
        extract( $templates, EXTR_IF_EXISTS );
        // registered passed variables as local variables
        extract($data);
        //Daten für die Statistiken werden geholt --> je nachdem wie der toggle-Button steht
        if ($template_right==='statistics') {
            if (isset($_SESSION['toggle'])&&$_SESSION['toggle']==="true") {
                $stats=StatisticHandler::getAllStats();
            } else {
                $stats=StatisticHandler::getFriendsStats();
            }
        }
        if ($template_left === 'rss'){
            $rss_article=RSSHandler::getRssfeed();
        }
        // top
        if ($template_top == null) {
            $content_top = null;
        } else {
            ob_start();
            include(TEMPLATES_PATH . '/' . $template_top .'.php');
            $content_top = ob_get_clean();
        }

        // left
        if ($template_left == null) {
            $content_left = null;
        } else {
            ob_start();
            include(TEMPLATES_PATH . '/' . $template_left .'.php');
            $content_left = ob_get_clean();
        }
        // right
        if ($template_right == null) {
            $content_right = null;
        } else {
            ob_start();
            include(TEMPLATES_PATH . '/' . $template_right .'.php');
            $content_right = ob_get_clean();
        }

        // center
        ob_start();
        include(TEMPLATES_PATH . '/' . $template_center .'.php');
        $content_for_layout = ob_get_clean();
        
        include(TEMPLATES_PATH . "/layout.php");
    }
}
