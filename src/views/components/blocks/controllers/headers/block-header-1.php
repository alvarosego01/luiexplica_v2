<?php /* Block Name: Header T1 */
 ?>

<?php

$block_slug = $context['slug'];
$fields = $context['fields'];

$context = Timber::context();

$context['fields'] = $fields;

Timber::render(array('components/blocks/templates/headers/'.$block_slug.'.twig', 'components/blocks/templates/not_found.twig'), $context);

?>
