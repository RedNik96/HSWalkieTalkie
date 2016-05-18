<?php

class Search
{
    /**
     * @param $content
     * @return Gibt ein Array zurück, welches alle cashtags beinhaltet
     */
    public static function cashtag($content)
    {
        preg_match_all("/\\\$[^ |\r|\$]+/", $content, $treffer);
        return $treffer[0];
    }
}