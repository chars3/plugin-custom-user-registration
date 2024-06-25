<?php
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
        PRIMARY KEY (id),
        UNIQUE (email)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
