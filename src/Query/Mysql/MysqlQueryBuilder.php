<?php
/**
 * Part of Windwalker project. 
 *
 * @copyright  Copyright (C) 2008 - 2014 Asikart.com. All rights reserved.
 * @license    GNU Lesser General Public License version 2.1 or later.
 */

namespace Windwalker\Query\Mysql;

use Windwalker\Query\AbstractQueryBuilder;
use Windwalker\Query\Query;
use Windwalker\Query\QueryElement;

/**
 * Class MysqlQueryBuilder
 *
 * @since 2.0
 */
abstract class MysqlQueryBuilder extends AbstractQueryBuilder
{
	const PRIMARY  = 'PRIMARY KEY';
	const INDEX    = 'INDEX';
	const UNIQUE   = 'UNIQUE';
	const SPATIAL  = 'SPATIAL';
	const FULLTEXT = 'UNIQUE';
	const FOREIGN  = 'FOREIGN KEY';

	/**
	 * Property query.
	 *
	 * @var  Query
	 */
	public static $query = null;

	/**
	 * showDatabases
	 *
	 * @param string $where
	 *
	 * @return  string
	 */
	public static function showDatabases($where = null)
	{
		$where = $where ? new QueryElement('WHERE', $where, 'AND') : null;

		return 'SHOW DATABASES ' . $where;
	}

	/**
	 * createDatabase
	 *
	 * @param string $name
	 * @param bool   $isNotExists
	 * @param string $charset
	 * @param string $collate
	 *
	 * @return  string
	 */
	public static function createDatabase($name, $isNotExists = false, $charset = null, $collate = null)
	{
		$query = static::getQuery();

		return static::build(
			'CREATE DATABASE',
			$isNotExists ? 'IF NOT EXISTS' : null,
			$query->quoteName($name),
			$charset ? 'CHARACTER SET=' . $query->quote($charset) : null,
			$collate ? 'COLLATE=' . $query->quote($collate) : null
		);
	}

	/**
	 * dropTable
	 *
	 * @param string $db
	 * @param bool   $ifExist
	 *
	 * @return  string
	 */
	public static function dropDatabase($db, $ifExist = false)
	{
		$query = static::getQuery();

		return static::build(
			'DROP DATABASE',
			$ifExist ? 'IF EXISTS' : null,
			$query->quoteName($db)
		);
	}

	/**
	 * showTableColumn
	 *
	 * @param string       $table
	 * @param bool         $full
	 * @param string|array $where
	 *
	 * @return  string
	 */
	public static function showTableColumns($table, $full = false, $where = null)
	{
		$query = static::getQuery();

		return static::build(
			'SHOW',
			$full ? 'FULL' : false,
			'COLUMNS FROM',
			$query->quoteName($table),
			$where ? new QueryElement('WHERE', $where, 'AND') : null
		);
	}

	/**
	 * showDbTables
	 *
	 * @param string $dbname
	 * @param string $where
	 *
	 * @return  string
	 */
	public static function showDbTables($dbname, $where = null)
	{
		$query = static::getQuery();

		return static::build(
			'SHOW',
			'TABLE STATUS FROM',
			$query->quoteName($dbname),
			$where ? new QueryElement('WHERE', $where, 'AND') : null
		);
	}

