<?php


/**
 *
 */
class {{PLUGIN_ABBR}}_Core
{
    public $admin;
    public $db;
    public $options;
    public $paths;

    function __construct()
    {
        $this->set_paths();

        if ( is_admin() )
		{
            $this->admin = new {{PLUGIN_ABBR}}_Admin($this);
		}

        $this->db = new {{plugin_abbr}}_DB_Example($this);

        add_action('{{plugin-abbr}}_debug', array(&$this, '_debug'));
    }


    public function include_component($component_name)
    {
        if (file_exists( $this->paths['templates'] . 'component-' . $component_name . '.php' ))
        {
            include $this->paths['templates'] . 'component-' . $component_name . '.php';
        }
    }



    public function _debug()
    {

    }


    public function enqueue_scripts()
    {
        wp_register_script(
            '{{plugin-slug}}',
            $this->paths['urls']['js'] .'scripts.min.js',
            array(),
            false,
            false
        );
        wp_enqueue_script('{{plugin-slug}}');

        wp_register_style(
            '{{plugin-slug}}',
            $this->paths['urls']['css'] . 'styles.css'
        );
	    wp_enqueue_style( '{{plugin-slug}}' );
    }







    private function set_paths()
    {
        $root = trailingslashit(dirname(__FILE__));
        $url = trailingslashit(plugins_url()) . '{{plugin-slug}}/';
        $this->paths = array(
            'includes' => $root . 'includes/',
            'templates' => $root . 'includes/templates/',

            'urls' => array(
                'js' => $url . 'js/',
                'css' => $url . 'css/',
            ),
        );
    }


}
