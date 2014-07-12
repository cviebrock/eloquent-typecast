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
	 * Get a plain attribute (not a relationship).
	 *
	 * @param  string  $key
	 * @return mixed
	 * @see Illuminate\Database\Eloquent\Model::getAttributeValue()
	 */
	protected function getAttributeValue($key)
	{
		$value = parent::getAttributeValue($key);

		if (array_key_exists($key, $this->getCastAttributes()))
		{
			if ($value) return $this->castAttribute($key, $value);
		}

		return $value;
	}

	/**
	 * Return the array of attributes to case
	 * @return array
	 */
	protected getCastAttributes()
	{
		return $this->cast;
	}

	/**
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return  mixed
	 */
	protected castAttribute($key, $value)
	{
		$type = $this->cast[$key];
		if ( settype($value, $type) ) {
			return $value;
		}
		throw new EloquentTypecastException("Value could not be cast to type \"$type\"", 1);
	}

}
