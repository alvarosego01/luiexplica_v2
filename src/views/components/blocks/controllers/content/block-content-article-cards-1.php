<?php /* Block Name: Content Article cards 1 */ ?>

<?php

$block_slug = $context['slug'];
$fields = $context['fields'];


if( count($fields['article_cards_1_sections']) > 3 ){

}

$context = Timber::context();

$context['fields'] = $fields;

Timber::render(array('components/blocks/templates/content/' . $block_slug . '.twig', 'components/blocks/templates/not_found.twig'), $context);

?>
