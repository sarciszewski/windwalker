<?php
/**
 * @package     Windwalker.Framework
 * @subpackage  AKHelper
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

// no direct access
defined('_JEXEC') or die;

/**
 * AKHelper base class.
 *
 * @package     Windwalker.Framework
 * @subpackage  AKHelper
 */
class AKHelper extends AKProxy
{
    /**
     * An singleton pattern array to store Config JRegistry instance.
     *
     * @var array 
     */
    static $config = array();
    
    /**
     * Store component version to get.
     *
     * @var integer 
     */
    static $version = 0;
    
    /**
     * Gets a list of the actions that can be performed.
     *
     * @return   JObject
     */
    public static function getActions($option)
    {
        $user   = JFactory::getUser();
        $result    = new JObject;

        $assetName = $option;

        $actions = array(
            'core.admin', 
            'core.manage', 
            'core.create', 
            'core.edit', 
            'core.edit.own', 
            'core.edit.state', 
            'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action,    $user->authorise($action, $assetName));
        }

        return $result;
    }

    /**
     * Print Array or Object as tree node.
     * 
     * @param    mixed    $data    Array or Object to print.
     */
    public static function show($data)
    {
        echo '<pre>'.print_r($data, 1).'</pre>' ;
    }

    /**
     * Detect is this page are frontpage?
     *
     * @return    boolean    Is frontpage?
     */
    public static function isHome() {
        $juri           = JFactory::getURI();
        $current_url    = $juri->toString();
        
        if( $juri->base()==$current_url || $juri->base().'index.php' == $current_url ) 
            return true;
        else 
            return false;
    }

    /**
     * Get Component params. This is a proxy to AKHelperSystem::getParams.
     * 
     * @param    string     $option    Component element name, eg: com_extension.
     *
     * @return   JRegistry  A JRegistry object.
     */
    public static function getParams($option = null)
    {
        return AKHelper::_('system.getParams', $option) ;
    }
}

if(!class_exists('AK')){
    
    /**
    * An alias for AKHelper base class.
    *
    * @package     Windwalker.Framework
    * @subpackage  AKHelper
    */
    class AK extends AKHelper {}
}