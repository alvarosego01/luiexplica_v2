<?php


namespace App\Classes;

class generalFunctions
{

    public function __construct()
    {
    }

    public function get_TypeUrl()
    {
        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            return '/lui';
        } else {
            return '';
        }
    }

    public function get_TypeEnv()
    {
        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            return 'dev';
        } else {
            return 'production';
        }
    }

    public function get_color($color, $default)
    {
        if (!isset($color)) {
            return $default;
        }
        if ($color == '' || $color == 'default') {
            return $default;
        } else {
            return $color;
        }
    }

    public function _get_img($image_id,  $type)
    {

        $image_url = wp_get_attachment_url($image_id);
        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

        if ($type == 'url') {
            return $image_url;
        } else if ($type == 'alt') {
            return $image_alt;
        } else {
            return $image_url;
        }
    }

function minify_html($buffer) {
    $buffer = preg_replace('/>\s+</', '><', $buffer); // Remover espacios en blanco entre etiquetas HTML
    $buffer = preg_replace('/<!--(?!<!)[^\[>].*?-->/', '', $buffer); // Eliminar comentarios HTML
    return $buffer;
}

function start_buffer() {
    ob_start([$this,'minify_html']);
}

function end_buffer() {
    ob_end_flush();
}


    function set_minify()
    {
add_action('template_redirect', [$this, 'start_buffer']);
add_action('shutdown', [$this, 'end_buffer']);

    }
}
