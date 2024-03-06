<?php declare(strict_types=1);

session_start( [ 
	'name' => DEBUG ? 'Session-ID' : '__Secure-Session-ID',
	'cookie_lifetime' => 0,
	'cookie_path' => '/',
	'cookie_domain' => $_SERVER['HTTP_HOST'],
	'cookie_secure' => true,
	'cookie_httponly' => true,
	'cookie_samesite' => 'Strict',
	'sid_length' => 96,
	'sid_bits_per_character' => 6,
	'use_strict_mode' => true,
	'referer_check' => $_SERVER['HTTP_HOST'],
] );