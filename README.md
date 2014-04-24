LazyResult
==========

Proxy that only run function when result interacts with loop structures. Addicional you can configure before and after callbacks, for instance use cache manager results.



[![Build Status LazyResult stvkoch by travis-ci](https://travis-ci.org/stvkoch/LazyResult.svg)]


Example
-------

Proxy Lazy Load for one function or callback:

    $proxy = new \Lazy\Result(array('Model', 'find'));
    $lazyResult = $proxy(array('id'=>1)); //not run yeat
    ....
    foreach($lazyResult as $value) { //find method is now run!
        ....
    }


Proxy Lazy Load object:

    $proxy = new \Lazy\Result(array('Model'));
    $lazyResult = $proxy->find(array('id'=>1)); //not run yeat
    ....
    foreach($lazyResult as $value) { //find method is now run!
        ....
    }



Proxy Lazy Load object/or function with cache:

    $proxy = new \Lazy\Result(array('Model'), array('Cache','get'), array('Cache','set'));
    $lazyResult = $proxy->find(array('id'=>1)); //not run yeat
    ....
    foreach($lazyResult as $value) { //find method is now run! but before Cache::get is run, if Cache::get not return result 'find' method is run and after Cache::set with array('id'=>1) more $result
        ....
    }


Definition
==========


    [$object] new \Lazy\Result(array $callableLazy [, array $beforeCallableLazy] [, array $afterCallableLazy]);



$callableLazy
-------------

\Lazy\Result object registe $callableLazy to run when result() function run. result() run when initialize foreach loop structure.

$callableLazy receive parameters that you pass for you proxy


Example:

    $lazyFunc = function($param1, $param2){...};
    $proxy = new \Lazy\Result($lazyFunc);
    $proxy($param1, $param2);



$beforeCallableLazy
-------------------

When definite, $beforeCallableLazy run before $callableLazy. This is useful if you like add cache manager for your results.

Interface that you need implemented for $beforeCallableLazy:

    boolean $beforeCallableLazy(array $parameters, $callableLazy);



$afterCallableLazy
-------------------

When definite, $afterCallableLazy run after $callableLazy. This is useful if you like set your result on cache manager.

Interface that you need implemented for $afterCallableLazy:

    boolean $afterCallableLazy(array $parameters, $callableLazy, array $resultByCallableLazy);


