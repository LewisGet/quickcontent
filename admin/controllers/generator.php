<?php
/**
 * @version     1.0.0
 * @package     com_quickcontent
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * List list controller class.
 */
class QuickcontentControllerGenerator extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'generator', $prefix = 'QuickcontentModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function generate(){
		
		$model = $this->getModel(  );
		$model->saveContent();
		
		$msg = JText::_('COM_QUICKCONTENT_GENERATE_SUCCESS') ;
		
		$this->setRedirect( 'index.php?option=com_quickcontent' , $msg );
		
	}
	
	public function deleteAll(){
		$model = $this->getModel(  );
		$model->deleteAll();
		
		$msg = JText::_('COM_QUICKCONTENT_CLEAR_WHOLE_SITE_SUCCESS') ;
		
		$this->setRedirect( 'index.php?option=com_quickcontent' , $msg );
	}
	
	public function restore(){
		
		$model = $this->getModel(  );
		$model->restore();
		
		$msg = JText::_('COM_QUICKCONTENT_RESTORE_SUCCESS') ;
		
		$this->setRedirect( 'index.php?option=com_quickcontent' , $msg );
		
	}
}