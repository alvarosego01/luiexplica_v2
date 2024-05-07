<?php /* Block Name: CTA texts and button T1 */ ?>


<?php

$block_slug = $context['slug'];

$fields = $block_content = get_fields();

$context = Timber::context();
$context['fields'] = $fields;

Timber::render(array('components/blocks/templates/ctas/' . $block_slug . '.twig', 'components/blocks/templates/not_found.twig'), $context);

?>
