<?php

use App\Classes\generalFunctions;
use Timber\Site;
use Timber\Timber;

/**
 * Class StarterSite
 */
class StarterSite extends Site
{

    public function __construct()
    {
        add_action('after_setup_theme', array($this, 'theme_supports'));
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));

        add_filter('timber/context', array($this, 'add_to_context'));
        add_filter('timber/twig', array($this, 'add_to_twig'));
        add_filter('timber/twig/environment/options', [$this, 'update_twig_environment_options']);

        $this->register_scripts_styles();

        parent::__construct();
    }

    public function register_scripts_styles()
    {

        /*
       wp_enqueue_style('blog.css', get_stylesheet_directory_uri() . '/dist/styles/pages/blog.css', false, rand(111, 9999), 'all');
        wp_enqueue_script('blog.js', get_stylesheet_directory_uri() . '/dist/scripts/pages/blog.js', ['jquery'], rand(111, 9999), 'all');
         */

        add_action('wp_enqueue_scripts', function () {

             wp_enqueue_style('wp-block-library');

            wp_enqueue_style('aos_css', get_stylesheet_directory_uri() . '/resources/css/vendors/aos.css', false, rand(111, 9999), 'all');

            wp_enqueue_style('styles', get_stylesheet_directory_uri() . '/assets/css/main.css', false, rand(111, 9999), 'all');

            wp_enqueue_style('tailwind', get_stylesheet_directory_uri() . '/assets/css/tailwind.css', false, rand(111, 9999), 'all');

            wp_enqueue_script('alpinejs', get_stylesheet_directory_uri() . '/resources/js/vendors/alpinejs.min.js', ['jquery'], rand(111, 9999), 'all');

            wp_enqueue_script('aos_js', get_stylesheet_directory_uri() . '/resources/js/vendors/aos.js', ['jquery'], rand(111, 9999), 'all');

            wp_enqueue_script('mainjs', get_stylesheet_directory_uri() . '/assets/js/main.js', ['jquery'], rand(111, 9999), 'all');

        });

    }

    /**
     * This is where you can register custom post types.
     */
    public function register_post_types()
    {
    }

    /**
     * This is where you can register custom taxonomies.
     */
    public function register_taxonomies()
    {
    }

    /**
     * This is where you add some context
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function add_to_context($context)
    {
        $context['foo']   = 'bar';
        $context['stuff'] = 'I am a value set in your functions.php file';
        $context['notes'] = 'These values are available everytime you call Timber::context();';
        $context['menu']  = Timber::get_menu();
        $context['site']  = $this;

        $context['project_root'] = THEME_ROOT_PATH;
        $context['server_name'] = (new generalFunctions())->get_TypeUrl();

        return $context;
    }

    public function theme_supports()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
        add_theme_support('title-tag');

        /*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
        add_theme_support('post-thumbnails');

        /*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
        add_theme_support(
            'html5',
            array(
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        /*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
        add_theme_support(
            'post-formats',
            array(
                'aside',
                'image',
                'video',
                'quote',
                'link',
                'gallery',
                'audio',
            )
        );

        add_theme_support('menus');
    }

    /**
     * his would return 'foo bar!'.
     *
     * @param string $text being 'foo', then returned 'foo bar!'.
     */
    public function myfoo($text)
    {
        $text .= ' bar!';
        return $text;
    }

    /**
     * This is where you can add your own functions to twig.
     *
     * @param Twig\Environment $twig get extension.
     */
    public function add_to_twig($twig)
    {
        /**
         * Required when you want to use Twig’s template_from_string.
         * @link https://twig.symfony.com/doc/3.x/functions/template_from_string.html
         */
        // $twig->addExtension( new Twig\Extension\StringLoaderExtension() );

        $twig->addFilter(new Twig\TwigFilter('myfoo', [$this, 'myfoo']));

        return $twig;
    }

    /**
     * Updates Twig environment options.
     *
     * @link https://twig.symfony.com/doc/2.x/api.html#environment-options
     *
     * \@param array $options An array of environment options.
     *
     * @return array
     */
    function update_twig_environment_options($options)
    {
        // $options['autoescape'] = true;

        return $options;

    }
}
