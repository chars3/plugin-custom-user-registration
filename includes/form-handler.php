<?php
// Função para processar o formulário e inserir os dados no banco de dados
function cur_register_user()
{
    if (
        !isset($_POST['cur_nonce']) ||
        !wp_verify_nonce($_POST['cur_nonce'], 'cur_register_user')
    ) {
        wp_die('Erro de validação, por favor tente novamente.');
    }

    if (isset($_POST['cur_name']) && isset($_POST['cur_email']) && isset($_POST['cur_phone']) && isset($_POST['cur_company_name'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_users';

        $name = sanitize_text_field($_POST['cur_name']);
        $email = sanitize_email($_POST['cur_email']);
        $phone = sanitize_text_field($_POST['cur_phone']);
        $company_name = sanitize_text_field($_POST['cur_company_name']);

        // Verificar se o e-mail já está cadastrado
        $user_exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE email = %s",
            $email
        ));

        if ($user_exists) {
            wp_die('Este e-mail já está cadastrado.');
        }

        $wpdb->insert(
            $table_name,
            [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'company_name' => $company_name,
            ]
        );

        wp_redirect(add_query_arg('registered', 'success', home_url()));
        exit;
    }
}

add_action('admin_post_nopriv_cur_register_user', 'cur_register_user');
add_action('admin_post_cur_register_user', 'cur_register_user');
