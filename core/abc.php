<?php

class abc{
    public static $a = 0;

    public static function print() {
        self::$a = self::$a + 10;
        return self::$a;
    }
}