<?php

namespace App\Classes;

class Popups_Handler{

    public function __construct()
    {
    }

     private function resolve_data()
    {
        $custom_postTypes = new Custom_PostTypes();

        $pages = $custom_postTypes->get_entries_postType('page');

        $a = array(
            'pages' => $pages,
        );

        return $a;
    }

      public function set_popup_behavior_field($field)
    {
        $forms_data = $this->resolve_data();

        $options = array_map(function ($item) {
            return array(
                'label' => $item->post_title,
                'value' => $item->ID
            );
        }, $forms_data['pages']);

        array_push($options, array(
            'label' => 'All pages',
            'value' => 'all_pages'
        ));

        $field = array(
            'type' => 'multiselect',
            'label' => $field['label'],
            'key' => $field['key'],
            'options' => $options,
            'default' => 'all_pages'
        );

        return $field;
    }

}

 ?>