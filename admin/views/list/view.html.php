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

include_once AKPATH_COMPONENT.'/viewitem.php' ;

/**
 * View to edit
 */
class QuickcontentViewList extends AKViewItem
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected 	$text_prefix = 'COM_QUICKCONTENT';
	protected 	$items;
	protected 	$pagination;
	protected 	$state;
	
	public		$option 	= 'com_quickcontent' ;
	public		$list_name 	= 'lists' ;
	public		$item_name 	= 'list' ;
	public		$sort_fields ;
	
	

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication() ;
		
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');
		$this->fields	= $this->get('Fields');
		$this->formParams = $this->get( 'FormParams' );
		$this->canDo	= AKHelper::getActions($this->option);
		$this->params	= $this->state->get('params');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		// load content language
		$lang = JFactory::getLanguage();
		
		$lang->load( 'com_content' , JPATH_BASE, null, true, true );
		$lang->load( 'com_menus' , JPATH_BASE, null, true, true );
		
		// set Editor
		$editor = JFactory::getEditor( $this->params->get( 'editor' , 'tinymce' ) );
		$params['mode'] = 2 ;
		$this->editor = $editor->display ( 'jform[content]' , $this->item->content , '650px' , '500px' , 650 , 500 , false , null , null ,null , $params );
		

		parent::displayWithPanel($tpl) ;
	}

	
	
	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		AKToolBarHelper::title( JText::_('COM_QUICKCONTENT_TITLE_ITEM_EDIT'), 'article-add.png');
		
		parent::addToolbar();
	}
	
	
	
	/*
	 * function handleFields
	 * @param 
	 */
	
	public function handleFields()
	{
		$form = $this->form ;
		
		parent::handleFields();
		
		// for Joomla! 3.0
		if(JVERSION >= 3) {
			
			// $form->removeField('name', 'fields');
			
		}else{
			
			// $form->removeField('name', 'fields');
			
		}
		
	}
	
	
	public function showParams( $id ) {
		$fieldSets = $this->formParams->$id->getFieldsets( $id );
		$keys = array_keys($fieldSets);
		
		echo QuickcontentHelper::_('panel.startSlider','menu-sliders-'.$id, array( 'active' => $id.'-'.$keys[0].'-options' )); 
		
		foreach ($fieldSets as $name => $fieldSet) :
			$label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_MENUS_'.$name.'_FIELDSET_LABEL';
			echo QuickcontentHelper::_('panel.addSlide', 'menu-sliders-'.$id, JText::_($label), $id.'-'.$name.'-options');
				if (isset($fieldSet->description) && trim($fieldSet->description)) :
					echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
				endif;
				?>
			<div class="clr"></div>
			<fieldset class="panelform">

				<?php foreach ($this->formParams->$id->getFieldset($name) as $field) : ?>
					<div class="control-group">
						<span class="control-label">
							<?php echo $field->label; ?>
						</span>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>

			</fieldset>
			
		<?php
			echo QuickcontentHelper::_('panel.endSlide');
		endforeach;
		
	 echo QuickcontentHelper::_('panel.endSlider'); 
	}
}
