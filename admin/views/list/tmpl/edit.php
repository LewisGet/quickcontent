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
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_QUICKCONTENT_LEGEND_FORM'); ?></legend>
			<ul class="adminformlist">

            
			<li><?php echo $this->form->getLabel('id', 'basic'); ?>
			<?php echo $this->form->getInput('id', 'basic'); ?></li>
			
			<li><?php echo $this->form->getLabel('title', 'basic'); ?>
			<?php echo $this->form->getInput('title', 'basic'); ?></li>
			
            <li><?php echo $this->form->getLabel('published', 'basic'); ?>
                    <?php echo $this->form->getInput('published', 'basic'); ?></li>
					<li><?php echo $this->form->getLabel('checked_out', 'basic'); ?>
                    <?php echo $this->form->getInput('checked_out', 'basic'); ?></li>
					<li><?php echo $this->form->getLabel('checked_out_time', 'basic'); ?>
                    <?php echo $this->form->getInput('checked_out_time', 'basic'); ?></li>


            </ul>
		</fieldset>
		<fieldset class="adminform">
			<div><?php echo $this->form->getLabel('content'); ?></div>
			<div><?php echo $this->editor; ?></div>
			
		</fieldset>
	</div>


	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	
	<div class="width-40 fltlft">
	
	<?php echo JHtml::_( 'tabs.start' , 'quickcontent' ); ?>
	
	<?php echo JHtml::_( 'tabs.panel' , '基本設置' , 'basic' ); ?>
	
		<?php echo JHtml::_('sliders.start','basic-slides'); ?>
		<?php echo JHtml::_('sliders.panel', '基本' , $name.'-options'); ?>
		<fieldset class="panelform">
			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('menutype', 'basic').$this->form->getInput('menutype', 'basic'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('delete_existing', 'basic').$this->form->getInput('delete_existing', 'basic'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('category_menutype', 'basic').$this->form->getInput('category_menutype', 'basic'); ?>
				</li>
			</ul>
		</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>
	<?php echo JHtml::_( 'tabs.panel' , '分類清單' , 'categoty-list' ); ?>
	
	<!-- CATEGORY LIST PARAMS -->
	<?php $this->showParams( 'list' ) ; ?>
	<!-- CATEGORY LIST PARAMS -->
	
	<?php echo JHtml::_( 'tabs.panel' , '分類部落格' , 'category-blog' ); ?>
	
	<!-- CATEGORY BLOG PARAMS -->
	<?php $this->showParams( 'blog' ) ; ?>
	<!-- CATEGORY BLOG PARAMS -->
		
		<?php echo JHtml::_( 'tabs.panel' , '文章' , 'article' ); ?>
		
		<!-- CONTENT PARAMS -->
		<?php $this->showParams( 'article' ) ; ?>
		<!-- CONTENT PARAMS -->
		
		<?php echo JHtml::_( 'tabs.end' ); ?>
	</div>
	
	<div class="clr"></div>
</form>