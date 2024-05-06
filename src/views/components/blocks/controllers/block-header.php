<?php /* Block Name: Custom Header */ ?>

<?php

$block_slug = $context['slug'];

$context = Timber::context();

Timber::render(array('components/blocks/templates/'.$block_slug.'.twig', 'components/blocks/templates/not_found.twig'), $context);

?>
