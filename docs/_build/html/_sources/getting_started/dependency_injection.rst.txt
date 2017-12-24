Dependency Injection
====================

Dependency Injection manages dependencies for you, as the name implies.

This class can be accessed by extending the base *Controller* class, as the property *crispySystem*.

**! Note !** The parameters in a method or function should be type-hinted in order for this to work.

Create instance
---------------

The *createInstance* method takes the name of a class (including namespace) as argument. It resolves any dependencies for the constructor recursively, and returns the class instance.

This method always creates a new instance, in comparison to the *getInstance* method which will re-use a previously created instance, or creates a new one if it doesn't exist.

Get instance
------------

The *getInstance* method takes the name of a class (including namespace) as argument. It resolves any dependencies for the constructor recursively, and returns the class instance.

This method will re-use a previously created instance, or create a new one if it doesn't exist.

Resolve closure
---------------

The *resolveClosure* method can be used to resolve dependencies in a closure. It takes one parameter, which should be a closure.

Resolve method
--------------

The *resolveMethod* method can be used to resolve a specific method of an instance, it takes the following arguments:

 * instance, the instance of which you want to resolve a method
 * method, the name of the method to resolve
 * parameters **(optional)**, if given, any parameters you give in this associative array, where the key = the name of the parameter, and the value the value, will be used to fill parameter slots. If a value is not given, the default will be used or resolved.