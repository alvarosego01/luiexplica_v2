<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 */

if (!defined('THEME_ROOT_PATH')) {
    define('THEME_ROOT_PATH', __DIR__);
}

if (!defined('THEME_NAME')) {
    define('THEME_NAME', 'lui_explica');
}



// Load Composer dependencies.
require_once THEME_ROOT_PATH . '/vendor/autoload.php';
require_once THEME_ROOT_PATH . '/src/classes/index.php';
require_once THEME_ROOT_PATH . '/src/StarterSite.php';

Timber\Timber::init();

// Sets the directories (inside your theme) to find .twig files.
Timber::$dirname = [ 'templates', 'src/views' ];



new StarterSite();