	/**
	 * createTable
	 *
	 * @param string       $name
	 * @param array        $columns
	 * @param array|string $pks
	 * @param array        $keys
	 * @param null         $autoIncrement
	 * @param bool         $ifNotExists
	 * @param string       $engine
	 * @param string       $defaultCharset
	 *
	 * @throws \InvalidArgumentException
	 * @return  string
	 */
	public static function createTable($name, $columns, $pks = array(), $keys = array(), $autoIncrement = null,
		$ifNotExists = true, $engine = 'InnoDB', $defaultCharset = 'utf8')
	{
		$query = static::getQuery();
		$cols = array();
		$engine = $engine ? : 'InnoDB';

		foreach ($columns as $cName => $details)
		{
			$details = (array) $details;

			array_unshift($details, $query->quoteName($cName));

			$cols[] = call_user_func_array(array(get_called_class(), 'build'), $details);
		}

		if (!is_array($keys))
		{
			throw new \InvalidArgumentException('Keys should be an array');
		}

		if ($pks)
		{
			$pks = array(
				'type' => 'PRIMARY KEY',
				'columns' => (array) $pks
			);

			array_unshift($keys, $pks);
		}

		foreach ($keys as $key)
		{
			$define = array(
				'type' => 'KEY',
				'name' => null,
				'columns' => array(),
				'comment' => ''
			);

			if (!is_array($key))
			{
				throw new \InvalidArgumentException('Every key data should be an array with "type", "name", "columns"');
			}

			$define = array_merge($define, $key);

			$cols[] = $define['type'] . ' ' . static::buildIndexDeclare($define['name'], $define['columns']);
		}

		$cols = "(\n" . implode(",\n", $cols) . "\n)";

		return static::build(
			'CREATE TABLE',
			$ifNotExists ? 'IF NOT EXISTS' : null,
			$query->quoteName($name),
			$cols,
			'ENGINE=' . $engine,
			$autoIncrement ? 'AUTO_INCREMENT=' . $autoIncrement : null,
			$defaultCharset ? 'DEFAULT CHARSET=' . $defaultCharset : null
		);
	}

	/**
	 * dropTable
	 *
	 * @param string $table
	 * @param bool   $ifExist
	 * @param string $option
	 *
	 * @return  string
	 */
	public static function dropTable($table, $ifExist = false, $option = '')
	{
		$query = static::getQuery();

		return static::build(
			'DROP TABLE',
			$ifExist ? 'IF EXISTS' : null,
			$query->quoteName($table),
			$option
		);
	}

	/**
	 * alterColumn
	 *
	 * @param string $operation
	 * @param string $table
	 * @param string $column
	 * @param string $type
	 * @param bool   $signed
	 * @param bool   $allowNull
	 * @param null   $default
	 * @param null   $position
	 * @param string $comment
	 *
	 * @return  string
	 */
	public static function alterColumn($operation, $table, $column, $type = 'text', $signed = true, $allowNull = true, $default = null,
		$position = null, $comment = '')
	{
		$query = static::getQuery();

		$column = $query->quoteName((array) $column);

		return static::build(
			'ALTER TABLE',
			$query->quoteName($table),
			$operation,
			implode(' ', $column),
			$type ? : 'text',
			$signed ? null : 'UNSIGNED',
			$allowNull ? null : 'NOT NULL',
			!is_null($default) ? 'DEFAULT ' . $query->quote($default) : null,
			$comment ? 'COMMENT ' . $query->quote($comment) : null,
			static::handleColumnPosition($position)
		);
	}

	/**
	 * Add column
	 *
	 * @param string $table
	 * @param string $column
	 * @param string $type
	 * @param bool   $signed
	 * @param bool   $allowNull
	 * @param string $default
	 * @param string $position
	 * @param string $comment
	 *
	 * @return  string
	 */
	public static function addColumn($table, $column, $type = 'text', $signed = true, $allowNull = true, $default = null,
		$position = null, $comment = '')
	{
		return static::alterColumn('ADD', $table, $column, $type, $signed, $allowNull, $default, $position, $comment);
	}

	/**
	 * changeColumn
	 *
	 * @param string $table
	 * @param string $oldColumn
	 * @param string $newColumn
	 * @param string $type
	 * @param bool   $signed
	 * @param bool   $allowNull
	 * @param null   $default
	 * @param string $position
	 * @param string $comment
	 *
	 * @return  string
	 */
	public static function changeColumn($table, $oldColumn, $newColumn, $type = 'text', $signed = true, $allowNull = true, $default = null,
		$position = null, $comment = '')
	{
		$column = array($oldColumn, $newColumn);

		return static::alterColumn('CHANGE', $table, $column, $type, $signed, $allowNull, $default, $position, $comment);
	}

	/**
	 * modifyColumn
	 *
	 * @param string $table
	 * @param string $column
	 * @param string $type
	 * @param bool   $signed
	 * @param bool   $allowNull
	 * @param null   $default
	 * @param string $position
	 * @param string $comment
	 *
	 * @return  string
	 */
	public static function modifyColumn($table, $column, $type = 'text', $signed = true, $allowNull = true, $default = null,
		$position = null, $comment = '')
	{
		return static::alterColumn('MODIFY', $table, $column, $type, $signed, $allowNull, $default, $position, $comment);
	}

