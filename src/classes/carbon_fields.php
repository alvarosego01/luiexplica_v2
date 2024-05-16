<?php

namespace App\Classes;

use Carbon_Fields\Block;
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Carbon_Fields;


class CarbonFields
{

    public function __construct()
    {
    }

    function __init()
    {
        add_action('after_setup_theme', array($this, 'carbon_fields_init'));
        add_action('carbon_fields_register_fields', [$this, 'set_properties_nav_menu']);
        add_action('carbon_fields_register_fields', [$this, 'register_theme_ops']);
        add_action('carbon_fields_register_fields', [$this, 'register_custom_postTypes_ops']);
        add_action('carbon_fields_register_fields', [$this, 'register_blocks']);
    }

    public function carbon_fields_init()
    {
        require_once(get_template_directory() . '/vendor/autoload.php');
        \Carbon_Fields\Carbon_Fields::boot();
    }


    function load_theme_settings()
    {
        if (file_exists(get_theme_file_path("/src/constants/theme_options.json"))) {

            $theme_settings = json_decode(file_get_contents(get_theme_file_path("/src/constants/theme_options.json")), true);

            $settings = array();

            foreach ($theme_settings as $setting) {

                $setting_id = $setting['page'];
                $settings[$setting_id] = array();
                foreach ($setting['fields'] as $field) {
                    $control_id = $field['key'];
                    $settings[$setting_id][$control_id] = carbon_get_theme_option($control_id);
                }
            }

            return $settings;
        } else {

            return array();
        }
    }

    function load_custom_postType_settings($post_id)
    {

        if (file_exists(get_theme_file_path("/src/constants/forms_postTypes_options.json"))) {

            $theme_settings = json_decode(file_get_contents(get_theme_file_path("/src/constants/forms_postTypes_options.json")), true);

            $settings = array();

            foreach ($theme_settings as $setting) {
                $settings = $this->get_layer_postType_settings($setting, $post_id);
            }

            return $settings;
        } else {

            return array();
        }
    }

    private function get_layer_postType_settings($post, $post_id, $setting_id = null)
    {


        $settings = array();

        $setting_id = isset($post['post_type']) ? $post['post_type'] : $setting_id;
        $settings = array();

        foreach ($post['fields'] as $field) {
            if ($field['type'] != 'special_post_type') {

                $control_id = $field['key'];
                $settings[$control_id] = carbon_get_post_meta($post_id, $control_id);
            } else {

                $settings['special_post_type'] = $this->get_layer_postType_settings($field, $post_id);
            }
        }




        return $settings;
    }


    public function set_properties_nav_menu()
    {
        Container::make('nav_menu_item', 'Menus custom field settings')
            ->add_fields([
                Field::make('checkbox', 'enable_is_button', 'Enable is button')->set_option_value('yes'),
                Field::make('select', 'button_type', 'Type button')->add_options([
                    'primary' => 'Primary',
                    'secondary' => 'Secondary',
                ])
                    ->set_default_value('primary')
                    ->set_conditional_logic([
                        [
                            'field' => 'enable_is_button',
                            'value' => true,
                            'compare' => '='
                        ]
                    ]),
                Field::make('text', 'icon_type', 'Icon')
                    ->set_attribute('placeholder', "bx bxl-tiktok")
            ]);
    }

    public function register_blocks()
    {

        if (file_exists(get_theme_file_path("/src/constants/blocks.json"))) {

            $json_path = get_theme_file_path('/src/constants/blocks.json');
            if (file_exists($json_path)) {
                $blocks = json_decode(file_get_contents($json_path), true);
                if (isset($blocks) && is_array($blocks) && !empty($blocks)) {
                    foreach ($blocks as $block) {
                        $this->register_block($block);
                    }
                }
            }
        }
    }

    public function register_custom_postTypes_ops()
    {
        if (file_exists(get_theme_file_path("/src/constants/forms_postTypes_options.json"))) {

            $json_path = get_theme_file_path('/src/constants/forms_postTypes_options.json');
            if (file_exists($json_path)) {
                $settings = json_decode(file_get_contents($json_path), true);
                if (isset($settings) && is_array($settings) && !empty($settings)) {
                    foreach ($settings as $setting) {
                        $this->register_custom_postType_ops($setting);
                    }
                }
            }
        }
    }

    public function register_theme_ops()
    {

        if (file_exists(get_theme_file_path("/src/constants/theme_options.json"))) {

            $json_path = get_theme_file_path('/src/constants/theme_options.json');
            if (file_exists($json_path)) {
                $settings = json_decode(file_get_contents($json_path), true);
                if (isset($settings) && is_array($settings) && !empty($settings)) {
                    foreach ($settings as $setting) {
                        $this->register_theme_opt($setting);
                    }
                }
            }
        }
    }

    private function register_custom_postType_ops($setting)
    {

        $fields = [];
        foreach ($setting['fields'] as $field) {

            if ($field['type'] != 'special_post_type') {

                $fields[] = $this->handler_fields($field);
            } else {

                $_fields = $this->set_special_post_type_field($field);
                foreach ($_fields as $f) {
                    $fields[] = $f;
                }
                $this->set_special_post_type_field($field);
            }
        }
        $name = $setting['name'];
        $post_type = $setting['post_type'];

        Container::make('post_meta', $name)
            ->where('post_type', '=', $post_type)
            ->add_fields($fields);
    }

