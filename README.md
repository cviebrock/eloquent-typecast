# EloquentTypecast

A trait that allows a Laravel project's Eloquent models to cast attribute values to native PHP variable types.  Useful if you've ever complained about _everything_ being returned from your database as a string.


## Installation

In your project's composer.json file:

```json
"require": {
    "cviebrock/eloquent-typecast": "1.*"
}
```

In your project's models:

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


## Usage

Anytime you request an attribute listed in the `$cast` array, it will be converted from the (usually) string that your database returned into a the native PHP variable type you specified.

The keys of the `$cast` array are the attribute (i.e. column) names, and the values are the types you want to cast to.  Anything supported by PHP's [settype()](http://php.net/manual/en/function.settype.php) function is valid ... although casting to arrays, objects, or null could be problematic.

## Note

Because of the way the trait works, you should make sure that your `$cast` array does not include:

- relations
- attributes for which you already have a custom mutator
- attributes using Eloquent's [date mutation](http://laravel.com/docs/eloquent#date-mutators)


## Problems or Suggestions?

Stick 'em on git and make a merge request, if possible.  Kids love merge requests!

[Colin Viebrock](mailto:colin@viebrock.ca)
