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


JHTML::script(JURI::base().'components/com_gpr/assets/js/progressbar.js');
JHTML::script(JURI::base().'components/com_gpr/assets/js/intro.js');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_gpr/assets/css/gpr.css');
$document->addStyleSheet('components/com_gpr/assets/css/introjs.css');
$document->addStyleSheet('components/com_gpr/assets/css/introjs-ie.css');



$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_gpr');
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_gpr&task=listevps.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'vpsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>

<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<?php
//Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar)) {
    $this->sidebar .= $this->extra_sidebar;
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_gpr&view=listevps'); ?>" method="post" name="adminForm" id="adminForm">
<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right" >
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
		</div>        
		<div class="clearfix"> </div>
		<p>Mémoire VPS : </p>
		<div id="progressBar" class="jlike"><div></div></div>		
		<script>
			<?php $vps_disk = get_disk_vps(); ?>
			progressBar(<?php echo round($vps_disk[1]/1048576,2);?>
						,<?php echo round($vps_disk[0]/1048576,2);?>, jQuery('#progressBar'));
		</script>
		<table class="table table-striped" id="vpsList" data-step="1" data-intro="Hello all! :)">
			<thead>
				<tr>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_GPR_ETAT', 'a.etat', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap center hidden-phone" data-step="2" data-intro="Boum l'etape 3:)">
						<?php echo JHtml::_('grid.sort', 'COM_GPR_ESPACE_DISQUE', 'a.espace_disque', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_GPR_HOSTNAME', 'a.hostname', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_GPR_NB_CPU', 'a.nb_cpu', $listDirn, $listOrder); ?>
					</th>			    
                	<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_GPR_MAX_CPU', 'a.max_cpu', $listDirn, $listOrder); ?>
					</th>
                	<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_GPR_RAISON', 'a.raison_demande', $listDirn, $listOrder); ?>
					</th>
                	<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_GPR_DATE', 'a.date', $listDirn, $listOrder); ?>
					</th>
                	<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_GPR_RAM_BURST', 'a.ram_burst', $listDirn, $listOrder); ?>
					</th>
                	<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_GPR_RAM_MIN', 'a.ram_min', $listDirn, $listOrder); ?>
					</th>
                	<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_GPR_ADRESSE_IP', 'a.adresse_ip', $listDirn, $listOrder); ?>
					</th>
                    
				</tr>
			</thead>
			<tfoot>
                <?php 
                if(isset($this->items[0])){
                    $colspan = count(get_object_vars($this->items[0]));
                }
                else{
                    $colspan = 10;
                }
            ?>
			<tr>
				<td colspan="<?php echo $colspan ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering   = ($listOrder == 'a.ordering');
                $canCreate	= $user->authorise('core.create',		'com_gpr');
                $canEdit	= $user->authorise('core.edit',			'com_gpr');
                $canCheckin	= $user->authorise('core.manage',		'com_gpr');
                $canChange	= $user->authorise('core.edit.state',	'com_gpr');
				?>
				
				<tr class="row<?php echo $i % 2; ?>" data-step="3" data-intro="blabla.">
                    
                <?php if (isset($this->items[0]->ordering)): ?>
					<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
					</td>
                <?php endif; ?>
					<td class="left">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
                <?php if (isset($this->items[0]->state)): ?>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'listevps.', $canChange, 'cb'); ?>
					</td>
                <?php endif; ?>
				<?php if (isset($this->items[0]->etat)): ?>
					<td class="center">
						<?php echo $item->etat; ?>
					</td>
                <?php endif; ?>
				<?php if (isset($this->items[0]->espace_disque)): ?>
					<td class="center">	
						<div id="progressBar<?php echo $item->id; ?>" class="default" style="min-width: 100px;"><div></div></div>		
						<script>
							progressBar(							
								<?php echo $item->quota_used; ?>							
							,<?php echo (int) $item->espace_disque/1000; ?>, jQuery('#progressBar<?php echo $item->id; ?>'));
						</script>
					</td>
                <?php endif; ?>
					<td class="center">
						<?php echo (string) $item->hostname; ?>
					</td>
                <?php if (isset($this->items[0]->nb_cpu)): ?>
					<td class="center">
						<?php echo (int) $item->nb_cpu; ?>
					</td>
                <?php endif; ?>
				<?php if (isset($this->items[0]->max_cpu)): ?>
					<td class="center">
						<?php echo (int) $item->max_cpu; ?>
					</td>
                <?php endif; ?>
				<?php if (isset($this->items[0]->raison_demande)): ?>
					<td class="center">
						<?php echo $item->raison_demande; ?>
					</td>
                <?php endif; ?>
				<?php if (isset($this->items[0]->reponse)): ?>
					<td class="center">
						<?php echo $item->reponse; ?>
					</td>
                <?php endif; ?>
				<?php if (isset($this->items[0]->date)): ?>
					<td class="center">
						<?php echo $item->date; ?>
					</td>
                <?php endif; ?>
				<?php if (isset($this->items[0]->ram_burst)): ?>
					<td class="center">
						<?php echo (int) $item->ram_burst; ?>
					</td>
                <?php endif; ?>
				<?php if (isset($this->items[0]->ram_min)): ?>
					<td class="center">
						<?php echo (int) $item->ram_min; ?>
					</td>
                <?php endif; ?>
					<td class="center">					
						<?php echo (string)$item->adresse_ip; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		

<?php 
function du( $dir ) 
{ 
    $res = `du -sk $dir`;             // Unix command
    preg_match( '/\d+/', $res, $KB ); // Parse result
    $ret = decodeSize($KB[0]);  // From kilobytes to megabytes
    return $ret;
} 

/* Renvoit les infos du disque du vps sous la forme d'un tableau */
/* return total en K, USE en k, available en k, use en %*/ 
function get_disk_vps()
{
	// Use awk to pull out the columns you actually want
	$output = shell_exec('df -T /dev/md4 | awk \'{print $3 " " $4 " " $5 " " $6}\'');
	// Split the result into an array by lines (removing the final linefeed)
	$drives = split("[\r|\n]", trim($output));
	// Chuck away the unused first line
	array_shift($drives);
	$drive = $drives[0];
	$values = explode(" ", $drive);
	return $values;
}

?>
<a class="btn btn-large btn-success" href="javascript:void(0);" onclick="javascript:introJs().start();">Aide</a>
     
		
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>        

		
