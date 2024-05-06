<?php

namespace App\Classes;

class BlocksMain
{
    public function __construct()
    {
        add_action('init', [$this, 'register_acf_blocks']);

        (new ACF_declarations())->register_custom_blocks();

    }

    public function blocks_categories()
    {
    }

    public function render_callback($block)
    {
        $slug = str_replace('acf/', '', $block['name']);

        if (file_exists(get_theme_file_path("/src/views/components/blocks/controllers/{$slug}.php"))) {
            $context = ['slug' => $slug];
            include(get_theme_file_path("/src/views/components/blocks/controllers/{$slug}.php"));
        }
    }

    public function register_acf_blocks()
    {

        if (function_exists('acf_register_block')) {

            if (file_exists(get_theme_file_path("/src/constants/blocks.json"))) {

                $blocks = json_decode(file_get_contents(get_theme_file_path("/src/constants/blocks.json")), true);

                if (isset($blocks) && is_array($blocks) && !empty($blocks)) {
                    foreach ($blocks as $block) {
                        acf_register_block(array(
                            'name'              => $block['name'],
                            'title'             => __($block['title']),
                            'description'       => __($block['description']),
                            'render_callback'   => [$this, 'render_callback'],
                            'category'          => $block['category'],
                            'icon'              => $block['icon'],
                            'keywords'          => $block['keywords'],
                        ));

                    }

                }
            }
        }


    }
}
