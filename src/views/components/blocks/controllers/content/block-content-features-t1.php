<?php /* Block Name: Content features T1 */ ?>

<?php

$block_slug = $context['slug'];

$fields = $block_content = get_fields();

$context = Timber::context();
$context['fields'] = $fields;

$rows = get_field('content_features_t1_features' );

$context['test'] = $rows;

Timber::render(array('components/blocks/templates/content/' . $block_slug . '.twig', 'components/blocks/templates/not_found.twig'), $context);

?>
