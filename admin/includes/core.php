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


// Detect is AKHelper exists
// ===============================================================
$old_akhelper_path = JPATH_PLUGINS.'/system/asikart_easyset/windwalker/init.php' ;
if( !file_exists($old_akhelper_path) && class_exists('AKHelper') ) {
	$message = 'Please disable Asikart Easyset plugin or upgrade to 2.6 later.' ;
	if(JRequest::getVar('option') == 'com_cpanel'){
		JError::raiseWarning(500, $message) ;
		return false;
	}else{
		$app = JFactory::getApplication() ;
		$app->redirect('index.php', $message);
	}
}



// Include WindWalker from libraries or component self.
// ===============================================================
if( !defined('AKPATH_ROOT') ) {
	$inner_ww_path 	= JPATH_ADMINISTRATOR . "/components/com_quickcontent/windwalker" ;
	$lib_ww_path	= JPATH_LIBRARIES . '/windwalker' ;
	
	if(file_exists($lib_ww_path.'/init.php')) {
		// From libraries
		$ww_path = $lib_ww_path ;
	}else{
		// From Component folder
		$ww_path = $inner_ww_path ;
	}
	
	
	
	// Init WindWalker
	// ===============================================================
	if(!file_exists($ww_path.'/init.php')) {
		$message = 'Please install WindWalker Framework libraries.' ;
		throw new Exception($message, 500) ;
	}
	include_once $ww_path.'/init.php' ;
}else{
	include_once AKPATH_ROOT.'/init.php' ;
}

include_once JPath::clean( JPATH_ADMINISTRATOR . "/components/com_quickcontent/helpers/quickcontent.php" ) ;
include_once JPath::clean( JPATH_ADMINISTRATOR . "/components/com_quickcontent/includes/loader.php" ) ;


// Set default option to path helper, then AKHelperPath will helpe us get admin path.
AKHelper::_('path.setOption', 'com_quickcontent') ;


// Set Component helper prefix, and AKProxy can use component helper first.
// If component helper and methods not exists, AKProxy will call AKHelper instead.
AKHelper::setPrefix('QuickcontentHelper') ;
AKHelper::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_quickcontent/helpers');
