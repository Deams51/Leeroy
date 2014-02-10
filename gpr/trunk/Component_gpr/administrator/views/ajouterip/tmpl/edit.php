<?php
/**
 * @version     1.0.0
 * @package     com_gpr
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Choulet Quentin, Fourgeaud Mickaël, Hauguel Antoine, Malory Tristan <> - 
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_gpr/assets/css/gpr.css');
?>
<script type="text/javascript">
    
    
	Joomla.submitbutton = function(task)
	{
        if(task == 'ajouterip.cancel'){
            Joomla.submitform(task, document.getElementById('ajouterip-form'));
        }
        
		if (document.formvalidator.isValid(document.id('ajouterip-form'))) {
			Joomla.submitform(task, document.getElementById('ajouterip-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_gpr&layout=edit&id='.(int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="ajouterip-form" class="form-validate">
	<div class="row-fluid">
		<div class="span10 form-horizontal">
            <fieldset class="adminform">

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('ip'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('ip'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('dispo'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('dispo'); ?></div>
			</div>

				
            </fieldset>
    	</div>
        
        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
        
    </div>
</form>