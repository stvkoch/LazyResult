<?php


class Cache {

    static public function get($params, $call)
    {
        $key = md5(json_encode($params)); //this is a parameters of lazy methods
        //...
        return isset($params[0]['hit']) ? array(11,12,13) : false; //fake cache
    }


    static public function set($params, $call, $result)
    {
        return null;
    }

}

