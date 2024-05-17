<?php

namespace App\Classes;


class Custom_PostTypes
{

    //constructor
    public function __construct()
    {
    }

    public function __init()
    {
        add_action('init', [$this, 'register_post_types']);
    }

    public function register_post_types()
    {
        $this->popups_postType();
        $this->forms_postType();
    }

    private function popups_postType()
    {

        $labels = array(
            'name'                => _x('Popups', 'Post Type General Name', 'escala'),
            'singular_name'       => _x('Popup', 'Post Type Singular Name', 'escala'),
            'menu_name'           => __('Popups', 'escala'),
            'parent_item_colon'   => __('Principal Popup', 'escala'),
            'all_items'           => __('Todos los Popups', 'escala'),
            'view_item'           => __('Ver Popup', 'escala'),
            'add_new_item'        => __('Añadir nuevo Popup', 'escala'),
            'add_new'             => __('Añadir nuevo', 'escala'),
            'edit_item'           => __('Editar Popup', 'escala'),
            'update_item'         => __('Actualizar Popup', 'escala'),
            'search_items'        => __('Buscar Popup', 'escala'),
            'not_found'           => __('No se encuentra', 'escala'),
            'not_found_in_trash'  => __('No se encuentra en papelera', 'escala'),
        );

        $args = array(
            'label'               => __('Popups', 'escala'),
            'description'         => __('Popup nuevos y revisiones', 'escala'),
            'labels'              => $labels,
            'supports'            => array('title', 'author', 'revisions'),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 4,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest' => false,
            "menu_icon" => "dashicons-cover-image"
        );

        register_post_type('popups_post_type', $args);
    }

    private function forms_postType()
    {

        $labels = array(
            'name'                => _x('Forms', 'Post Type General Name', 'escala'),
            'singular_name'       => _x('Form', 'Post Type Singular Name', 'escala'),
            'menu_name'           => __('Forms', 'escala'),
            'parent_item_colon'   => __('Principal Form', 'escala'),
            'all_items'           => __('Todos los Forms', 'escala'),
            'view_item'           => __('Ver Form', 'escala'),
            'add_new_item'        => __('Añadir nuevo Form', 'escala'),
            'add_new'             => __('Añadir nuevo', 'escala'),
            'edit_item'           => __('Editar Form', 'escala'),
            'update_item'         => __('Actualizar Form', 'escala'),
            'search_items'        => __('Buscar Form', 'escala'),
            'not_found'           => __('No se encuentra', 'escala'),
            'not_found_in_trash'  => __('No se encuentra en papelera', 'escala'),
        );

        $args = array(
            'label'               => __('Forms', 'escala'),
            'description'         => __('Form nuevos y revisiones', 'escala'),
            'labels'              => $labels,
            'supports'            => array('title', 'author', 'revisions'),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 4,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest' => false,
            "menu_icon" => "dashicons-list-view"

        );

        register_post_type('forms_post_type', $args);
    }

    public function get_entries_postType($post_type)
    {
        $query = [
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'desc'
        ];
        $query = get_posts($query);

        return $query;
    }
}
