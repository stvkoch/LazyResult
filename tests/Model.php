<?php

class Model{

    static public function find($params=array())
    {

        $r = array('foo', 'bar', 'world', 'hello', 'you');
        return array_intersect_key($r, array_flip($params));
    }

    static public function one($id)
    {
        $r = array('foo', 'bar', 'world');
        return $r[$id];
    }

    static public function error()
    {
        throw new Exception("not run");
        
    }

}
