<?php

namespace App\Classes;

class Menus_Handler
{


    public function __construct()
    {
    }

    function get_menu_items($menu_location)
    {
        $menu_items_data = [];
        $locations = get_nav_menu_locations();

        if (isset($locations[$menu_location])) {
            $menu = wp_get_nav_menu_object($locations[$menu_location]);
            $menu_items = wp_get_nav_menu_items($menu->term_id);

            foreach ($menu_items as $item) {
                // Obtener los campos ACF asociados a cada ítem de menú
                $acf_fields = get_fields($item->ID);

                $menu_items_data[] = [
                    'title' => $item->title,
                    'url' => $item->url,
                    'classes' => implode(' ', $item->classes),
                    'children' => [],
                    'menu_item_parent' => $item->menu_item_parent,
                    'ID' => $item->ID,
                    'properties' => $acf_fields  // Añadir los campos ACF al array
                ];
            }
        }

        // Organizar los elementos para manejar submenús
        if (!empty($menu_items_data)) {
            $menu_items_data = $this->build_menu_tree($menu_items_data);
        }

        return $menu_items_data;
    }

    function build_menu_tree(array &$elements, $parentId = 0)
    {
        $branch = array();

        foreach ($elements as &$element) {
            if ($element['menu_item_parent'] == $parentId) {
                $children = $this->build_menu_tree($elements, $element['ID']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['ID']] = $element;
                unset($element);
            }
        }
        return $branch;
    }
}
