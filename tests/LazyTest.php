<?php

include __DIR__.'/../src/Lazy/Result.php';
include 'Cache.php';
include 'Model.php';

/**
 * 
 */
class LazyTest extends PHPUnit_Framework_TestCase
{

    public function testInstance()
    {
        $instance = new \Lazy\Result(array('Model'));
        $this->assertInstanceOf('\Lazy\Result', $instance);
    }


    public function testLazyCallFunc()
    {
        $proxy = new \Lazy\Result(array('Model', 'find'), array('Cache','get'), array('Cache','set') );
        $lazyResult = $proxy(array(1));

        foreach ($lazyResult as $key => $value) {
            $this->assertEquals('bar', $value);
        }

        $lazyResult = $proxy(array(2,3));
        foreach ($lazyResult as $key => $value) {
            $this->assertContains($value, array('world', 'hello'));
        }
    }


    public function testLazyObject()
    {
        $proxy = new \Lazy\Result(array('Model'), array('Cache','get'), array('Cache','set') );
        $lazyResult = $proxy->one(1);
        $this->assertEquals('bar', $lazyResult->result());
        foreach ($lazyResult as $key => $value) {
            $this->assertEquals('bahbah', $lazyResult->result());
        }

    }

    public function testLazyObjectWithCache()
    {
        $proxy = new \Lazy\Result(array('Model'), array('Cache','get'), array('Cache','set') );
        $lazyResult = $proxy->find(array('hit'=>1));
        foreach ($lazyResult as $key => $value) {
            $this->assertContains($value, array(11,12,13));
        }

        $lazyResult = $proxy->find(array(0,1));
        foreach ($lazyResult as $key => $value) {
            $this->assertContains($value,  array('foo', 'bar'));
        }
    }

    public function testNotRun()
    {
        $proxy = new \Lazy\Result(array('Model') );
        $lazyResult = $proxy->error();
    }


}