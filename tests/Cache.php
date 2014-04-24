<?php


class Cache{

    static public function get($params)
    {
        return isset($params['hit']) ? array(11,12,13) : false;

    }

    static public function set()
    {
        return null;
    }
}

