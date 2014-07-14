# EloquentTypecast

A trait that allows a Laravel project's Eloquent models to cast attribute values to native PHP variable types.


[![Latest Stable Version](https://poser.pugx.org/cviebrock/eloquent-typecast/v/stable.png)](https://packagist.org/packages/cviebrock/eloquent-typecast)
[![Total Downloads](https://poser.pugx.org/cviebrock/eloquent-typecast/downloads.png)](https://packagist.org/packages/cviebrock/eloquent-typecast)

* [Background](#background)
* [Installation and Requirements](#installation)
* [Usage](#usage)
* [Notes](#notes)
* [Bugs, Suggestions and Contributions](#bugs)
* [Copyright and License](#copyright)


- - -


<a name="background"></a>
## Background: Why Do I Need This?

For some database drivers, all the attributes you get back from a query are returned as strings, even when the underlaying column-type is _INTEGER_ or _FLOAT_ or _BOOLEAN_.

Rather than have to use these "integer-looking" strings, etc., and rely on PHP's type-juggling, this trait will cast those attribute values to the proper native PHP variable type automagically for you.

> Note: I believe if you are using the mysqlnd drivers in your PHP installation, then you don't need this trait as mysqlnd handles this type casting for you.  Try it out by doing a `var_dump($model->getKey())`.  If it shows that the value is an integer, you don't need this package.  If it shows it's a string, read on.



<a name="installation"></a>
## Installation & Requirements

In your project's composer.json file:

```json
"require": {
    "cviebrock/eloquent-typecast": "1.*"
}
```

In your project's models (or your own base model):

```php
use Cviebrock\EloquentTypecast\EloquentTypecastTrait;

class MyModel {

    use EloquentTypecastTrait;

    // Define the attributes you want typecast here
    protected $cast = array(
        'id'         => 'integer',
        'price'      => 'float',
        'is_awesome' => 'boolean'
    );

    ...

}
```

That's it.  No service providers or facades required.  Because it's a trait, however, you will need to be running PHP 5.4 or later.



<a name="usage"></a>
## Usage

Anytime you request an attribute listed in the `$cast` array, it will be converted from the (usually) string that your database returned into a the native PHP variable type you specified.

The keys of the `$cast` array are the attribute (i.e. column) names, and the values are the types you want to cast to.  Anything supported by PHP's [settype()](http://php.net/manual/en/function.settype.php) function is valid ... although casting to arrays, objects, or null could be problematic.

If you set the `$castOnSet` property on your model to `true`, then setting an attribute that's in the `$cast` array will typecast that value before setting it.  For example:

```php
class MyModel {

    use EloquentTypecastTrait;

    protected $castOnSet = true;

    protected $cast = array(
        'price'      => 'float',
    );

}

$myModel = MyModel::find(1);

$price = Input::get('price');  // this will be a string
$myModel->price = $price;      // the string is cast to a float before setting;
```

In general, this setting isn't really necessary as Laravel and most databases will handle the string-to-column-type conversion for you on save.  However, maybe there are cases where it's useful, so it's added for "feature completion".



<a name="notes"></a>
## Notes

Because of the way the trait works, you should make sure that your `$cast` array does not include:

- relations
- attributes for which you already have a custom mutator
- attributes using Eloquent's [date mutation](http://laravel.com/docs/eloquent#date-mutators)

`$model->toArray()` triggers the casting as well.  `$model->getAttributes()`, however, does not.  It returns the raw values from the query (not even the date mutation).



<a name="bugs"></a>
## Bugs, Suggestions and Contributions

Please use Github for bugs, comments, suggestions.

1. Fork the project.
2. Create your bugfix/feature branch and write your (well-commented) code.
3. Create unit tests for your code:
    - Run `composer install --dev` in the root directory to install required testing packages.
    - Add your test methods to `eloquent-typecast/tests/TypecastTest.php`.
    - Run `vendor/bin/phpunit` to the new (and all previous) tests and make sure everything passes.
3. Commit your changes (and your tests) and push to your branch.
4. Create a new pull request against the `develop` branch.

**Please note that you must create your pull request against the `develop` branch.**



<a name="copyright"></a>
## Copyright and License

Eloquent-Typecast was written by Colin Viebrock and released under the MIT License. See the [LICENSE.md](./LICENSE.md) file for details.

Copyright 2014 Colin Viebrock
