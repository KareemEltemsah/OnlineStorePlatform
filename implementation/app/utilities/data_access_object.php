<?php

class DataAccessObject
{
	public $db = null;
	public $tableName;
	public $columns = array();

	public function __construct($_tableName)
	{
		$this->tableName = $GLOBALS['configs']["tables"][$_tableName];
        $this->columns = $GLOBALS['configs']["columns"][$_tableName];
		$this->db = DatabaseConnector::getInstance()->getConnection();
	}

	public function select($_array)
	{
		$i = 0;
		$arrayCount = count($_array);
		$query = "SELECT * FROM `" . $this->tableName . "` WHERE ";


		foreach($_array as $key => $value)
		{
			$value = $this->db->real_escape_string($value);
			

			if(++$i === $arrayCount) 
			{
				$query .= '`' . $key . '` = \'' . $value . "'";
			}
			else
			{
				$query .= '`' . $key . '` = \'' . $value . "' AND ";
			}
		}

      		// If the query can't be executed (e.g: use of special characters in inputs)
        	if(!$result = $this->db->query($query)) 
		{
            		return 0;
        	}

        	$user = $result->fetch_assoc();

        	return $user;
	}
}
