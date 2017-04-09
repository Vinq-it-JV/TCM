<?php
namespace AppBundle\Services;

class ClassHelper
{
    public function __construct()
    {
    }

    public function info()
    {
        return "This class holds various functions that can be used in the controllers";
    }

    public function isValidDate($date)
    {
        $d = \DateTime::createFromFormat('d-m-Y', $date);
        return $d && $d->format('d-m-Y') === $date;
    }

    public function getBooleanValue($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function debug($data)
    {
        print '<pre>';
        var_dump($data);
        Print '</pre>';
    }

}