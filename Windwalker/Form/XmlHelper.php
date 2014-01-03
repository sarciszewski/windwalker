<?php
/**
 * Part of joomla321 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Windwalker\Form;

/**
 * Class XmlHelper
 *
 * @since 1.0
 */
class XmlHelper
{
	static protected $falseValue = array(
		'disbaled',
		'false',
		'null',
		'0',
		'no',
		'none'
	);

	static protected $trueValue = array(
		'true',
		'yes',
		'1'
	);

	/**
	 * getAttribute
	 *
	 * @param \SimpleXMLElement $xml
	 * @param string            $attr
	 * @param null              $default
	 *
	 * @return mixed
	 */
	public static function getAttribute(\SimpleXMLElement $xml, $attr, $default = null)
	{
		$value = (string) $xml[$attr];

		if (!$value)
		{
			return $default;
		}

		return $value;
	}

	/**
	 * geetAttr
	 *
	 * @param \SimpleXMLElement $xml
	 * @param string            $attr
	 * @param null              $default
	 *
	 * @return bool
	 */
	public static function get(\SimpleXMLElement $xml, $attr, $default = null)
	{
		return self::getAttribute($xml, $attr, $default);
	}

	/**
	 * getBool
	 *
	 * @param \SimpleXMLElement $xml
	 * @param string            $attr
	 * @param null              $default
	 *
	 * @return boolean
	 */
	public static function getBool(\SimpleXMLElement $xml, $attr, $default = null)
	{
		$value = self::getAttribute($xml, $attr, $default);

		if (in_array($value, self::$falseValue) || !$value)
		{
			return false;
		}

		return true;
	}

	/**
	 * getFalse
	 *
	 * @param \SimpleXMLElement $xml
	 * @param string            $attr
	 * @param null              $default
	 *
	 * @return bool
	 */
	public static function getFalse(\SimpleXMLElement $xml, $attr, $default = null)
	{
		return !self::getBool($xml, $attr, $default);
	}
}