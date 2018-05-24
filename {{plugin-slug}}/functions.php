<?php

/**
 * dump function for debug
 */
if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE) {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left; width: 100% !important; font-size: 12px !important;">' . $label . ' => ' . $output . '</pre>';
        if ($echo == TRUE) {
            echo $output;}else {return $output;}
    }
}
if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE) {
        dump ($var, $label, $echo);exit;
    }
}


/**
 * Auto Loader
 */
function {{plugin-abbr}}_autoloaders($class) {
	$dir = trailingslashit(dirname(__FILE__));
	$file = 'class-' . str_replace( '_', '-', strtolower($class)) . '.php';

	if ( file_exists($dir . 'includes/' . $file) )
	{
		require_once($dir . 'includes/' . $file);
	}
    else if ( file_exists($dir . 'includes/controllers/' . $file) )
	{
		require_once($dir . 'includes/controllers/' . $file);
	}
    else if ( file_exists($dir . 'includes/models/' . $file) )
	{
		require_once($dir . 'includes/models/' . $file);
	}
	else if ( file_exists($dir . 'admin/includes/' . $file) )
	{
		require_once($dir . 'admin/includes/' . $file);
	}
	else if ( file_exists($dir . $file) )
	{
		require_once($dir . $file);
	}
}



/**
 * Activation and CPT
 * @method {{plugin-abbr}}_setup_post_type
 * @return void
 */
function {{plugin-abbr}}_setup_post_type() {
    // register the "book" custom post type
    register_post_type( 'places', ['public' => 'true', 'has_archive' => true] );
    register_taxonomy(
        'place_type',
        'places',
        array(
            'label' => __( 'Type of Place' ),
            'rewrite' => array( 'slug' => 'place-type' ),
            'hierarchical' => false,
        )
    );
}

function {{plugin-abbr}}_install() {
    // trigger our function that registers the custom post type
    {{plugin-abbr}}_setup_post_type();

    // clear the permalinks after the post type has been registered
    flush_rewrite_rules();
}




/**
 * Deactivation
 * @method {{plugin-abbr}}_deactivation
 * @return void
 */
function {{plugin-abbr}}_deactivation() {
    // unregister the post type, so the rules are no longer in memory
    unregister_post_type( 'places' );
    // clear the permalinks to remove our post type's rules from the database
    flush_rewrite_rules();
}
