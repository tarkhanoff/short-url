<?php

class UrlsTable
{
	protected $tableName = 'urls';
	protected $fields = array('id', 'created_at', 'short_name', 'full_url', 'used');
	
	public function findUrl($shortName)
	{
		$db = Database::getInstance();
		
		$shortName = $db->escapeString($shortName);
		
		$q = 'SELECT * FROM `urls` WHERE `short_name` LIKE "' . $shortName . '"';
		return $db->fetch($q);
	}
	
	/**
	 * Removes old entries (more than 15 days old)
	 */
	public function cleanup()
	{
		$db = Database::getInstance();
		
		$q = 'DELETE FROM `urls` WHERE DATEDIFF(NOW(), `created_at`) >= 15';
		return $db->exec($q);
	}
	
	public function updateEntry($entry)
	{
		if (!is_array($entry) || !isset($entry['id']))
			return false;
		
		$db = Database::getInstance();
		
		$params = array();
		foreach ($this->fields as $field)
		{
			if (($field != 'id') && isset($entry[$field]))
			{
				$params[] = '`' . $field . '` = "' . $db->escapeString($entry[$field]) . '"';
			}
		}
		
		$q = 'UPDATE `' . $this->tableName . '` SET ' . implode(', ', $params) . ' WHERE `id` = ' . (int)$entry['id'];
		return $db->exec($q);
	}
	
	public function insertEntry($entry)
	{
		if (!is_array($entry))
			return false;
		
		$db = Database::getInstance();
		
		$names = array();
		$values = array();
		foreach ($this->fields as $field)
		{
			if (($field != 'id') && isset($entry[$field]))
			{
				$names[] = '`' . $field . '`';
				$values[] = '"' . $db->escapeString($entry[$field]) . '"';
			}
		}
		
		$q = 'INSERT INTO `' . $this->tableName . '` (' . implode(', ', $names) . ') VALUES (' . implode(', ', $values) . ')';
		return $db->exec($q);
	}
}