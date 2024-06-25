<?php
/*
Plugin Name: Custom User Registration
Description: Plugin para cadastro de usuários.
Version: 1.1
Author: Charles
*/

// Impedir acesso direto ao arquivo
if (!defined('ABSPATH')) {
    exit;
}

// Incluir os arquivos necessários
require_once plugin_dir_path(__FILE__) . 'includes/activation.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/form-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';

// Hook para criar a tabela no momento da ativação do plugin
register_activation_hook(__FILE__, 'cur_create_table');

// Adicionar o shortcode
add_shortcode('cur_registration_form', 'cur_registration_form');

// Adicionar a página ao menu de administração
add_action('admin_menu', 'cur_admin_menu');
