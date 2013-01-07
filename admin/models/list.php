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

include_once AKPATH_COMPONENT.'/modeladmin.php' ;

/**
 * Quickcontent model.
 */
class QuickcontentModelList extends AKModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected 	$text_prefix = 'COM_QUICKCONTENT';
	
	public 		$component = 'quickcontent' ;
	public 		$item_name = 'list' ;
	public 		$list_name = 'lists' ;
	
	

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$form = parent::getForm($data, $loadData) ;
		
		return $form ;
	}
	
	
	/*
	 * function getFields
	 * @param 
	 */
	
	public function getFields()
	{
		$fields = parent::getFields();
		
		return $fields ;
	}
	
	
	public function getFormParams($data = array(), $loadData = true) {
		$form = new JObject();
		$form->blog 	= $this->blogForm ;
		$form->list 	= $this->listForm ;
		$form->article 	= $this->articleForm ;
		return $form ;
	}
	

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		$data = parent::loadFormData();
		
		return $data ;
	}

	
	
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if($item = parent::getItem($pk)){
			
			
			
			return $item ;	
		}

		return false;
	}
	
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		parent::populateState();
	}
	
	
	
	/**
     * Method to allow derived classes to preprocess the form.
     *
     * @param   JForm   $form   A JForm object.
     * @param   mixed   $data   The data expected for the form.
     * @param   string  $group  The name of the plugin group to import (defaults to "content").
     *
     * @return  void 
     *
     * @see     JFormField
     * @since   11.1
     * @throws  Exception if there is an error in the form event.
     */
    protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		$blogXML 	= JPath::clean(JPATH_ROOT.DS.'components/com_content/views/category/tmpl/blog.xml');
		$listXML 	= JPath::clean(JPATH_ROOT.DS.'components/com_content/views/category/tmpl/default.xml');
		$articleXML	= JPath::clean(JPATH_ROOT.DS.'components/com_content/views/article/tmpl/default.xml');
		$menuXML	= JPath::clean(JPATH_BASE.DS.'components/com_menus/models/forms/item_component.xml' );
		
		// set list params
		$listParams = simplexml_load_file( $listXML );
		$listParams->fields[1]['name'] = 'list' ;
		
		// set blog params
		$blogParams = simplexml_load_file( $blogXML );
		$blogParams->fields[1]['name'] = 'blog' ;
		
		// set article params
		$articleParams = simplexml_load_file( $articleXML );
		$articleParams->fields[1]['name'] = 'article' ;
		
		// create Form
		$this->listForm = JForm::getInstance( 'list' , $listParams->asXML() , array( 'control' => 'jform' ) , true , '/metadata' ) ;
		$this->blogForm = JForm::getInstance( 'blog' , $blogParams->asXML() , array('control' => 'jform') , true , '/metadata' ) ;
		$this->articleForm = JForm::getInstance( 'article' , $articleParams->asXML() , array('control' => 'jform') , true , '/metadata' ) ;
		
		// set menu xml
		$menuParams = simplexml_load_file( $menuXML );
		
		$menuParams->fields[0]['name'] = 'list' ;
		$this->listForm->load( $menuParams->asXML() , true , '/form' );
		
		$menuParams->fields[0]['name'] = 'blog' ;
		$this->blogForm->load( $menuParams->asXML() , true , '/form' );
		
		$menuParams->fields[0]['name'] = 'article' ;
		$this->articleForm->load( $menuParams->asXML() , true , '/form' );
		
		
		// bind data
		$data->list = json_decode( $data->list ) ;
		$data->blog = json_decode( $data->blog ) ;
		$data->article = json_decode( $data->article ) ;
		
		$this->listForm->bind( $data );
		$this->blogForm->bind( $data );
		$this->articleForm->bind( $data );
		
		return parent::preprocessForm($form, $data, $group);
	}
	
	

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		$post = JRequest::get( 'post' );
		
		$post['jform']['list'] = new JRegistry($post['jform']['list']) ;
		$table->list = $post['jform']['list'] = $post['jform']['list']->toString();
		
		$post['jform']['blog'] = new JRegistry($post['jform']['blog']) ;
		$table->blog = $post['jform']['blog'] = $post['jform']['blog']->toString();
		
		$post['jform']['article'] = new JRegistry($post['jform']['article']) ;
		$table->article = $post['jform']['article'] = $post['jform']['article']->toString();
		
		JRequest::setVar( 'jform' , $post['jform'] ) ;
		
		$table->bind( $post['jform'] );
		
		return parent::prepareTable($table);
	}
	
	
}