<?php

/**
 * Interface
 */
abstract class RWSDB
{
    protected $tablename;
    protected $columns = array();
    protected $required = array();

    function __construct()
    {
        global $wpdb;
        $tableSearch = $wpdb->get_var("SHOW TABLES LIKE '$this->tablename'");

        if ($tableSearch != $this->tablename) {
            $this->create_update_database();
        }
    }

    public function delete_table()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS $this->tablename");
    }

    public function insert($data)
    {
        global $wpdb;
        $insert = true;
        foreach ($this->required as $col)
        {
            if ( !isset($data[$col]) )
            {
                $insert = false;
            }
        }

        if ($insert)
        {
            $return = $wpdb->insert($this->tablename, $data);
        }
        else
        {
            // @TODO Throw error and store values in errors table.
            $return = false;
        }

        return $return;
    }


    public function update($data, $id)
    {
        global $wpdb;

        if ( isset($data['created_at']) )
        {
            unset($data['created_at']);
        }

        $data['updated_at'] = date('Y-m-d H:i:s');
        $return = $wpdb->update( $this->tablename, $data, array('id' => $id) );
        return $return;
    }

    /**
     * Abstract Methods Break
     * @Break - Searchable Tag
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     */

    abstract protected function create_update_database();
    abstract protected function set_columns();

    /**
     * Protected Methods Break
     * @Break - Searchable Tag
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     * ##### ##### ##### ##### ##### ##### ##### ##### ##### #####
     */


}
