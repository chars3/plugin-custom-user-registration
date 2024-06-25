<?php
// Shortcode para exibir o formulário
function cur_registration_form()
{
    ob_start();
?>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <?php wp_nonce_field('cur_register_user', 'cur_nonce'); ?>
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
?>