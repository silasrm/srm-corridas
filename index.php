<?php
/**
 * Plugin Name: SRM Corridas
 * Plugin URI: http://www.silasribas.com.br
 * Description: Plugin que cria um custom post type Corridas e adiciona no menu
 * Version: 1.0
 * Author: Silas Ribas
 * Author URI: http://www.silasribas.com.br
 * License: MIT License
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Silas Ribas
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the 'Software'), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

defined('ABSPATH') or die('No script kiddies please!');

// @see https://developer.wordpress.org/reference/functions/plugin_dir_path/
define('SRM_CORRIDAS_PLUGIN_PATH', plugin_dir_path(__FILE__));

add_action('init', 'srmc_configuracoes');

function srmc_configuracoes()
{
    register_post_type(
        'corrida',
        array(
            'labels' => array(
                'name' => __('Corridas'),
                'menu_name' => __('Corridas'),
                'singular_name' => __('Corrida'),
                'all_items' => __('Todas as Corridas'),
                'add_new' => __('Nova Corrida'),
                'add_new_item' => __('Criar Nova Corrida'),
                'edit' => __('Editar'),
                'edit_item' => __('Editar Corrida'),
                'new_item' => __('Nova Corrida'),
                'view' => __('Ver Corrida'),
                'view_item' => __('Ver Corrida'),
                'search_items' => __('Buscar Corridas'),
                'not_found' => __('Nenhuma corrida encontrada'),
                'not_found_in_trash' => __('Nenhuma corrida encontrada na lixeira')
            ),
            'rewrite' => array(
                'slug' => 'corrida',
                'with_front' => true
            ),
            'supports' => array(
                'title',
                'custom_fields',
                'revisions',
            ),
            'description' => __('Corridas'),
            'public' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'menu_position' => null,
            'capability_type' => 'post',
            'hierarchical' => false,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => true,
            'map_meta_cap' => true,
        )
    );

    register_taxonomy(
        'Locais',
        array('corrida'),
        array(
            'hierarchical' => true,
            'labels' => array(
                'name' => __('Locais'),
                'menu_name' => __('Locais'),
                'singular_name' => __('Local'),
                'all_items' => __('Todos os Locais'),
                'add_new' => __('Novo Local'),
                'add_new_item' => __('Criar Novo Local'),
                'edit' => __('Editar'),
                'edit_item' => __('Editar Local'),
                'new_item' => __('Novo Local'),
                'view' => __('Ver Local'),
                'view_item' => __('Ver Local'),
                'search_items' => __('Buscar Locais'),
                'not_found' => __('Nenhum local encontrado'),
                'not_found_in_trash' => __('Nenhum local encontrado na lixeira')
            ),
            'rewrite' => true
        )
    );

    register_taxonomy(
        'DiasSemana',
        array('corrida'),
        array(
            'hierarchical' => true,
            'labels' => array(
                'name' => __('Dias da Semana'),
                'menu_name' => __('Dias da Semana'),
                'singular_name' => __('Dia da Semana'),
                'all_items' => __('Todos os Dias da Semana'),
                'add_new' => __('Novo Dia da Semana'),
                'add_new_item' => __('Criar Novo Dia da Semana'),
                'edit' => __('Editar'),
                'edit_item' => __('Editar Dia da Semana'),
                'new_item' => __('Novo Dia da Semana'),
                'view' => __('Ver Dia da Semana'),
                'view_item' => __('Ver Dia da Semana'),
                'search_items' => __('Buscar Dias da Semana'),
                'not_found' => __('Nenhum local encontrado'),
                'not_found_in_trash' => __('Nenhum local encontrado na lixeira')
            ),
            'rewrite' => true
        )
    );
}

add_action('admin_init', 'srmc_admin_init');

function srmc_admin_init() {
    add_meta_box('horario_saida_meta', 'Horário de Saída', 'horario_saida', 'corrida', 'normal', 'low');
    add_meta_box('horario_retorno_meta', 'Horário de Retorno', 'horario_retorno', 'corrida', 'normal', 'low');
}

function horario_saida() {
    global $post;
    $custom = get_post_custom($post->ID);
    $horario_saida_meta = $custom['horario_saida_meta'][0];
?>
    <label for="horario_saida_meta">Horário de Saída:</label>
    <input name="horario_saida_meta" id="horario_saida_meta" value="<?php echo $horario_saida_meta; ?>" />
<?php
}

function horario_retorno() {
    global $post;
    $custom = get_post_custom($post->ID);
    $horario_retorno_meta = $custom['horario_retorno_meta'][0];
?>
    <label for="horario_retorno_meta">Horário de Retorno:</label>
    <input name="horario_retorno_meta" id="horario_retorno_meta" value="<?php echo $horario_retorno_meta; ?>" />
<?php
}

add_action('save_post', 'srmc_save_details');

function srmc_save_details() {
    global $post;
    
    update_post_meta($post->ID, "horario_saida_meta", $_POST["horario_saida_meta"]);
    update_post_meta($post->ID, "horario_retorno_meta", $_POST["horario_retorno_meta"]);
}

add_filter("manage_edit-corrida_columns", "srmc_edit_columns");
add_action("manage_posts_custom_column",  "srmc_custom_columns");

function srmc_edit_columns($columns) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => "Título",
        "horario_saida_meta" => "Horário de Saída",
        "horario_retorno_meta" => "Horário de Retorno",
        "dias_semana" => "Dias da Semana",
        "locais" => "Locais",
    );

    return $columns;
}

function srmc_custom_columns($column) {
    global $post;

    switch ($column) {
        case "horario_saida_meta":
        case "horario_retorno_meta":
            $custom = get_post_custom();
            echo $custom[$column][0];
            break;
        case "dias_semana":
            echo get_the_term_list($post->ID, 'DiasSemana', '', ', ','');
            break;
        case "locais":
            echo get_the_term_list($post->ID, 'Locais', '', ', ','');
            break;
      break;
    }
}