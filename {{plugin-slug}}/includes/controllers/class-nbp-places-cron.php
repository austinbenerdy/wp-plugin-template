<?php

/**
 *
 */
class {{PLUGIN_ABBR}}_Places_Cron
{
    private $last_result_option_name = '{{plugin-abbr}}_last_result';
    private $last_result;
    private $last_ran_option_name = '{{plugin-abbr}}_last_ran';
    private $last_ran;
    private $next_pagetoken_option_name = '{{plugin-abbr}}_next_pagetoken';
    private $next_pagetoken;
    private $core;

    function __construct(&$core)
    {
        $this->core = $core;

        $this->last_result = get_option($this->last_result_option_name);
        $this->last_ran = get_option($this->last_ran_option_name);
        $this->next_pagetoken = get_option($this->next_pagetoken_option_name);


        // add_action( 'rest_api_init', function () {
        //     register_rest_route( '{{plugin-slug}}/v1', '/trigger', array(
        //         'methods' => 'GET',
        //         'callback' => array(&$this, 'execute_rest_api'),
        //     ) );
        // } );

        add_action( '{{plugin-abbr}}_cron_hook', array(&$this, 'cron') );
        if ( ! wp_next_scheduled( '{{plugin-abbr}}_cron_hook' ) ) {
            wp_schedule_event( time(), 'daily', '{{plugin-abbr}}_cron_hook' );
        }
        //$this->cron();
    }

    public function cron()
    {
        add_action( 'wp_enqueue_scripts', array( &$this, 'cron_scripts' ) );
    }

    public function cron_scripts() {
        wp_enqueue_script( 'custom-script', $this->core->paths['urls']['js'] . 'script-{{plugin-abbr}}-cron.js', array( 'jquery' ) );
    }

    public function execute_rest_api(WP_REST_Request $request)
    {
        $this->execute();
        die();
    }

    public function execute_debug_api()
    {
        $this->execute();
    }

    /**
     * Private Methods Break
     * @Break - Searchable Tag
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     */

    private function execute()
    {
        $places = array();
        if ( $this->next_pagetoken )
        {
            $places = $this->core->gapi->get_places($this->next_pagetoken);
            $message = 'Next Page Trigger';
        }
        # else if ( $this->last_ran < date('Y-m-d H:i:s', time() - 60*60*24*7) )
        #else if ( $this->last_ran < date('Y-m-d H:i:s', time() - 60*60) )
        else if ( $this->last_ran < date('Y-m-d H:i:s', time()) )
        {
            $places = $this->core->gapi->get_places();
            update_option( $this->last_ran_option_name, date('Y-m-d H:i:s') );
            $message = 'Last Ran Trigger';
        }
        else
        {
            echo json_encode(array(
                'status' => 'No Trigger',
            ));
            return;  # Do Nothing
        }

        update_option($this->next_pagetoken_option_name, $places['pagetoken']);
        // dump($places);
        $this->filter_places($places['places']);

        echo json_encode(array(
            'status' => $message
        ));

    }

    private function filter_places($places)
    {
        foreach ($places as $place)
        {
            $id = $place->place_id;
            $place_details = $this->core->gapi->get_place($id);

            $this->handle_cpt($place_details);
        }
    }


    private function handle_cpt($place)
    {
        //dump($place->types);
        $existing = $this->core->relation_table->get_by_place_id($place->place_id);

        if ($existing)
        {
            $post_id = $this->core->places_cpt->update($place, $existing->wp_post_id);
            $this->core->relation_table->update(array('updated_at' => time()), $existing->id);
        }
        else {
            $post_id = $this->core->places_cpt->create($place);
            $this->core->relation_table->insert(array(
                'wp_post_id' => $post_id,
                'gapi_place_id' => $place->place_id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ));
        }
    }
}
