<?php /* Block Name: CTA countdown T1 */ ?>


<?php

$block_slug = $context['slug'];
$fields = $context['fields'];

$context = Timber::context();

$context['fields'] = $fields;

$context['block_id'] = 'block_' . rand();

Timber::render(array('components/blocks/templates/ctas/' . $block_slug . '.twig', 'components/blocks/templates/not_found.twig'), $context);

?>
