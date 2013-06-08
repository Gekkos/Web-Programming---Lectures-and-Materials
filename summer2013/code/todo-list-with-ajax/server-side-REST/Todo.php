<?php

require "MySQL.php";

class Todo
{
    public $dp;
    static $FIELDS = array('description','completed');
    function __construct()
    {
        /**
         * $this->dp = new DB_PDO_Sqlite();
         * $this->dp = new DB_PDO_MySQL();
         * $this->dp = new DB_Serialized_File();
         */
        //$this->dp = new DB_Session();
		$this->dp = new DB_PDO_MySQL();
    }
    function index()
    {
        return $this->dp->getAll();
    }
    function get($id)
    {
        return $this->dp->get($id);
    }
    function post($request_data = NULL)
    {
        return $this->dp->insert($this->_validate($request_data));
    }
    function put($id, $request_data = NULL)
    {
        return $this->dp->update($id, $this->_validate($request_data));
    }
    function delete($id)
    {
        return $this->dp->delete($id);
    }
	
	
	
    private function _validate($data)
    {
        $todo = array();
        foreach (Todo::$FIELDS as $field) {
            if (!isset($data[$field]))
                throw new RestException(400, "$field field missing");
            $todo[$field] = $data[$field];
        }
        return $todo;
    }
}
?>