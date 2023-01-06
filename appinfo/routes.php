<?php

return [
	'resources' => [
		'link' => ['url' => '/links'],
		'uitconfig' => ['url' => '/uitconf'],
	],
	'routes' => [
		['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
		['name' => 'link#jouwlinks', 'url' => '/links/eigenlinks', 'verb' => 'GET'],
		['name' => 'link#percategorie', 'url' => '/links/categorie/{categorie}/{wie}/{groep}', 'verb' => 'GET'],
		['name' => 'link#linkbij', 'url' => '/links/werklinkbij/{note}', 'verb' => 'PUT'],
		['name' => 'link#werklinks', 'url' => '/links/werklink/{wie}', 'verb' => 'GET'],
		['name' => 'link#afgeschermde', 'url' => '/links/afgeschermd/{wie}', 'verb' => 'GET'],
		['name' => 'werkdag#zoeken', 'url' => '/werkstaten/zoeken/{cat}/{wie}/{dagenAr}', 'verb' => 'GET'],
		['name' => 'werkstaat_api#preflighted_cors', 'url' => '/api/0.1/{path}',
		'verb' => 'OPTIONS', 'requirements' => ['path' => '.+']]
	]
];
