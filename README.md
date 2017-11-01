# CrispySystem
CrispySystem is a lightweight minimal framework, originally built for the sole purpose of me having to do less during my exams.

## Install
1. `composer require stevenliebregt/crispysystem`
2. Create an index.php with the following content:
   ```php
   <?php
   
   use StevenLiebregt\CrispySystem\CrispySystem;
   use StevenLiebregt\CrispySystem\Routing\Router;
   use StevenLiebregt\CrispySystem\Routing\Route;
   
   define('DEBUG', false); // Set to true to show extra error messages
   
   $root = __DIR__ . '/../'; // Set this to your root, my configuration has a `public` public root
   
   /**
    * Require the composer autoloader
    */

   require $root . 'vendor/autoload.php'; // Set this to the location your composer autoload is in
   
   $crispysystem = new CrispySystem($root);
   
   /**
    * Add routes
    */
   Router::group()->routes(function () {
   
           // Index
           Route::get('/', function () {
               echo 'Hello world!';
           })->setName('home');
   
       });
   
   $crispysystem->run();
   ```
3. Create an `.htaccess` file which rewrites everything to the `index.php` file
4. ????
5. Have fun!