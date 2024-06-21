<?php
/*
Plugin Name: Custom User Registration
Description: Plugin para cadastro de usuários.
Version: 1.0
Author: Charles
*/

// Impedir acesso direto ao arquivo
if (!defined('ABSPATH')) {
    exit;
}

// Criar a tabela no banco de dados
function cur_create_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_users';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(15) NOT NULL,
        company_name tinytext NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Hook para criar a tabela no momento da ativação do plugin
register_activation_hook(__FILE__, 'cur_create_table');

// Shortcode para exibir o formulário
function cur_registration_form()
{
    ob_start();
?>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <p>
            <label for="cur_name">Nome</label>
            <input type="text" name="cur_name" id="cur_name" required>
        </p>
        <p>
            <label for="cur_email">Email</label>
            <input type="email" name="cur_email" id="cur_email" required>
        </p>
        <p>
            <label for="cur_phone">Telefone</label>
            <input type="text" name="cur_phone" id="cur_phone" required>
        </p>
        <p>
            <label for="cur_company_name">Nome da Empresa</label>
            <input type="text" name="cur_company_name" id="cur_company_name" required>
        </p>
        <p>
            <input type="hidden" name="action" value="cur_register_user">
            <input type="submit" value="Cadastrar">
        </p>
    </form>
<?php
    return ob_get_clean();
}

add_shortcode('cur_registration_form', 'cur_registration_form');

// Função para processar o formulário e inserir os dados no banco de dados
function cur_register_user()
{
    if (isset($_POST['cur_name']) && isset($_POST['cur_email']) && isset($_POST['cur_phone']) && isset($_POST['cur_company_name'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_users';

        $name = sanitize_text_field($_POST['cur_name']);
        $email = sanitize_email($_POST['cur_email']);
        $phone = sanitize_text_field($_POST['cur_phone']);
        $company_name = sanitize_text_field($_POST['cur_company_name']);

        $wpdb->insert(
            $table_name,
            [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'company_name' => $company_name,
            ]
        );

        wp_redirect(home_url());
        exit;
    }
}

add_action('admin_post_nopriv_cur_register_user', 'cur_register_user');
add_action('admin_post_cur_register_user', 'cur_register_user');

// Função para adicionar uma página ao menu de administração
function cur_admin_menu()
{
    add_menu_page(
        'Usuários Cadastrados',
        'Usuários Cadastrados',
        'manage_options',
        'cur_users',
        'cur_users_page',
        'dashicons-admin-users'
    );
}

add_action('admin_menu', 'cur_admin_menu');

// Função para exibir os dados na página de administração
function cur_users_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_users';
    $users = $wpdb->get_results("SELECT * FROM $table_name");
?>
    <div class="wrap">
        <h1>Usuários Cadastrados</h1>
        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th id="columnname" class="manage-column column-columnname" scope="col">Nome</th>
                    <th id="columnname" class="manage-column column-columnname" scope="col">Email</th>
                    <th id="columnname" class="manage-column column-columnname" scope="col">Telefone</th>
                    <th id="columnname" class="manage-column column-columnname" scope="col">Nome da Empresa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo esc_html($user->name); ?></td>
                        <td><?php echo esc_html($user->email); ?></td>
                        <td><?php echo esc_html($user->phone); ?></td>
                        <td><?php echo esc_html($user->company_name); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
}
?>