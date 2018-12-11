<?php

namespace App\Handlers;

/*
 * Demo:
 * 腾讯地图坐标拾取器
 * https://lbs.qq.com/tool/getpoint/index.html
 *
 * $handler = new CoordinateHandler();
 *
 * $a = $handler->getCoordinate(36.087491, 120.374086); // 山东省青岛市市北区山东路
 * $b = $handler->getCoordinate(36.089052, 120.373957); // 山东省青岛市市北区山东路109号
 *
 * $angle = $handler->getAngle($a, $b);
 * $distance = $handler->getDistance($a, $b);
 */
class CoordinateHandler
{
    // get a new instantiation of class Coordinate from a coordinate-pair (latitude, longitude).
    public function getCoordinate($latitudeAngular, $longitudeAngular)
    {
        return new Coordinate($latitudeAngular, $longitudeAngular);
    }

    // get the angle from Coordinate A to Coordinate B.
    public function getAngle(Coordinate $a, Coordinate $b)
    {
        $dLatitudeDistance = ($b->latitudeRadian - $a->latitudeRadian) * $a->localEarthRadius;
        $dLongitudeDistance = ($b->longitudeRadian - $a->longitudeRadian) * $a->latitudeRadius;
        if ($dLatitudeDistance == 0) {
            return null;
        }

        $angle = atan(abs($dLongitudeDistance / $dLatitudeDistance)) * 180 / M_PI;
        $dLatitudeAngular = $b->latitudeAngular - $a->latitudeAngular;
        $dLongitudeAngular = $b->longitudeAngular - $a->longitudeAngular;

        if ($dLongitudeAngular > 0 && $dLatitudeAngular <= 0) {
            $angle = (90 - $angle) + 90;
        } else if ($dLongitudeAngular <= 0 && $dLatitudeAngular < 0) {
            $angle = $angle + 180;
        } else if ($dLongitudeAngular < 0 && $dLatitudeAngular >= 0) {
            $angle = (90 - $angle) + 270;
        }

        return $angle;
    }

    // get the distance from Coordinate A to Coordinate B.
    public function getDistance(Coordinate $a, Coordinate $b)
    {
        $dLatitudeRadian = $b->latitudeRadian - $a->latitudeRadian;
        $dLongitudeRadian = $b->longitudeRadian - $a->longitudeRadian;
        if ($dLatitudeRadian == 0) {
            return 0;
        }

        //google maps里面实现的算法
        $distance = 2 * asin(sqrt(pow(sin($dLatitudeRadian / 2), 2) + cos($a->latitudeRadian) * cos($b->latitudeRadian) * pow(sin($dLongitudeRadian / 2), 2))); //google maps里面实现的算法
        $distance = $distance * Coordinate::EARTH_RADIUS;

        return $distance;
    }
}

// coordinate: 坐标; latitude: 纬度; longitude: 经度.
// landscape: 横向打印的; portrait: 纵向打印的.
// vertical,perpendicular,upright,erect,plumb: 垂直的; horizontal: 水平的.
class Coordinate
{
    const EARTH_RADIUS = 6378137; // 地球半径 人类规定 (单位：m)
    const EQUATORIAL_RADIUS = 6378137; // 赤道半径 (单位：m)
    const POLAR_RADIUS = 6356725; // 极半径 (单位：m)

    public $latitudeAngular;
    public $latitudeRadian;
    public $latitudeDegree;
    public $latitudeMinute;
    public $latitudeSecond;

    public $longitudeAngular;
    public $longitudeRadian;
    public $longitudeDegree;
    public $longitudeMinute;
    public $longitudeSecond;

    public $localEarthRadius;
    public $latitudeRadius;

    // Constant M_PI is equal to pi() ...
    public function __construct($latitudeAngular, $longitudeAngular)
    {
        $this->latitudeAngular = $latitudeAngular;
        $this->latitudeRadian = $latitudeAngular * M_PI / 180;
        $this->latitudeDegree = (int)$latitudeAngular;
        $this->latitudeMinute = (int)(($latitudeAngular - $this->latitudeDegree) * 60);
        $this->latitudeSecond = ($latitudeAngular - $this->latitudeDegree - $this->latitudeMinute / 60) * 3600;

        $this->longitudeAngular = $longitudeAngular;
        $this->longitudeRadian = $longitudeAngular * M_PI / 180;
        $this->longitudeDegree = (int)$longitudeAngular;
        $this->longitudeMinute = (int)(($longitudeAngular - $this->longitudeDegree) * 60);
        $this->longitudeSecond = ($longitudeAngular - $this->longitudeDegree - $this->longitudeMinute / 60) * 3600;

        $this->localEarthRadius = self::POLAR_RADIUS + (self::EQUATORIAL_RADIUS - self::POLAR_RADIUS) * (90 - $latitudeAngular) / 90;
        $this->latitudeRadius = $this->localEarthRadius * cos($this->latitudeRadian);
    }
}
