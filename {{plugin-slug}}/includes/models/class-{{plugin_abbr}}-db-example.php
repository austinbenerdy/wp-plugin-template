<?php

/**
 *
 */
class {{PLUGIN_ABBR}}_DB_Example extends RWSDB
{
    protected $tablename = '{{plugin-abbr}}_example';

    function __construct($core)
    {
        global $wpdb;
        $this->core = $core;
        $this->tablename = $wpdb->prefix . $this->tablename;
        parent::__construct();
    }

    /**
     * Getter Setter Methods Break
     * @Break - Searchable Tag
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     */


    /**
     * Protected Methods Break
     * @Break - Searchable Tag
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     */
    protected function create_update_database()
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $sql = "CREATE TABLE $this->tablename (
				 id int(11) NOT NULL AUTO_INCREMENT,
				 wp_post_id INT(11) DEFAULT '0' NOT NULL,
				 created_at datetime,
				 updated_at datetime,
				PRIMARY KEY  (id)
				)
				CHARACTER SET utf8
				COLLATE utf8_general_ci;";
        dbDelta($sql);
    }

    protected function set_columns()
    {
        $this->columns = array(
            'id',
            'wp_post_id',
            'created_at',
            'update_at',
        );

        $this->required = array(
            'wp_post_id',
        );
    }


    /**
     * Private Methods Break
     * @Break - Searchable Tag
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     */
}
