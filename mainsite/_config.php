<?php

global $project;
$project = 'mainsite';

global $database;
$database = SS_DATABASE_NAME;

// Use _ss_environment.php file for configuration
require_once("conf/ConfigureFromEnv.php");

//GD::set_default_quality(90);
//Image::set_backend("OptimisedGDBackend");
ImagickBackend::set_default_quality(90);
Image::set_backend("ImagickBackend");

Requirements::set_write_js_to_body(false);

if (Director::isLive()) {
	SS_Log::add_writer(new SS_LogEmailWriter('leochenftw@gmail.com'), SS_Log::ERR);
}

i18n::set_locale('zh_CN');
Translatable::set_default_locale('zh_CN');
Translatable::set_allowed_locales(array(
	'zh_CN',
	'en_NZ' //NZ English
	)
);
Object::add_extension('SiteTree', 'Translatable');
Object::add_extension('SiteConfig', 'Translatable');

SS_Cache::set_cache_lifetime('Products', 86400, 1000);
SS_Cache::set_cache_lifetime('Blogs', 86400, 1000);
// If memcached is available, use it, else fall back to file.
if(class_exists('Memcache')){
	SS_Cache::add_backend(
		'primary_memcached',
		'Memcached',
		array(
			'host' => 'localhost',
			'port' => 11211,
			'persistent' => true,
			'weight' => 1,
			'timeout' => 1,
			'retry_interval' => 15,
			'status' => true,
			'failure_callback' => ''
		)
	);
	SS_Cache::pick_backend('primary_memcached', 'any', 10);
}
