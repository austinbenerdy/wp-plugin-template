<?php


/**
 *
 */
class {{PLUGIN_ABBR}}_Admin
{
    private $core;
    private $menu_page_tl;

    function __construct(&$core)
    {
        $this->core = $core;

        $this->menu_page_tl = new {{PLUGIN_ABBR}}_Admin_Menu_Top_Level($core);
        $this->submenu_settings = new {{PLUGIN_ABBR}}_Admin_Submenu_Settings($core);

        add_action( 'admin_enqueue_scripts', array(&$this, '{{plugin-abbr}}_admin_scripts') );

        add_filter( 'manage_places_posts_columns', array( &$this, 'set_places_cpt_outdated_column') );
        add_action( 'manage_places_posts_custom_column' , array(&$this, 'places_cpt_outdated_column'), 10, 2 );
    }

    public function set_places_cpt_outdated_column($columns) {
        unset($columns['date']);
        $columns['outdated_place'] = __( 'Up To Date', '{{plugin-slug}}' );
        return $columns;
    }

    public function places_cpt_outdated_column( $column, $post_id ) {
    	switch ( $column ) {
    		case 'outdated_place':
                $relation = $this->core->relation_table->get_by_post_id($post_id);
                echo $relation->updated_at;
                if ($relation->updated_at <= date("Y-m-d H:i:s", time() - 60*60*24*7))
                {
                    echo '<i class="fas fa-2x fa-times-circle" style="color: red;margin-left: 15px; margin-right:15px;"></i>';
                }
                else {
                    echo '<i class="fas fa-2x fa-check-circle" style="color: green;margin-left: 15px; margin-right:15px;"></i>';
                }

    			break;
    	}
    }

    public function {{plugin-abbr}}_admin_scripts() {
        wp_register_script( 'fontawesome', 'https://use.fontawesome.com/releases/v5.0.8/js/all.js' );
        wp_enqueue_script( 'fontawesome' );
    }
}
