<?php

return [
	'resources' => [
		'link' => ['url' => '/links'],
	],
	'routes' => [
		['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
		['name' => 'link#jouwlinks', 'url' => '/links/eigenlinks', 'verb' => 'GET'],
		['name' => 'link#percategorie', 'url' => '/links/categorie/{categorie}/{wie}', 'verb' => 'GET'],
		['name' => 'werkdag#zoeken', 'url' => '/werkstaten/zoeken/{cat}/{wie}/{dagenAr}', 'verb' => 'GET'],
		['name' => 'werkstaat_api#preflighted_cors', 'url' => '/api/0.1/{path}',
		'verb' => 'OPTIONS', 'requirements' => ['path' => '.+']]
	]
];
