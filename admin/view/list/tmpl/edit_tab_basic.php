<?php
/**
 * Part of Component Quickcontent files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

$tab       = $data->tab;
$fieldsets = $data->form->getFieldsets();
?>

<?php echo JHtmlBootstrap::addTab('listEditTab', $tab, \JText::_($data->view->option . '_EDIT_' . strtoupper($tab))) ?>

<div class="row-fluid">
	<div class="width-60 fltlft span7">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_QUICKCONTENT_LEGEND_FORM'); ?></legend>

			<div class="control-group">
				<?php echo $data->form->getLabel('id'); ?>
				<div class="controls">
					<?php echo $data->form->getInput('id'); ?>
				</div>
			</div>

			<div class="control-group">
				<?php echo $data->form->getLabel('title'); ?>
				<div class="controls">
					<?php echo $data->form->getInput('title'); ?>
				</div>
			</div>

			<div class="control-group">
				<?php echo $data->form->getLabel('checked_out'); ?>
				<div class="controls">
					<?php echo $data->form->getInput('checked_out'); ?>
				</div>
			</div>

			<div class="control-group">
				<?php echo $data->form->getLabel('checked_out_time'); ?>
				<div class="controls">
					<?php echo $data->form->getInput('checked_out_time'); ?>
				</div>
			</div>

		</fieldset>
		<fieldset class="adminform">
			<div><?php echo $data->form->getLabel('content'); ?></div>
			<div><?php echo $data->form->getInput('content'); ?></div>

		</fieldset>
	</div>
</div>

<?php echo JHtmlBootstrap::endTab(); ?>
