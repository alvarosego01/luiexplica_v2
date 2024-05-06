<?php /* Block Name: Header T1 */
 ?>

<?php

use App\Classes\ACF_declarations;

$block_slug = $context['slug'];

// echo 'block_slug: '.$block_slug;

// $fields = (new ACF_declarations())->get_field_values_byGroup('group_block-header-t1');
$fields = $block_content = get_fields();

$context = Timber::context();
$context['fields'] = $fields;

Timber::render(array('components/blocks/templates/'.$block_slug.'.twig', 'components/blocks/templates/not_found.twig'), $context);

?>
