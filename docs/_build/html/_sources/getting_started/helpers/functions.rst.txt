Functions
=========

pr
--

The *pr* function is a wrapper for the PHP function *print_r*. It encapsulates the *print_r* function inside *<pre></pre>* tags so you don't have to type those manually.

vd
--

The *vd* function is a wrapper for the PHP function *var_dump*. It encapsulates the *var_dump* function inside *<pre></pre>* tags so you don't have to type those manually.

showPlainError
--------------

This function shows an error, with a darkred title saying *[ ERROR ]*, it has an option to exit after showing the error, by default this is true.

jsonify
-------

This function sets the *Content-Type: application/json* header and returns the given data as a json_encoded string.