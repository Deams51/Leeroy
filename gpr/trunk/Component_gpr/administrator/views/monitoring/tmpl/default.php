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

JHTML::script(JURI::base().'components/com_gpr/assets/js/flot/jquery.flot.js');
JHTML::script(JURI::base().'components/com_gpr/assets/js/flot/jquery.flot.pie.js');
JHTML::script(JURI::base().'components/com_gpr/assets/js/flot/jquery.flot.resize.js');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_gpr/assets/css/gpr.css');
$document->addStyleSheet('components/com_gpr/assets/css/introjs.css');
$document->addStyleSheet('components/com_gpr/assets/css/introjs-ie.css');



$user	= JFactory::getUser();
$userId	= $user->get('id');





?>

<form action="<?php echo JRoute::_('index.php?option=com_gpr&view=monitoring'); ?>" method="post" name="adminForm" id="adminForm">
<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>


       



		
<h1 class="monitoring-cadre" style="text-align: center;">Monitoring Serveur</h1>
	<div class="demo-placeholder">
		<h2 style="text-align: center;">Résumé espace disque</h2>
		<div id="placeholder" class="demo-placeholder" style="padding: 0px; position: relative;"></div>
	</div>
<a class="btn btn-large btn-success" href="javascript:void(0);" onclick="javascript:introJs().start();">Show me how</a>		
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>        

<div class="clearfix"> </div>
<p>Mémoire VPS : </p>
<div id="progressBar" class="jlike"><div></div></div>		
<script>
	<?php $vps_disk = get_disk_vps(); ?>
	progressBar(<?php echo round($vps_disk[1]/1048576,2);?>,<?php echo round($vps_disk[0]/1048576,2);?>, jQuery('#progressBar'));
</script>

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

<script type="text/javascript">
var addEvent = function(elem, type, eventHandle) {
    if (elem == null || elem == undefined) return;
    if ( elem.addEventListener ) {
        elem.addEventListener( type, eventHandle, false );
    } else if ( elem.attachEvent ) {
        elem.attachEvent( "on" + type, eventHandle );
    } else {
        elem["on"+type]=eventHandle;
    }
};

	jQuery(function() {

		// Example Data

		var data = [
			{ label: "VPS",  data: 30},
			{ label: "SVN",  data: 15},
			{ label: "Autres",  data: 15},
			{ label: "Libre",  data: 40},
		//	{ label: "Series5",  data: 80},
		//	{ label: "Series6",  data: 110}
		];

		//var data = [
		//	{ label: "Series1",  data: [[1,10]]},
		//	{ label: "Series2",  data: [[1,30]]},
		//	{ label: "Series3",  data: [[1,90]]},
		//	{ label: "Series4",  data: [[1,70]]},
		//	{ label: "Series5",  data: [[1,80]]},
		//	{ label: "Series6",  data: [[1,0]]}
		//];

		//var data = [
		//	{ label: "Series A",  data: 0.2063},
		//	{ label: "Series B",  data: 38888}
		//];

		// Randomly Generated Data
/*
		var data = [],
			series = Math.floor(Math.random() * 6) + 3;

		for (var i = 0; i < series; i++) {
			data[i] = {
				label: "Series" + (i + 1),
				data: Math.floor(Math.random() * 100) + 1
			}
		}
*/
		var placeholder = jQuery("#placeholder");

			placeholder.unbind();

			jQuery("#title").text("Label Styles #1");
			jQuery("#description").text("Semi-transparent, black-colored label background.");

			jQuery.plot(placeholder, data, {
				series: {
					pie: { 
						show: true,
						radius: 1,
						label: {
							show: true,
							radius: 3/4,
							formatter: labelFormatter,
							background: { 
								opacity: 0.5,
								color: "#000"
							}
						}
					}
				},
				legend: {
					show: false
				}
			});

			setCode([
				"jQuery.plot('#placeholder', data, {",
				"    series: {",
				"        pie: { ",
				"            show: true,",
				"            radius: 1,",
				"            label: {",
				"                show: true,",
				"                radius: 3/4,",
				"                formatter: labelFormatter,",
				"                background: { ",
				"                    opacity: 0.5,",
				"                    color: '#000'",
				"                }",
				"            }",
				"        }",
				"    },",
				"    legend: {",
				"        show: false",
				"    }",
				"});"
			]);

		// Show the initial default chart

		jQuery("#example-5").click();

		// Add the Flot version string to the footer

		jQuery("#footer").prepend("Flot " + jQuery.plot.version + " &ndash; ");

		jQuery(".demo-placeholder").resizable({
					maxWidth: 900,
					maxHeight: 500,
					minWidth: 450,
					minHeight: 250,
				});
	});

	// A custom label formatter used by several of the plots

	function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
	}

	//

	function setCode(lines) {
		jQuery("#code").text(lines.join("\n"));
	}

</script>

		
