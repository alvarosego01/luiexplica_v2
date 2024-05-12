<?php /* Block Name: Content features T1 */ ?>

<?php

$block_slug = $context['slug'];
$fields = $context['fields'];


if(!function_exists('_aux_get_fontawesome_class')){

function _aux_get_fontawesome_class($icon_name) {

    $brand_icons = ['facebook', 'twitter', 'linkedin', 'github', 'instagram', 'youtube', 'paypal', 'google', "whatsapp"];

    if (in_array($icon_name, $brand_icons)) {
        return 'fab fa-' . $icon_name;
    }

    // Por defecto, devuelve como 'fas'
    return 'fas fa-' . $icon_name;
}

}

foreach ($fields['content_features_1_features'] as &$field) {
    if ($field['feature_type_icon'] === 'icon' && $field['feature_icon']['provider'] === 'fontawesome') {
        $field['feature_icon']['icon'] = _aux_get_fontawesome_class($field['feature_icon']['icon']);
    }
}
unset($field);

$context = Timber::context();

$context['fields'] = $fields;

Timber::render(array('components/blocks/templates/content/' . $block_slug . '.twig', 'components/blocks/templates/not_found.twig'), $context);

?>



