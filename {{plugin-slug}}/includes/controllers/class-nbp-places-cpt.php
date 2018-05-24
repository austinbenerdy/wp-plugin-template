<?php

/**
 *
 */
class {{PLUGIN_ABBR}}_Places_CPT
{
    private $core;
    private $labels;
    private $args;
    private $slug;

    function __construct(&$core)
    {
        $this->core = $core;

        $this->set_labels();
        $this->set_args();

        add_action( 'init', array(&$this, 'register') );
        add_action( 'init', array(&$this, 'register_place_type_taxonomy') );

        add_filter('single_template', array( &$this, 'load_single_places_template' ), 10, 1);
        add_filter('archive_template', array( &$this, 'load_archive_places_template' ), 10, 1);
        add_filter('taxonomy_template', array( &$this, 'load_archive_places_template' ), 10, 1);
    }

    public function set_slug($slug)
    {
        $slug = strtolower($slug);
        $slug = str_replace('_', '-', $slug);
        $this->slug = str_replace(' ', '-', $slug);
    }

    public function register()
    {
        register_post_type( 'places', $this->args );
    }



    public function register_place_type_taxonomy() {
    	register_taxonomy(
    		'place_type',
    		'places',
    		array(
    			'label' => __( 'Type of Place' ),
    			'rewrite' => array( 'slug' => 'place' ),
    			'hierarchical' => false,
    		)
    	);
    }

    public function load_single_places_template($template)
    {
        global $wp_query, $post;

        /* Checks for single template by post type */
        if ( is_singular( 'places' ) ) {

            add_action( 'wp_enqueue_scripts', array( &$this->core, 'enqueue_scripts' ) );
            $theme_template = trailingslashit(get_template_directory()) . 'single-places.php';
            $child_theme_template = trailingslashit(get_stylesheet_directory()) . 'single-places.php';

            if (
                file_exists( $theme_template ) ||
                file_exists( $child_theme_template )
            )
            {
                // Use the theme template
                return $template;
            }

            // Use the plugin template
            return $this->core->paths['templates'] . 'single-places.php';
        }

        return $template;
    }

    public function load_archive_places_template($template)
    {
        global $wp_query, $post;

        /* Checks for archive template by post type */
        if ( is_post_type_archive('places') || is_tax('place_type') ) {

            add_action( 'wp_enqueue_scripts', array( &$this->core, 'enqueue_scripts' ) );
            $theme_template = trailingslashit(get_template_directory()) . 'archive-places.php';
            $child_theme_template = trailingslashit(get_stylesheet_directory()) . 'archive-places.php';

            if (
                file_exists( $theme_template ) ||
                file_exists( $child_theme_template )
            )
            {
                // Use the theme template
                return $template;
            }

            // Use the plugin template
            return $this->core->paths['templates'] . 'archive-places.php';
        }

        return $template;
    }

    public function create($place, $id = 0)
    {
        $address = $place->address_components[0]->short_name . ' ' .
            $place->address_components[1]->short_name;

        $args = array(
            'ID' => $id,
            'post_title' => $place->name,
            'post_type' => 'places',
            'post_status' => 'publish',
            'meta_input' => array(
                '{{plugin-abbr}}_lat' => $place->geometry->location->lat,
                '{{plugin-abbr}}_lng' => $place->geometry->location->lng,
                '{{plugin-abbr}}_icon' => $place->icon,
                '{{plugin-abbr}}_address' => $place->formatted_address,

                '{{plugin-abbr}}_hours_monday' => (isset($place->opening_hours)) ? $place->opening_hours->weekday_text[0] : '',
                '{{plugin-abbr}}_hours_tuesday' => (isset($place->opening_hours)) ? $place->opening_hours->weekday_text[1] : '',
                '{{plugin-abbr}}_hours_wednesday' => (isset($place->opening_hours)) ? $place->opening_hours->weekday_text[2] : '',
                '{{plugin-abbr}}_hours_thursday' => (isset($place->opening_hours)) ? $place->opening_hours->weekday_text[3] : '',
                '{{plugin-abbr}}_hours_friday' => (isset($place->opening_hours)) ? $place->opening_hours->weekday_text[4] : '',
                '{{plugin-abbr}}_hours_saturday' => (isset($place->opening_hours)) ? $place->opening_hours->weekday_text[5] : '',
                '{{plugin-abbr}}_hours_sunday' => (isset($place->opening_hours)) ? $place->opening_hours->weekday_text[6] : '',

                '{{plugin-abbr}}_url' => $place->url,
                '{{plugin-abbr}}_rating' => (isset($place->rating)) ? $place->rating : -1,
                '{{plugin-abbr}}_place_id' => $place->place_id,
                '{{plugin-abbr}}_photo_url' => $place->{{plugin-abbr}}_photo_url,
                '{{plugin-abbr}}_serialized_data' => json_encode($place),
            ),
        );
        $post_id = wp_insert_post($args);

        wp_set_object_terms( $post_id, null, 'place_type' );

        foreach ($place->types as $t) {
            wp_set_object_terms( $post_id, ucwords(str_replace('_', ' ', $t)), 'place_type', true );
        }
        return $post_id;
    }


