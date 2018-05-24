<?php


/**
 *
 */
class {{PLUGIN_ABBR}}_Admin_Submenu_Settings
{
    private $core;

    function __construct($core)
    {
        $this->core = $core;

        add_action( 'admin_menu', array(&$this, 'add_menu_page') );
        add_action( 'admin_init', array(&$this, '{{plugin-abbr}}_settings_init') );
    }

    public function add_menu_page()
    {
        add_submenu_page(
            '{{plugin-slug}}',
            'Settings',
            'Settings',
            'manage_options',
            '{{plugin-slug}}-settings',
            array($this, 'render_menu_page')
        );
    }


    public function render_menu_page()
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        ob_start();

        if ( isset( $_GET['settings-updated'] ) ) {
            add_settings_error( '{{plugin-abbr}}_messages', '{{plugin-abbr}}_message', __( 'Settings Saved', '{{plugin-slug}}' ), 'updated' );
        }

        // show error/update messages
        settings_errors( '{{plugin-abbr}}_messages' );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <p>Welcome to the {{Plugin Name}} plugin!</p>
            <form action="/wp-admin/options.php" method="post">
                <?php
                settings_fields( '{{plugin-abbr}}' );
                do_settings_sections( '{{plugin-abbr}}' );
                submit_button( 'Save Settings' );
                ?>
            </form>
        </div>
        <?php
        $output = ob_get_clean();
        echo $output;
    }



     public function {{plugin-abbr}}_settings_init()
     {
         register_setting( '{{plugin-abbr}}', '{{plugin-abbr}}_options' );

         add_settings_section(
             '{{plugin-abbr}}_section_developers',
             __( 'General Settings', '{{plugin-slug}}' ),
             array(&$this, '{{plugin-abbr}}_section_developers_cb'),
             '{{plugin-abbr}}'
         );

         add_settings_field(
             '{{plugin-abbr}}_field_gapi_key',
             __( 'Google Maps API Key', '{{plugin-slug}}' ),
             array(&$this, '{{plugin-abbr}}_field_gapi_key_cb'),
             '{{plugin-abbr}}',
             '{{plugin-abbr}}_section_developers',
             [
                 'label_for' => '{{plugin-abbr}}_field_gapi_key',
                 'class' => '{{plugin-abbr}}_row',
                 '{{plugin-abbr}}_custom_data' => 'custom',
             ]
         );

         add_settings_field(
             '{{plugin-abbr}}_field_gapi_center_lat',
             __( 'Center Point Latitude', '{{plugin-slug}}' ),
             array(&$this, '{{plugin-abbr}}_field_gapi_center_lat_cb'),
             '{{plugin-abbr}}',
             '{{plugin-abbr}}_section_developers',
             [
                 'label_for' => '{{plugin-abbr}}_field_gapi_center_lat',
                 'class' => '{{plugin-abbr}}_row',
                 '{{plugin-abbr}}_custom_data' => 'custom',
             ]
         );

         add_settings_field(
             '{{plugin-abbr}}_field_gapi_center_lng',
             __( 'Center Point Longitude', '{{plugin-slug}}' ),
             array(&$this, '{{plugin-abbr}}_field_gapi_center_lng_cb'),
             '{{plugin-abbr}}',
             '{{plugin-abbr}}_section_developers',
             [
                 'label_for' => '{{plugin-abbr}}_field_gapi_center_lng',
                 'class' => '{{plugin-abbr}}_row',
                 '{{plugin-abbr}}_custom_data' => 'custom',
             ]
         );

         add_settings_field(
             '{{plugin-abbr}}_field_gapi_radius',
             __( 'Google Maps Search Radius', '{{plugin-slug}}' ),
             array(&$this, '{{plugin-abbr}}_field_gapi_radius_cb'),
             '{{plugin-abbr}}',
             '{{plugin-abbr}}_section_developers',
             [
                 'label_for' => '{{plugin-abbr}}_field_gapi_radius',
                 'class' => '{{plugin-abbr}}_row',
                 '{{plugin-abbr}}_custom_data' => 'custom',
             ]
         );


         add_settings_field(
             '{{plugin-abbr}}_field_archive_ajax',
             __( 'Use AJAX for archive page', '{{plugin-slug}}' ),
             array(&$this, '{{plugin-abbr}}_field_archive_ajax_cb'),
             '{{plugin-abbr}}',
             '{{plugin-abbr}}_section_developers',
             [
                 'label_for' => '{{plugin-abbr}}_field_archive_ajax',
                 'class' => '{{plugin-abbr}}_row',
                 '{{plugin-abbr}}_custom_data' => 'custom',
             ]
         );
     }




     public function {{plugin-abbr}}_section_developers_cb( $args )
     {
         ?>
         <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'We have a couple settings for you to customize your experience.', '{{plugin-slug}}' ); ?></p>
         <?php
     }


    public function {{plugin-abbr}}_field_gapi_key_cb( $args )
    {
        $options = $this->core->options;
        ?>
        <input type="text"
            id="<?php echo esc_attr( $args['label_for'] ); ?>"
            class="widefat"
            data-custom="<?php echo esc_attr( $args['{{plugin-abbr}}_custom_data'] ); ?>"
            name="{{plugin-abbr}}_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
            value="<?php echo isset( $options[ $args['label_for'] ] ) ? ( $options[ $args['label_for'] ] ) : ( '' ); ?>">

        <p class="description">
            <?php _e( 'In order to function, {{Plugin Name}} needs to be able to talk to Google Maps. In order to do this, we need an Google Maps API Key. You can find out how to get one from Google <a href="">by clicking here</a>.', '{{plugin-slug}}' ); ?>
        </p>
        <?php
    }

    public function {{plugin-abbr}}_field_gapi_center_lat_cb( $args )
    {
        $options = $this->core->options;
        ?>
        <input type="number"
            step="0.0000001"
            id="<?php echo esc_attr( $args['label_for'] ); ?>"
            class="widefat"
            data-custom="<?php echo esc_attr( $args['{{plugin-abbr}}_custom_data'] ); ?>"
            name="{{plugin-abbr}}_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
            value="<?php echo isset( $options[ $args['label_for'] ] ) ? ( $options[ $args['label_for'] ] ) : ( '' ); ?>">

        <p class="description">
            <?php _e( 'The latitude for the center point.', '{{plugin-slug}}' ); ?>
        </p>
        <?php
    }


    public function {{plugin-abbr}}_field_gapi_center_lng_cb( $args )
    {
        $options = $this->core->options;
        ?>
        <input type="number"
            step="0.0000001"
            id="<?php echo esc_attr( $args['label_for'] ); ?>"
            class="widefat"
            data-custom="<?php echo esc_attr( $args['{{plugin-abbr}}_custom_data'] ); ?>"
            name="{{plugin-abbr}}_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
            value="<?php echo isset( $options[ $args['label_for'] ] ) ? ( $options[ $args['label_for'] ] ) : ( '' ); ?>">

        <p class="description">
            <?php _e( 'The longitutde for the center point.', '{{plugin-slug}}' ); ?>
        </p>
        <?php
    }


    public function {{plugin-abbr}}_field_gapi_radius_cb( $args )
    {
        $options = $this->core->options;
        ?>
        <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['{{plugin-abbr}}_custom_data'] ); ?>"
            name="{{plugin-abbr}}_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
            >
            <option value="1" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '1', false ) ) : ( '' ); ?>>
                <?php esc_html_e( 'One Mile Radius', '{{plugin-slug}}' ); ?>
            </option>
            <option value="2" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '2', false ) ) : ( '' ); ?>>
                <?php esc_html_e( 'Two Mile Radius', '{{plugin-slug}}' ); ?>
            </option>
            <option value="3" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '3', false ) ) : ( '' ); ?>>
                <?php esc_html_e( 'Three Mile Radius', '{{plugin-slug}}' ); ?>
            </option>
            <option value="5" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '5', false ) ) : ( '' ); ?>>
                <?php esc_html_e( 'Five Mile Radius', '{{plugin-slug}}' ); ?>
            </option>
        </select>
        <p class="description">
            <?php esc_html_e( 'How far away is the for your location do you want to search for places?', '{{plugin-slug}}' ); ?>
        </p>
        <?php
    }

    public function {{plugin-abbr}}_field_archive_ajax_cb( $args )
    {
        $options = $this->core->options;
        ?>
        <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['{{plugin-abbr}}_custom_data'] ); ?>"
            name="{{plugin-abbr}}_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
            >
            <option value="0" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '0', false ) ) : ( '' ); ?>>
                <?php esc_html_e( 'No, Use Page Reloads on Archive', '{{plugin-abbr}}' ); ?>
            </option>
            <option value="true" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'true', false ) ) : ( '' ); ?>>
                <?php esc_html_e( 'Use Ajax to Update Archive!', '{{plugin-abbr}}' ); ?>
            </option>
        </select>
        <!-- <p class="description">
            <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', '{{plugin-abbr}}' ); ?>
        </p> -->
        <?php
        //dump($options);
    }


    /**
     * Example field pulled from WP Core docs
     */
    public function {{plugin-abbr}}_field_pill_cb( $args )
    {
        $options = $this->core->options;
        ?>
        <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['{{plugin-abbr}}_custom_data'] ); ?>"
            name="{{plugin-abbr}}_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
            >
            <option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
                <?php esc_html_e( 'red pill', '{{plugin-abbr}}' ); ?>
            </option>
            <option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
                <?php esc_html_e( 'blue pill', '{{plugin-abbr}}' ); ?>
            </option>
        </select>
        <p class="description">
            <?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', '{{plugin-abbr}}' ); ?>
        </p>
        <p class="description">
            <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', '{{plugin-abbr}}' ); ?>
        </p>
        <?php
    }
}
