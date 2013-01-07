<?php
/**
 * @version     1.0.0
 * @package     com_quickcontent
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

if( JVERSION >= 3){
	JHtml::_('formbehavior.chosen', 'select');

}else{
	//FlowerHelper::_('include.bluestork');
	// FlowerHelper::_('include.fixBootstrapToJoomla');
}

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'form.cancel' || document.formvalidator.isValid(document.id('form-form'))) {
			Joomla.submitform(task, document.getElementById('form-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_quickcontent&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="form-form" class="form-validate">
	<div class="width-60 fltlft span7">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_QUICKCONTENT_LEGEND_FORM'); ?></legend>
			
			<div class="control-group">
				<?php echo $this->form->getLabel('id', 'basic'); ?>
				<div class="controls">
					<?php echo $this->form->getInput('id', 'basic'); ?>
				</div>
			</div>
			
			<div class="control-group">
				<?php echo $this->form->getLabel('title', 'basic'); ?>
				<div class="controls">
					<?php echo $this->form->getInput('title', 'basic'); ?>
				</div>
			</div>
			
			<div class="control-group">
				<?php echo $this->form->getLabel('published', 'basic'); ?>
				<div class="controls">
					<?php echo $this->form->getInput('published', 'basic'); ?>
				</div>
			</div>
			
			<div class="control-group">
				<?php echo $this->form->getLabel('checked_out', 'basic'); ?>
				<div class="controls">
					<?php echo $this->form->getInput('checked_out', 'basic'); ?>
				</div>
			</div>
			
			<div class="control-group">
				<?php echo $this->form->getLabel('checked_out_time', 'basic'); ?>
				<div class="controls">
					<?php echo $this->form->getInput('checked_out_time', 'basic'); ?>
				</div>
			</div>
			
		</fieldset>
		<fieldset class="adminform">
			<div><?php echo $this->form->getLabel('content'); ?></div>
			<div><?php echo $this->editor; ?></div>
			
		</fieldset>
	</div>


	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	
	<div class="width-40 span5 fltlft form-horizontal">
	
	<?php if( JVERSION >= 3 ): ?>
	<!-- Tab Buttons -->
	<ul class="nav nav-tabs">

		<li class="active">
			<a href="#basic" data-toggle="tab"><?php echo JText::_('基本設置'); ?></a>
		</li>
		
		<li class="">
			<a href="#category-list" data-toggle="tab"><?php echo JText::_('分類清單'); ?></a>
		</li>
		
		<li class="">
			<a href="#category-blog" data-toggle="tab"><?php echo JText::_('分類部落格'); ?></a>
		</li>
		
		<li class="">
			<a href="#article" data-toggle="tab"><?php echo JText::_('文章設定'); ?></a>
		</li>

	</ul>
	<?php endif; ?>
	
	<!-- Tab Content -->
	
	<?php echo QuickcontentHelper::_( 'panel.startTabs' , 'quickcontent', array( 'active' => 'basic' )  ); ?>
	
	<?php echo QuickcontentHelper::_( 'panel.addPanel' , 'quickcontent', '基本設置' , 'basic' ); ?>
	
	
		<?php echo QuickcontentHelper::_('panel.startSlider','basic-slides', array('active' => 'basic-basic-options')); ?>
		<?php echo QuickcontentHelper::_('panel.addSlide', 'basic-slides', '基本' , 'basic-basic-options'); ?>
		<fieldset class="panelform">
			<div class="control-group">
				<?php echo $this->form->getLabel('menutype', 'basic'); ?>
				<div class="controls">
					<?php echo $this->form->getInput('menutype', 'basic'); ?>
				</div>
			</div>
			
			<div class="control-group">
				<?php echo $this->form->getLabel('delete_existing', 'basic'); ?>
				<div class="controls">
					<?php echo $this->form->getInput('delete_existing', 'basic'); ?>
				</div>
			</div>
			
			<div class="control-group">
				<?php echo $this->form->getLabel('category_menutype', 'basic'); ?>
				<div class="controls">
					<?php echo $this->form->getInput('category_menutype', 'basic'); ?>
				</div>
			</div>
			
		</fieldset>
		<?php echo QuickcontentHelper::_('panel.endSlide'); ?>
		<?php echo QuickcontentHelper::_('panel.endSlider'); ?>
		
		
		<?php echo QuickcontentHelper::_('panel.endPanel' ) ; ?>
		
		
	<?php echo QuickcontentHelper::_( 'panel.addPanel' , 'quickcontent', '分類清單' , 'category-list' ); ?>
	
	<!-- CATEGORY LIST PARAMS -->
	<?php $this->showParams( 'list' ) ; ?>
	<!-- CATEGORY LIST PARAMS -->
	
	<?php echo QuickcontentHelper::_('panel.endPanel' ) ; ?>
	
	
	<?php echo QuickcontentHelper::_( 'panel.addPanel' , 'quickcontent', '分類部落格' , 'category-blog' ); ?>
	
	<!-- CATEGORY BLOG PARAMS -->
	<?php $this->showParams( 'blog' ) ; ?>
	<!-- CATEGORY BLOG PARAMS -->
	<?php echo QuickcontentHelper::_('panel.endPanel' ) ; ?>
	
	
		<?php echo QuickcontentHelper::_( 'panel.addPanel' , 'quickcontent' , '文章' , 'article' ); ?>
		
		<!-- CONTENT PARAMS -->
		<?php $this->showParams( 'article' ) ; ?>
		<!-- CONTENT PARAMS -->
		
		<?php echo QuickcontentHelper::_('panel.endPanel' ) ; ?>
		
		<?php echo QuickcontentHelper::_( 'panel.endTabs' ); ?>
	</div>
	
	<div class="clr"></div>
</form>