    private function set_special_post_type_field($field)
    {

        $fields = [];
        foreach ($field['fields'] as $_field) {

            switch ($_field['post_type']) {
                case 'forms_post_type':

                    $_aux = null;

                    if ($_field['type'] == 'select_form_cf7') {

                        $_aux = (new Forms_Handler())->set_select_form_cf7_field($_field);
                    }

                    if ($_field['type'] == 'select_form_behavior_cf7') {

                        $_aux = (new Forms_Handler())->set_form_behavior_field($_field);
                    }

                    $carbon_field = $this->handler_fields($_aux);

                    $fields[] = $carbon_field;
                    break;

                default:
                    break;
            }
        }

        return $fields;
    }

    private function register_theme_opt($setting)
    {

        $fields = [];
        foreach ($setting['fields'] as $field) {
            $fields[] = $this->handler_fields($field);
        }
        $name = $setting['name'];

        $basic_options_container = Container::make('theme_options', $name)
            ->add_fields($fields);

        if (isset($setting['icon'])) {
            $basic_options_container->set_icon($setting['icon']);
        }

        if (isset($setting['page'])) {
            $basic_options_container->set_page_file($setting['page']);
        }

        if (isset($setting['parent'])) {
            $basic_options_container->set_page_parent($setting['parent']);
        }
    }

    function handler_fields($field)
    {
        // Define el tipo de campo y la configuración básica
        $field_type = $field['type'];
        $field_label = $field['label'];
        $field_name = $field['key'];
        $default_value = $field['default'] ?? '';
        $placeholder = $field['placeholder'] ?? '';

        $finalType = $field_type;

        if ($field_type === 'select_color') {
            $finalType = 'select';
        }

        if ($field_type === 'url') {
            $finalType = 'text';
        }
        if ($field_type === 'number') {
            $finalType = 'text';
        }

        $carbon_field = Field::make($finalType, $field_name, $field_label);

        if ($field_type === 'image' || $field_type === 'file') {
            $carbon_field->set_value_type('url');
        }

        if ($field_type === 'url') {
            $carbon_field->set_attribute('type', 'url');
        }

        if ($field_type === 'date' && isset($field['format'])) {
            $carbon_field->set_storage_format($field['format']);
        }

        if ($field_type === 'number') {
            $carbon_field->set_attribute('type', 'number')
                ->set_attribute('min', 0);
        }

        if ($field_type === 'select' && isset($field['options'])) {
            $options = [];
            foreach ($field['options'] as $option) {
                $options[$option['value']] = $option['label'];
            }
            $carbon_field->add_options($options);
        }

        if ($field_type === 'multiselect' && isset($field['options'])) {
            $options = [];
            foreach ($field['options'] as $option) {
                $options[$option['value']] = $option['label'];
            }
            $carbon_field->add_options($options);
        }

        if ($field_type === 'checkbox') {
            $carbon_field->set_option_value('yes');
        }

        if ($field_type === 'select_color') {
            if (file_exists(get_theme_file_path("/src/constants/colors.json"))) {
                $colors = json_decode(file_get_contents(get_theme_file_path("/src/constants/colors.json")), true);
                $options = [];
                foreach ($colors as $option) {
                    $options[$option['name']] = $option['name']. ' - ' . $option['color'];
                }
                $options[$default_value] = $default_value;
                $carbon_field = Field::make('select', $field_name, $field_label)
                    ->add_options($options);
                //  ->set_default_value($default_value);
            }

            $field_type = 'select';
        }

        if (isset($field['condition'])) {
            // En Carbon Fields, el manejo de condiciones es un poco diferente y se debe ajustar a su API.
            $carbon_field->set_conditional_logic([
                'relation' => 'AND', // O 'OR' dependiendo de tus necesidades
                [
                    'field' => $field['condition']['key'],
                    'value' => $field['condition']['value'],
                    'compare' => '=',
                ]
            ]);
        }

        if ($field_type === 'icon') {

            $carbon_field->add_dashicons_options()
                ->add_fontawesome_options();
        }


        if (isset($placeholder) && $placeholder != '') {

            $carbon_field->set_attribute('placeholder', $placeholder);
        }

        if ($field_type === 'complex') {

            $carbon_field->set_layout('tabbed-vertical');

            foreach ($field['layouts'] as $layout) {
                $fields = [];
                foreach ($layout['fields'] as $f) {
                    $fields[] = $this->handler_fields($f);
                }
                $carbon_field->add_fields($layout['key'], $layout['label'], $fields);
            }

            if (isset($field['max'])) {

                $carbon_field->set_max($field['max']);
            }
        } else {

            $carbon_field->set_default_value($default_value);
        }

        return $carbon_field;
    }


    private function register_block($block)
    {
        $fields = [];
        foreach ($block['fields'] as $field) {
            $fields[] = $this->handler_fields($field);
        }

        $slug = $block['name'];
        $folder = $block['folder'];
        $name = $block['title'];

        Block::make($name)
            ->add_fields($fields)
            ->set_category($block['category'])
            ->set_icon($block['icon'])
            ->set_keywords(explode(',', $block['keywords']))
            ->set_render_callback(function ($fields, $attributes, $inner_blocks) use ($slug, $folder) {

                if (file_exists(get_theme_file_path("/src/views/components/blocks/controllers/{$folder}/{$slug}.php"))) {
                    $context = [
                        'slug' => $slug,
                        'fields' => $fields
                    ];
                    include(get_theme_file_path("/src/views/components/blocks/controllers/{$folder}/{$slug}.php"));
                }
            });
    }
}