	/**
	 * dropColumn
	 *
	 * @param string $table
	 * @param string $column
	 *
	 * @return  string
	 */
	public static function dropColumn($table, $column)
	{
		$query = static::getQuery();

		return static::build(
			'ALTER TABLE',
			$query->quoteName($table),
			'DROP',
			$query->quoteName($column)
		);
	}

	/**
	 * addIndex
	 *
	 * @param string       $table
	 * @param string       $type
	 * @param string       $name
	 * @param string|array $columns
	 * @param string       $comment
	 *
	 * @return  string
	 *
	 * @throws \InvalidArgumentException
	 */
	public static function addIndex($table, $type, $name, $columns, $comment = null)
	{
		$query = static::getQuery();
		$cols  = static::buildIndexDeclare($name, $columns);

		$comment = $comment ? 'COMMENT ' . $query->quote($comment) : '';

		return static::build(
			'ALTER TABLE',
			$query->quoteName($table),
			'ADD',
			strtoupper($type),
			$cols,
			$comment
		);
	}

	/**
	 * buildIndexDeclare
	 *
	 * @param string $name
	 * @param array  $columns
	 *
	 * @return  string
	 *
	 * @throws \InvalidArgumentException
	 */
	public static function buildIndexDeclare($name, $columns)
	{
		$query = static::getQuery();
		$cols  = array();

		foreach ((array) $columns as $key => $val)
		{
			if (is_numeric($key))
			{
				$cols[] = $query->quoteName($val);
			}
			else
			{
				if (!is_numeric($val))
				{
					$string = is_string($val) ? ' ' . $query->quote($val) : '';

					throw new \InvalidArgumentException(sprintf('Index length should be number, (%s)%s given.', gettype($val), $string));
				}

				$cols[] = $query->quoteName($key) . '(' . $val . ')';
			}
		}

		$cols = '(' . implode(', ', $cols) . ')';

		$name = $name ? $query->quoteName($name) . ' ' : '';

		return $name . $cols;
	}

	/**
	 * dropIndex
	 *
	 * @param string $table
	 * @param string $type
	 * @param string $name
	 *
	 * @return  string
	 */
	public static function dropIndex($table, $type, $name)
	{
		$query = static::getQuery();

		return static::build(
			'ALTER TABLE',
			$query->quoteName($table),
			'DROP',
			strtoupper($type),
			$query->quoteName($name)
		);
	}

	/**
	 * build
	 *
	 * @return  string
	 */
	public static function build()
	{
		$args = func_get_args();

		$sql = array();

		foreach ($args as $arg)
		{
			if ($arg === '' || $arg === null || $arg === false)
			{
				continue;
			}

			$sql[] = $arg;
		}

		return implode(' ', $args);
	}

	/**
	 * handleColumnPosition
	 *
	 * @param string $position
	 *
	 * @return  string
	 */
	protected static function handleColumnPosition($position)
	{
		$query = static::getQuery();

		if (!$position)
		{
			return null;
		}

		$posColumn = '';

		$position = trim($position);

		if (strpos(strtoupper($position), 'AFTER') !== false)
		{
			list($position, $posColumn) = explode(' ', $position, 2);

			$posColumn = $query->quoteName($posColumn);
		}

		return $position . ' ' . $posColumn;
	}

	/**
	 * replace
	 *
	 * @param string $name
	 * @param array  $columns
	 * @param array  $values
	 *
	 * @return  string
	 */
	public static function replace($name, $columns = array(), $values = array())
	{
		$query = new MysqlQuery;

		$query = (string) $query->insert($query->quoteName($name))
			->columns($columns)
			->values($values);

		$query = substr(trim($query), 6);

		return 'REPLACE' . $query;
	}

	/**
	 * getQuery
	 *
	 * @param bool $new
	 *
	 * @return  Query
	 */
	public static function getQuery($new = false)
	{
		if (!static::$query || $new)
		{
			static::$query = new MysqlQuery;
		}

		return static::$query;
	}
}

