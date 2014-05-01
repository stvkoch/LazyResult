<?php

class Model
{

    public static function find($params = array())
    {

        $r = array('foo', 'bar', 'world', 'hello', 'you');
        return array_intersect_key($r, array_flip($params));
    }

    public static function one($id)
    {
        $r = array('foo', 'bar', 'world');
        return $r[$id];
    }

    public static function oneObj($id)
    {
        $r = new stdClass;
        $r->value = $id;
        return $r;
    }

    public static function error()
    {
        throw new Exception("not run");
    }
}
