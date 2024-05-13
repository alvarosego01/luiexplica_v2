<?php

namespace App\Classes;


class Forms_Handler
{

    public function __construct()
    {
    }

    public function get_form_by_pageId($page_id)
    {

        $custom_postTypes = new Custom_PostTypes();
        $forms = $custom_postTypes->get_entries_postType('forms_post_type');

        $forms = array_map(function ($item) {

            $fields = (new CarbonFields)->load_custom_postType_settings($item->ID);

            return array(
                'ID' => $item->ID,
                'post_title' => $item->post_title,
                'post_name' => $item->post_name,
                'post_type' => $item->post_type,
                'post_fields' => $fields
            );
        }, $forms);

        $result = array();

        foreach ($forms as $post_type) {
            if (isset($post_type['post_fields']['special_post_type']['form_behavior_cf7'])) {
                $form_behavior_cf7 = $post_type['post_fields']['special_post_type']['form_behavior_cf7'];
                if (in_array($page_id, $form_behavior_cf7) || in_array('all_pages', $form_behavior_cf7)) {
                    $result[] = $post_type;
                }
            }
        }

        return $result;

    }

    private function get_all_cf7()
    {

        $args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
        $rs = array();
        if ($data = get_posts($args)) {
            foreach ($data as $key) {
                $rs[] = array(
                    'id' => $key->ID,
                    'title' => $key->post_title
                );
            }
        } else {
            $rs['0'] = esc_html__('No Contact Form found', 'text-domanin');
        }

        return $rs;
    }

    private function resolve_forms_data()
    {
        $custom_postTypes = new Custom_PostTypes();
        $pages = $custom_postTypes->get_entries_postType('page');
        $all_cf7 = $this->get_all_cf7();

        $a = array(
            'pages' => $pages,
            'cf7' => $all_cf7
        );

        return $a;
    }

    public function set_select_form_cf7_field($field)
    {
        $forms_data = $this->resolve_forms_data();

        $field = array(
            'type' => 'select',
            'label' => $field['label'],
            'key' => $field['key'],
            'options' => array_map(function ($item) {
                return array(
                    'label' => $item['title'],
                    'value' => $item['id']
                );
            }, $forms_data['cf7']),
            'condition' => $field['condition']
        );

        return $field;
    }

    public function set_form_behavior_field($field)
    {
        $forms_data = $this->resolve_forms_data();

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
