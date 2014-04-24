LazyResult
==========

Proxy that only run function when result interacts with loop structures. Addicional you can configure before and after callbacks for instance use cache manager results.



[![Build Status LazyResult stvkoch by travis-ci](https://travis-ci.org/stvkoch/LazyResult.svghttps://travis-ci.org/stvkoch/LazyResult)


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