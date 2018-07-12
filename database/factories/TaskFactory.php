<?php

use Faker\Generator as Faker;
use Faker\Provider\Lorem;

$factory->define(App\Task::class, function (Faker $faker) {
	return [
		'name' => Lorem::text($maxNbChars = 100),
		'priority' => 'Normal',
		'user_id' => '1'
	];
});
