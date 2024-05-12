<?php

namespace App\Classes;

class ACF_declarations
{

    public function __construct()
    {
    }

    function register_custom_blocks()
    {
        add_action('acf/init', function () {
            if (file_exists(get_theme_file_path("/src/constants/blocks.json"))) {

                $blocks = json_decode(file_get_contents(get_theme_file_path("/src/constants/blocks.json")), true);

                if ($blocks) {
                    foreach ($blocks as $block) {

                        $fields_config = [];
                        if(isset($block['fields'])) {
                        foreach ($block['fields'] as $field) {

                            $field_config = $this->handler_fields($field);
                            array_push($fields_config, $field_config);
                        }
                        }

                        $field_group = [
                            'key' => 'group_' . $block['name'],
                            'title' => 'Group fields ' . $block['title'],
                            'fields' => $fields_config,
                            'location' => [
                                [
                                    [
                                        'param' => 'block',
                                        'operator' => '==',
                                        'value' => 'acf/' . $block['name'],
                                    ],
                                ],
                            ]
                        ];

                        if (function_exists('acf_add_local_field_group')) {
                            acf_add_local_field_group($field_group);
                        }
                    }
                }
            }


        });
    }

    function handler_fields($field)
    {

        $field_config = [
            'key' => $field['key'],
            'label' => $field['label'],
            'name' => $field['key'],
            'type' => $field['type'],
            'default_value' => $field['default'] ?? '',
            'instructions' => ''
        ];

        if ($field['type'] === 'select' && isset($field['options'])) {
            foreach ($field['options'] as $option) {
                $field_config['choices'][$option['value']] = $option['label'];
            }
        }

        if ($field['type'] === 'boolean') {

            $field_config['default_value'] = $field['default'];
        }

        if ($field['type'] === 'select_color') {

            if (file_exists(get_theme_file_path("/src/constants/colors.json"))) {

                $colors = json_decode(file_get_contents(get_theme_file_path("/src/constants/colors.json")), true);

                foreach ($colors as $option) {
                    $field_config['choices'][$option['name']] = $option['name'];
                }
                $field_config['choices'][$field_config['default_value']] = $field_config['default_value'];

                $field_config['type'] = 'select';
            }
        }

        if (isset($field['condition'])) {
            $field_config['conditional_logic'] = [
                [
                    [
                        'field' => $field['condition']['key'],
                        'operator' => '==',
                        'value' => $field['condition']['value'],
                    ],
                ],
            ];
        }

        if ($field['type'] === 'flexible_content') {

            $layouts = [];
            foreach ($field['layouts'] as $layout) {
                $sub_fields = [];
                foreach ($layout['sub_fields'] as $sub_field) {
                    $sub_field_config = $this->handler_fields($sub_field);
                    array_push($sub_fields, $sub_field_config);
                }

                $layout_config = [
                    'key' => $layout['key'],
                    'name' => $layout['key'],
                    'label' => $layout['label'],
                    'display' => 'block',
                    'sub_fields' => $sub_fields
                ];

                array_push($layouts, $layout_config);
            }

            $field_config['layouts'] = $layouts;
            $field_config['button_label'] = $field['button_label'];
            $field_config['min'] = 0;
            // $field_config['max'] = $field['max'];

        }

        return $field_config;
    }

    public function set_properties_nav_menu()
    {
        add_action('acf/init', function () {
            $fields = [
                'key' => 'menus_custom_field_settings',
                'title' => 'Menus custom field settings',
                'fields' => [
                    [
                        'key' => 'enable_is_button',
                        'label' => 'Enable is button',
                        'name' => 'enable_is_button',
                        'type' => 'true_false',
                        'default_value' => 0,
                    ],
                    [
                        'key' => 'button_type',
                        'label' => 'Type button',
                        'name' => 'button_type',
                        'type' => 'select',
                        'choices' => [
                            'primary' => 'Primary',
                            'secondary' => 'Secondary',
                        ],
                        'default_value' => 'primary',
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'enable_is_button',
                                    'operator' => '==',
                                    'value' => 1,
                                ],
                            ],
                        ],
                    ],

                ],
                'location' => [
                    [
                        [
                            'param' => 'nav_menu_item',
                            'operator' => '==',
                            'value' => 'all',
                        ],
                    ],
                ]
            ];

            if (function_exists('acf_add_local_field_group')) {
                acf_add_local_field_group($fields);
            }

        });
    }
}
