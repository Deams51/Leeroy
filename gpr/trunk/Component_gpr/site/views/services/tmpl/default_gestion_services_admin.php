<?php
	//empeche l'accès direct au fichier
	defined('_JEXEC') or die ('Restricted access');

?>
<h1>Gestion des services</h1>
<h2>Demandes svn en attente</h2>
<table>
	<thead>
		<th>Projet</th>
		<th>Date</th>
		<th>Description</th>
		<th>Espace demandé</th>
		<th>Droits</th>
		<th></th>
		<th></th>
	</thead>
	<tbody>
		<?php

			if(isset($this->msg['svn']) && count($this->msg['svn']) > 0)
			{
				foreach ($this->msg['svn'] as $key => $svn) {
					echo '<tr>';
						echo '<td>'.$svn['id_projet'].'</td>';
						echo '<td>'.$svn['date'].'</td>';
						echo '<td>'.$svn['raison_demande'].'</td>';
						echo '<td>'.$svn['taille'].'</td>';
						echo '<td>'.$svn['droits'].'</td>';
						echo '<td><a href="'.JURI::root().'index.php?option=com_gpr&task=accepter_demande&service_demande=svn&id_demande='.$svn['id'].'">
						Accepter</a></td>';
						echo '<td><a href="'.JURI::root().'index.php?option=com_gpr&task=refuser_demande&service_demande=svn&id_demande='.$svn['id'].'">
						Refuser</a></td>';
					echo '</tr>';
				}
			}
			else
			{
				echo '<tr><td colspan="6">Aucune demande</td></tr>';
			}
		?>
	</tbody>
</table>

<h2>Demandes de vps en attente</h2>
<table>
	<thead>
		<th>Projet</th>
		<th>Date</th>
		<th>Description</th>
		<th>Espace demandé</th>
		<th>RAM min</th>
		<th>Ram burst</th>
		<th>Nb CPU</th>
		<th></th>
		<th></th>
	</thead>
	<tbody>
		<?php
			if(isset($this->msg['vps']) && count($this->msg['vps']) > 0)
			{
				foreach ($this->msg['vps'] as $key => $vps) {
					echo '<tr>';
						echo '<td>'.$vps['id_projet'].'</td>';
						echo '<td>'.$vps['date'].'</td>';
						echo '<td>'.$vps['raison_demande'].'</td>';
						echo '<td>'.$vps['espace_disque'].'</td>';
						echo '<td>'.$vps['ram_min'].'</td>';
						echo '<td>'.$vps['ram_burst'].'</td>';
						echo '<td>'.$vps['nb_cpu'].'</td>';
						echo '<td><a href="'.JURI::root().'index.php?option=com_gpr&task=accepter_demande&service_demande=vps&id_demande='.$vps['id'].'">
						Accepter</a></td>';
						echo '<td><a href="'.JURI::root().'index.php?option=com_gpr&task=refuser_demande&service_demande=vps&id_demande='.$vps['id'].'">
						Refuser</a></td>';
					echo '</tr>';
				}
			}
			else
			{
				echo '<tr><td colspan="7">Aucune demande</td></tr>';
			}
		?>
	</tbody>
</table>