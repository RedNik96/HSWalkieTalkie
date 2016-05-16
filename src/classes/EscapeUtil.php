<?php

class EscapeUtil
{
    public static function escape_array(&$variable) {
        foreach ($variable as &$value) {
            if (!is_array($value)) {
                $value = htmlspecialchars($value);
            }
            else {
                $value=escape_array($value);
            }
        }
        //return $variable;
    }

}
?>