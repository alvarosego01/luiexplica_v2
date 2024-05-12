<?php /* Block Name: Content FAQS 1 */ ?>

<?php

$block_slug = $context['slug'];
$fields = $context['fields'];

$context = Timber::context();

$context['fields'] = $fields;

Timber::render(array('components/blocks/templates/content/' . $block_slug . '.twig', 'components/blocks/templates/not_found.twig'), $context);

?>


