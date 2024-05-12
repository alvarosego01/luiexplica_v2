<?php

namespace App\Classes;

use WP_Customize_Image_Control;

class CustomizeControls
{
    public function __construct()
    {
        $this->customize_register();
    }

    function customize_register()
    {
        add_action('customize_register', [$this, 'customize_params']);
    }

    function load_customizer_settings()
    {
        if (file_exists(get_theme_file_path("/src/constants/customize_params.json"))) {

            $customizer_data = json_decode(file_get_contents(get_theme_file_path("/src/constants/customize_params.json")), true);

            $settings = array();

            foreach ($customizer_data as $section) {
                $section_id = $section['id'];
                $settings[$section_id] = array();

                foreach ($section['controls'] as $control) {
                    $control_id = $control['id'];
                    $settings[$section_id][$control_id] = get_theme_mod($control_id);
                }
            }

            return $settings;

        } else {

            return array();

        }
    }

    function customize_params($wp_customize)
    {
        if (file_exists(get_theme_file_path("/src/constants/customize_params.json"))) {
            $customizes = json_decode(file_get_contents(get_theme_file_path("/src/constants/customize_params.json")), true);

            if (isset($customizes) && is_array($customizes) && !empty($customizes)) {
                foreach ($customizes as $param) {
                    $section = $param['id'];
                    $title = $param['title'];
                    $controls = $param['controls'];

                    // Verifica si la sección ya existe
                    if (!$wp_customize->get_section($section)) {
                        $wp_customize->add_section($section, array(
                            'title'    => __($title, THEME_NAME),
                            'priority' => 30,
                        ));
                    }

                    if (isset($controls) && is_array($controls) && !empty($controls)) {
                        foreach ($controls as $control) {
                            $this->set_setting_byType($control, $section, $wp_customize);
                        }
                    }
                }
            }
        }
    }

    function set_setting_byType($control, $section, $wp_customize)
    {
        if (!$control) {
            return;
        }

        $id = $control['id'];
        $label = $control['label'];
        $type = $control['type'];
        $default = isset($control['default'])? $control['default'] : '';

        // Verifica si la configuración ya existe
        if (!$wp_customize->get_setting($id)) {
            switch ($type) {

                case 'image':
                    $wp_customize->add_setting($id, array(
                        'default' => $default,
                        'transport' => 'refresh',
                    ));

                    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $id . '_control', array(
                        'label'    => __($label, THEME_NAME),
                        'section'  => $section,
                        'settings' => $id,
                    )));
                    break;

                case 'string':
                    $wp_customize->add_setting($id, [
                        'default' => $default,
                        'transport' => 'refresh', // o 'postMessage' si estás implementando cambios en vivo con JS
                    ]);

                    $wp_customize->add_control($id, [
                        'label'    => __($label, THEME_NAME),
                        'section'  => $section,
                        'settings' => $id,
                        'type'     => 'text'
                    ]);

                case 'url':
                    $wp_customize->add_setting($id, [
                        'default' => $default,
                        'transport' => 'refresh', // o 'postMessage' si estás implementando cambios en vivo con JS
                    ]);

                    $wp_customize->add_control($id, [
                        'label'    => __($label, THEME_NAME),
                        'section'  => $section,
                        'settings' => $id,
                        'type'     => 'url'
                    ]);

                    break;

                default:
                    // Agrega aquí más tipos según sea necesario
                    break;
            }
        }
    }
}
