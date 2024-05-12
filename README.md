
<p align="center">
    <img src="./screenshot.png" height="250">
</p>

# A custom Timber Theme
<h3 align="left"> Developed by:
    <a href="https://www.linkedin.com/in/alvarosego01/" target="_blank">
        Álvaro Segovia
    </a>
    -
    <a href="https://luiexplica.com" target="_blank">
        To Lui Explica
    </a>
</h3>

<hr>

## To developing
### Init project composer and node_modules
```
composer install
yarn install
```

### Up localhost server to listening changes
```
yarn dev
```

## To config fields, configs and constants
Configure custom blocks for gutenberg:
```
 ./src/constants/blocks.json
```
Configure custom colors to use in gutenberg blocks and else settings:
```
 ./src/constants/colors.json
```
Configure custom theme settings:
```
 ./src/constants/theme_options.json
```


# Carbon fields meta
To obtain fields you can use:

```
// Para posts, páginas y custom post types
$post_value = carbon_get_post_meta($post_id, 'field_name');

// Para opciones de tema (ajustes globales del tema)
$theme_option_value = carbon_get_theme_option('field_name');

// Para widgets
$widget_value = carbon_get_widget_meta($widget_id, 'field_name');

// Para comentarios
$comment_value = carbon_get_comment_meta($comment_id, 'field_name');

// Para términos de taxonomía
$term_value = carbon_get_term_meta($term_id, 'field_name');

// Para ítems de menú (nav menu items)
$menu_item_value = carbon_get_nav_menu_item_meta($item_id, 'field_name');

// Para usuarios
$user_value = carbon_get_user_meta($user_id, 'field_name');

// Para bloques de Gutenberg (tratados como posts)
$block_value = carbon_get_post_meta($block_id, 'field_name');

```