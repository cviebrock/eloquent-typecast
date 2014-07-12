<?php namespace Cviebrock\EloquentTypecast;


trait EloquentTypecastTrait {

	/**
	 * Attributes to type cast.  Array key is the attribute name, value is the
	 * PHP variable type to cast to.
	 *
	 * @var array
	 */
	protected $cast = array();


	/**
	 * Augment the "booting" method of the model to add all our typecast-able
	 * attributes to the mutator cache.  This way, they get mutated without
	 * us needing to write a mutator function for each one.
	 *
	 * @return void
	 * @see  Illuminate\Database\Eloquent\Model::boot()
	 */
	protected static function boot()
	{
		parent::boot();

		$class = get_called_class();
		$instance = new $class();

		foreach($instance->getCastAttributes() as $attribute=>$type)
		{
			static::$mutatorCache[$class][] = $attribute;
		}
	}


	/**
	 * Get the value of an attribute using its mutator.  If the attribute
	 * is typecast-able, then return the cast value instead.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return mixed
	 * @see  Illuminate\Database\Eloquent\Model::mutateAttribute()
	 */
	protected function mutateAttribute($key, $value)
	{
		if (array_key_exists($key, $this->getCastAttributes()))
		{
			return $this->castAttribute($key, $value);
		}

		return parent::mutateAttribute($key, $value);
	}


	/**
	 * Return the array of attributes to cast.
	 *
	 * @return array
	 */
	protected function getCastAttributes()
	{
		return $this->cast;
	}


	/**
	 * Cast an attribute to a PHP variable type.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return  mixed
	 */
	protected function castAttribute($key, $value)
	{
		$type = $this->cast[$key];

		try {
			if ( settype($value, $type) ) {
				return $value;
			}
			throw new EloquentTypecastException("Value could not be cast to type \"$type\"", 1);
		} catch (\Exception $e) {
			throw new EloquentTypecastException("Value could not be cast to type \"$type\"", 1);
		}
	}

}
