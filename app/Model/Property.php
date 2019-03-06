<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'address_line_1',
		'address_line_2',
		'city',
		'postcode',
	];

	public $timestamps = false;

	public function getFullAddressAttribute()
	{
		return $this->address_line_1.",".$this->address_line_2.",".$this->city.",".$this->postcode;
	}
}
