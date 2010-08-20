<?php 
class Model implements Iterator
{
	/**
	 * The PDO statement object
	 */
	protected $_stmt = FALSE;
	
	/**
	 * Holds the array returned from fetch()
	 */
	protected $_data = array();
	
	/**
	 * The table name for loading data for this object type
	 */
	protected $_table;

	/**
	 * Static create() method for creating an object corresponding to a single row
	 */
	public static function create($value, $column='id')
	{
		// get_called_class() 
		// http://php.net/get_called_class
		// Only exists in PHP 5.3 and later
		$className = get_called_class();
		$model = new $className();
		if($model->_load($value, $column) == FALSE)
			return FALSE;
		else
			return $model;
	}
	
	public function __construct($id=FALSE)
	{
		if(is_array($id))
			$this->_data = $id;
		elseif($id !== FALSE)
			$this->_load($id);
	}
	
	/** 
	 * Load a single record from the database selecting $value matching the column $column (default id)
	 */
	protected function _load($value, $column='id')
	{
		$this->prepare('SELECT * FROM ' . $this->_table . ' WHERE `' . $column . '` = ?');
		$this->_data = $this->go($value);

		return is_array($this->_data) && count($this->_data) > 0;
	}

	/**
	 * Allow access to the row's data by using the getter of the Model object
	 */
	public function __get($key)
	{
		if(is_array($this->_data) && array_key_exists($key, $this->_data))
			return $this->_data[$key];
		else
			return NULL;
	}
	
	/** 
	 * Return the internal $_data array
	 */
	public function export()
	{
		return $this->_data;
	}

	public static function insert($data)
	{
		if(!is_array($data))
			throw new Exception('$data is not an array. insert() must be called with an array');
		
		$className = get_called_class();
		$model = new $className();
		
		$columns = array();
		$questionMarks = array();
		foreach($data as $key=>$val)
		{
			$columns[] = '`' . $key . '`';
			$params[] = '?';
		}
		$columns = implode(', ', $columns);
		$params = implode(', ', $params);
		
		$sql = 'INSERT INTO `' . $model->_table . '` (' . $columns . ') VALUES (' . $params . ')';
		$model->prepare($sql);
		
		$i = 1;
		foreach($data as $key=>$val)
			$model->bind($i++, $val);
			
		$model->execute();
		$insertID = $model->lastInsertID();
		if(!$insertID)
		{
			$error = $model->_stmt->errorInfo();
			$model->_error($error[2]);
		}
		else
			return $insertID;
	}

	public static function update($data, $val, $col='id')
	{
		if(!is_array($data))
			throw new Exception('$data is not an array. update() must be called with an array');
			
		$className = get_called_class();
		$model = new $className();
		
		$fields = array();
		foreach($data as $k=>$v)
			$fields[] = '`' . $k . '` = ?';
		$fields = implode(', ', $fields);
		
		$sql = 'UPDATE `' . $model->_table . '` SET ' . $fields . ' WHERE `' . $col . '` = ?';
		$model->prepare($sql);

		$i = 1;
		foreach($data as $k=>$v)
			$model->bind($i++, $v);
		$model->bind($i, $val);
			
		$model->execute();
	}
	
	/**
	 * PDO methods
	 */
	
	protected function prepare($sql)
	{
		$this->_stmt = db()->prepare($sql);
	}
	
	protected function bind($param, $value, $type=NULL)
	{
		if($type == NULL)
		{
			if(is_bool($value))
				$type = PDO::PARAM_BOOL;
			elseif(is_integer($value) || is_long($value))
				$type = PDO::PARAM_INT;
			else
				$type = PDO::PARAM_STR;
		}
		$this->_stmt->bindParam($param, $value, $type);
	}
	
	protected function execute()
	{
		if($this->_stmt == FALSE)
			$this->_error('execute() called with no statement prepared');
		
		$this->_stmt->execute();

		$status = $this->_stmt->errorInfo();
		if($status[0] != 0)
			$this->_error($status[2]);
	}

	protected function fetch()
	{
		return $this->_stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	protected function lastInsertID()
	{
		return db()->lastInsertId();
	}
	
	
	/**
	 * Iterator methods
	 * http://php.net/iterator
	 */
	private $_iterator = 0;
	private $_iteratorData;
	
	// Return the current row as a Model object of the same type
	public function current()
	{	
		$className = get_called_class();
		$model = new $className($this->_iteratorData);
		return $model;
	}
	public function next()
	{
		return $this->current();
	}
	// When doing a foreach() loop, valid() is called before current() so we need
	// to fetch the next row here in order to know if it's going to be valid
	public function valid()
	{
		$this->_iteratorData = $this->fetch();
		$this->_iterator++;
		return (boolean)$this->_iteratorData;
	}
	public function rewind()
	{
	}
	public function key()
	{
		return $this->_iterator;
	}
	
	
	
	/**
	 * Shortcut for bind, execute and fetch when only one parameter is being bound and only one row is expected in the result
	 */
	protected function go($value, $type=NULL)
	{
		$this->bind(1, $value, $type);
		$this->execute();
		// TODO: check if any valid rows were returned
		// if($this->...)
		return $this->fetch();
	}
	
	private function _handleException($e)
	{
		$this->_error($e->getMessage());
	}

	private function _error($str)
	{
		throw new Exception('Database error: ' . $str);
	}
}
?>