<?php
// Função para adicionar uma página ao menu de administração
function custom_user_registration_admin_menu()
{
    add_menu_page(
        'Usuários Cadastrados',
        'Usuários Cadastrados',
        'manage_options',
        'custom_user_registration_users',
        'custom_user_registration_users_page',
        'dashicons-admin-users'
    );
}

// Função para exibir os dados na página de administração
function custom_user_registration_users_page()
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
                    <th id="columnname" class="manage-column column-columnname" scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo esc_html($user->name); ?></td>
                        <td><?php echo esc_html($user->email); ?></td>
                        <td><?php echo esc_html($user->phone); ?></td>
                        <td><?php echo esc_html($user->company_name); ?></td>
                        <td>
                            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                <?php wp_nonce_field('custom_user_registration_delete_user', 'custom_user_registration_delete_nonce'); ?>
                                <input type="hidden" name="action" value="custom_user_registration_delete_user">
                                <input type="hidden" name="user_id" value="<?php echo esc_attr($user->id); ?>">
                                <input type="submit" value="Excluir" class="button button-secondary" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
}

// Função para excluir usuário
function custom_user_registration_delete_user()
{
    if (
        !isset($_POST['custom_user_registration_delete_nonce']) ||
        !wp_verify_nonce($_POST['custom_user_registration_delete_nonce'], 'custom_user_registration_delete_user')
    ) {
        wp_die('Erro de validação, por favor tente novamente.');
    }

    if (isset($_POST['user_id'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_users';
        $user_id = intval($_POST['user_id']);

        $wpdb->delete(
            $table_name,
            ['id' => $user_id]
        );

        wp_redirect(add_query_arg('deleted', 'success', admin_url('admin.php?page=custom_user_registration_users')));
        exit;
    }
}

add_action('admin_post_custom_user_registration_delete_user', 'custom_user_registration_delete_user');
?>