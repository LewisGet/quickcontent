<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_quickcontent
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

// no direct access
defined('_JEXEC') or die;

$doc 	= JFactory::getDocument();
$app 	= JFactory::getApplication();
$lang 	= JFactory::getLanguage();



// Define
// ========================================================================
define('QUICKCONTENT_SITE' , JPATH_COMPONENT_SITE );
define('QUICKCONTENT_ADMIN', JPATH_COMPONENT_ADMINISTRATOR);
define('QUICKCONTENT_SELF' , JPATH_COMPONENT);



// Include Helpers
// ========================================================================

// Core init, it can use by module, plugin or other component.
include_once JPath::clean( JPATH_ADMINISTRATOR . "/components/com_quickcontent/includes/core.php" ) ;


// Some useful settings
if( $app->isSite() ){
	
	// Include Admin language as global language.
	$lang->load('', JPATH_ADMINISTRATOR);
	$lang->load('com_quickcontent', JPATH_COMPONENT_ADMINISTRATOR );
	QuickcontentHelper::_('lang.loadAll', $lang->getTag());
	
	
	// Include Joomla! admin css
	QuickcontentHelper::_('include.sortedStyle', 'includes/css');
	
	
	// set Base to fix toolbar anchor bug
	$doc->setBase( JFactory::getURI()->toString() );
	
}else{
	QuickcontentHelper::_('lang.loadAll', $lang->getTag());
	QuickcontentHelper::_('include.sortedStyle', 'includes/css');
}


// Detect version
QuickcontentHelper::_('plugin.attachPlugins');