    public function update($place, $id)
    {
        $post_id = $this->create($place, $id);
        return $post_id;
    }

    /**
     * Private Methods Break
     * @Break - Searchable Tag
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     */


    private function set_labels()
    {
        $this->labels = array(
            'name'                  => _x( 'Places', 'Post type general name', '{{plugin-slug}}-plugin' ),
            'singular_name'         => _x( 'Place', 'Post type singular name', '{{plugin-slug}}-plugin' ),
            'menu_name'             => _x( 'Places', 'Admin Menu text', '{{plugin-slug}}-plugin' ),
            'name_admin_bar'        => _x( 'Place', 'Add New on Toolbar', '{{plugin-slug}}-plugin' ),
            'add_new'               => __( 'Add New', 'textdomain' ),
            'add_new_item'          => __( 'Add New Place', '{{plugin-slug}}-plugin' ),
            'new_item'              => __( 'New Place', '{{plugin-slug}}-plugin' ),
            'edit_item'             => __( 'Edit Place', '{{plugin-slug}}-plugin' ),
            'view_item'             => __( 'View Place', '{{plugin-slug}}-plugin' ),
            'all_items'             => __( 'All Places', '{{plugin-slug}}-plugin' ),
            'search_items'          => __( 'Search Places', '{{plugin-slug}}-plugin' ),
            'parent_item_colon'     => __( 'Parent Places:', '{{plugin-slug}}-plugin' ),
            'not_found'             => __( 'No placess found.', '{{plugin-slug}}-plugin' ),
            'not_found_in_trash'    => __( 'No placess found in Trash.', '{{plugin-slug}}-plugin' ),
            'featured_image'        => _x(
                'Image of Place',
                'Overrides the “Featured Image” phrase for this post type. Added in 4.3',
                '{{plugin-slug}}-plugin'
            ),
            'set_featured_image'    => _x(
                'Set place image',
                'Overrides the “Set featured image” phrase for this post type. Added in 4.3',
                '{{plugin-slug}}-plugin'
            ),
            'remove_featured_image' => _x(
                'Remove place image',
                'Overrides the “Remove featured image” phrase for this post type. Added in 4.3',
                '{{plugin-slug}}-plugin'
            ),
            'use_featured_image'    => _x(
                'Use as place\'s image',
                'Overrides the “Use as featured image” phrase for this post type. Added in 4.3',
                '{{plugin-slug}}-plugin'
            ),
            'archives'              => _x(
                'Place archives',
                'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4',
                '{{plugin-slug}}-plugin'
            ),
            'insert_into_item'      => _x(
                'Insert into place',
                'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4',
                '{{plugin-slug}}-plugin'
            ),
            'uploaded_to_this_item' => _x(
                'Uploaded to this place',
                'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4',
                '{{plugin-slug}}-plugin'
            ),
            'filter_items_list'     => _x(
                'Filter places list',
                'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4',
                '{{plugin-slug}}-plugin'
            ),
            'items_list_navigation' => _x(
                'Places list navigation',
                'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4',
                '{{plugin-slug}}-plugin'
            ),
            'items_list'            => _x(
                'Places list',
                'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4',
                '{{plugin-slug}}-plugin'
            ),
        );
    }


    private function set_args()
    {
        $slug = ( is_null($this->slug) ) ? 'places' : $this->slug;

        $this->args = array(
            'labels'             => $this->labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $slug ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'custom-fields', 'editor', 'thumbnail', 'excerpt' ),
        );
    }



}
