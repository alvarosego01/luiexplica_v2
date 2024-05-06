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
                        foreach ($block['fields'] as $field) {

                            $field_config = $this->handler_fields($field);
                            array_push($fields_config, $field_config);
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

        if ($field['type'] === 'repeater') {

            $field_config['sub_fields'] = [];
            foreach ($field['fields'] as $sub_field) {
                $sub_field_config = $this->handler_fields($sub_field);
                array_push($field_config['sub_fields'], $sub_field_config);
            }
        }

        return $field_config;
    }


}
