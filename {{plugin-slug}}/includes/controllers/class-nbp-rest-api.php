<?php

/**
 *
 */
class {{PLUGIN_ABBR}}_Rest_API
{

    function __construct($core)
    {
        $this->core = $core;
        add_action( 'rest_api_init', array(&$this, 'add_endpoints'));
    }


    public function add_endpoints()
    {
        register_rest_route( '{{plugin-slug}}/v1', '/trigger', array(
            'methods' => 'GET',
            'callback' => array(&$this->core->cron, 'execute_rest_api'),
        ) );

        register_rest_route( '{{plugin-slug}}/v1', '/places', array(
            'methods' => 'GET',
            'callback' => array(&$this, 'places'),
        ) );
    }


    public function places(WP_REST_Request $request)
    {

        $query = new WP_Query( array(
            'post_type' => 'places',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'place_type',
                    'field'    => 'id',
                    'terms'    => $request['tag'],
                ),
            ),
        ) );

        ob_start();
        if ($query->have_posts())
        {
            while($query->have_posts())
            {
                $query->the_post();
                ?>
                <div class="{{plugin-slug}}-content-text">
                    <?php
                    $theme_template = trailingslashit(get_template_directory()) . 'component-{{plugin-abbr}}-archive-place.php';
                    $child_theme_template = trailingslashit(get_stylesheet_directory()) . 'component-{{plugin-abbr}}-archive-place.php';

                    if (file_exists( $theme_template ))
                    {
                        // Use the theme template
                        require($theme_template);
                    }
                    else if (file_exists( $child_theme_template ))
                    {
                        require($child_theme_template);
                    }
                    else
                    {
                        require($this->core->paths['templates'] . 'component-place.php');
                    }
                    ?>
                </div>
                <?php
            }
        }

        $output = ob_get_clean();

        $return = array(
            'tag_id' => $request['tag'],
            'html' => $output
        );
        echo json_encode($return);
        die();
    }
}
