<?php
/**
 * Created by PhpStorm.
 * User: Lew
 * Date: 17/07/2017
 * Time: 19:30
 */

namespace Tests\AppBundle;


trait HelperTrait
{
    static protected $uniqId;
    static protected $uniqIdBis;

    public static function getUniqId()
    {
        if (is_null(self::$uniqId))
        {
            self::$uniqId = uniqid();
        }
        return self::$uniqId;
    }

    public static function getUniqIdBis()
    {
        if (is_null(self::$uniqIdBis))
        {
            self::$uniqIdBis = uniqid();
        }
        return self::$uniqIdBis;
    }
}
