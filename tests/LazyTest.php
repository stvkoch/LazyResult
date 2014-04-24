<?php

include __DIR__.'/../src/Lazy/Result.php';
include 'Cache.php';
include 'Model.php';


class LazyTest extends PHPUnit_Framework_TestCase
{

    public function testInstance()
    {
        $instance = new \Lazy\Result(array('Model'));
        $this->assertInstanceOf('\Lazy\Result', $instance);
    }


    public function testLazyCallFunc()
    {
        $finder = new \Lazy\Result(array('Model', 'find'), array('Cache','get'), array('Cache','set') );
        $result = $finder(array(1));

        foreach ($result as $key => $value) {
            $this->assertEquals('bar', $value);
        }

        $result = $finder(array(2,3));
        foreach ($result as $key => $value) {
            $this->assertContains($value, array('world', 'hello'));
        }
    }


    public function testLazyObject()
    {
        $finder = new \Lazy\Result(array('Model', 'find'), array('Cache','get'), array('Cache','set') );
        $result = $finder->one(1);
var_dump($result->result());
        foreach ($result as $key => $value) {
            $this->assertEquals('bar', $value);
        }

    }


}