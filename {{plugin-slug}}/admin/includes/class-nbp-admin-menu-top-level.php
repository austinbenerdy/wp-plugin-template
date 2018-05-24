<?php


/**
 *
 */
class {{PLUGIN_ABBR}}_Admin_Menu_Top_Level
{
    private $core;

    function __construct($core)
    {
        $this->core = $core;
        
        add_action( 'admin_menu', array(&$this, 'add_menu_page') );
    }

    public function add_menu_page()
    {
        add_menu_page(
            '{{Plugin Name}}',
            '{{Plugin Name}}',
            'manage_options',
            '{{plugin-slug}}',
            array(&$this, 'render_menu_page'),
            'dashicons-pressthis',
            369
        );
    }


    public function render_menu_page()
    {
        ob_start();

        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <p>Welcome to the {{Plugin Name}} plugin!</p>
        </div>

        <?php
        $output = ob_get_clean();
        echo $output;
    }

}
