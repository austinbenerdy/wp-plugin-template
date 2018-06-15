<?php




/**
 *
 */
class {{PLUGIN_ABBR}}_GAPI
{
    private $core;

    private $center_lat = 33.4484;
    private $center_lng = -112.4052327;
    private $radius = 500;
    private $key;

    function __construct(&$core)
    {
        $this->core = $core;
        $miles = $this->core->options['{{plugin-abbr}}_field_gapi_radius'];
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        $this->radius($meters);

        $this->center_lat = $this->core->options['{{plugin-abbr}}_field_gapi_center_lat'];
        $this->center_lng = $this->core->options['{{plugin-abbr}}_field_gapi_center_lng'];

        #$this->key = 'AIzaSyA47LbtUVg4YBXNgL9PLvdNV9LlKgtfREs';
        $this->key = $this->core->options['{{plugin-abbr}}_field_gapi_key'];
    }

    public function get_places($pagetoken = false)
    {
        // dump($pagetoken, 'Pagetoken');
        // dump($this->radius, 'Radius');
        if ($pagetoken)
        {
            $return = $this->get_next_places_page($pagetoken);
        }
        else
        {
            $response = wp_remote_get(
                'https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=' .
                $this->key .
                '&location=' . $this->center_lat . ',' . $this->center_lng .
                '&radius=' . $this->radius
            );
            $response = wp_remote_retrieve_body($response);

            $response = json_decode($response);

            if (!isset($response->results)) {
                die('Error');
            }

            $results = $response->results;

            $return = array(
                'places' => $results,
                'pagetoken' => false
            );

            if (isset($response->next_page_token))
            {
                $return['pagetoken'] = $response->next_page_token;
            }
        }

        return $return;
    }

    public function get_place($id)
    {
        $url = 'https://maps.googleapis.com/maps/api/place/details/json?key=' . $this->key . '&placeid=' . $id;
        $response = wp_remote_get($url);
        $response = json_decode(wp_remote_retrieve_body($response));
        $place = $response->result;
        // dump($place);
        if (isset($place->photos) && count($place->photos) > 0) {
            $photo_reference = $place->photos[0]->photo_reference;
            $attribute = $place->photos[0]->html_attributions;
            // dump($photo_reference);
            $photo_url = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=1200&photoreference=' . $photo_reference . '&key=' . $this->key;
            // dump($photo_url);
            $place->{{plugin-abbr}}_photo_url = $photo_url;
            // $photo_response = wp_remote_get($photo_url);
            // dump($photo_response, 'Photo');


            // $image_url = $photo_url;
            //
            // $upload_dir = wp_upload_dir();
            //
            // $image_data = file_get_contents($image_url);
            //
            // $filename = str_replace(' ', '-', strtolower($place->name));
            //
            // if (wp_mkdir_p($upload_dir['path']))
            // $file = $upload_dir['path'] . '/' . $filename;
            // else
            // $file = $upload_dir['basedir'] . '/' . $filename;
            //
            // file_put_contents($file, $image_data);
            //
            // $wp_filetype = wp_check_filetype($filename, null);
            //
            // $attachment = array(
            //     'post_mime_type' => $wp_filetype['type'],
            //     'post_title' => sanitize_file_name($filename),
            //     'post_content' => '',
            //     'post_status' => 'inherit'
            // );
            //
            // $attach_id = wp_insert_attachment( $attachment, $filename );
            // require_once(ABSPATH . 'wp-admin/includes/image.php');
            // $attach_data = wp_generate_attachment_metadata($attach_id, $file);
            // wp_update_attachment_metadata($attach_id, $attach_data);
        }
        else
        {
            $place->{{plugin-abbr}}_photo_url = null;
        }
        return $place;
    }



    /**
     * Getter Setter Methods Break
     * @Break - Searchable Tag
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     */


    public function center_lat($lat = false)
    {
        if (!$lat) { return $this->center_lat; }

        // Within possible range of latitudes
        if ( -90 < $lat && $lat < 90 )
        {
            $this->center_lat = $lat;
            return true;
        }

        // Out of range
        return false;
    }

    public function center_lng($lng = false)
    {
        if (!$lng) { return $this->center_lng; }

        // Within possible range of longitude
        if ( -180 < $lng && $lng < 180 )
        {
            $this->center_lng = $lng;
            return true;
        }

        // Out of range
        return false;
    }

    public function radius($radius = false)
    {
        if (!$radius) { return $this->radius; }

        // Make sure this is a number
        if ( is_numeric($radius) )
        {
            $this->radius = $radius;
            return true;
        }

        // Out of range
        return false;
    }

    public function key($key = false)
    {
        if (!$key) { return $this->key; }

        // Make sure this is a number
        if ( is_numeric($key) )
        {
            $this->key = $key;
            return true;
        }

        // Out of range
        return false;
    }


    /**
     * Private Methods Break
     * @Break - Searchable Tag
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     */

    private function get_next_places_page($pagetoken)
    {
        $url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=' . $this->key . '&pagetoken=' . $pagetoken;
        $response = wp_remote_get($url);
        $response = json_decode(wp_remote_retrieve_body($response));
        $results = $response->results;

        $return = array(
            'places' => $results,
            'pagetoken' => false
        );

        if (isset($response->next_page_token))
        {
            $return['pagetoken'] = $response->next_page_token;
        }

        return $return;
    }
}
