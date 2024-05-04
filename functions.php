<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 */

if (!defined('THEME_ROOT_PATH')) {
    define('THEME_ROOT_PATH', __DIR__);
}

define('LUI_THEME_VERSION', '0.1.1');
define('LUI_THEME_ROOT', str_replace(ABSPATH, '/', dirname(__FILE__)));
define('LUI_THEME_PATH', dirname(__FILE__));
define('LUI_THEME_URI', home_url(LUI_THEME_ROOT));
define('LUI_THEME_HMR_HOST', 'http://localhost:5173');
define('LUI_THEME_HMR_URI', LUI_THEME_HMR_HOST . LUI_THEME_ROOT);
define('LUI_THEME_ASSETS_PATH', LUI_THEME_PATH . '/dist');
define('LUI_THEME_ASSETS_URI', LUI_THEME_URI . '/dist');


// Load Composer dependencies.
require_once THEME_ROOT_PATH . '/vendor/autoload.php';
require_once THEME_ROOT_PATH . '/src/classes/index.php';
require_once THEME_ROOT_PATH . '/src/StarterSite.php';

Timber\Timber::init();

// Sets the directories (inside your theme) to find .twig files.
Timber::$dirname = [ 'templates', 'src/views' ];

new StarterSite();
