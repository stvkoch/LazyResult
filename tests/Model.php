<?php

class Model{

    static public function find($params=array())
    {
        $r = array('foo', 'bar', 'world', 'hello', 'you');
        return array_intersect_key($r, array_flip($params));
    }

    public function one($id)
    {
        $r = array('foo', 'bar', 'world');
        return $r[$id];
    }

}
