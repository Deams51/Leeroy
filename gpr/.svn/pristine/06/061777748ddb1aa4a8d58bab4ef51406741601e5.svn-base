<?php
	//empeche l'accès direct au fichier
	defined('_JEXEC') or die ('Restricted access');
?>

<!-- @TODO Lien ou formulaire pour ajouter un projet -->

<h1>Mes projets</h1>
<?php
	if(count($this->msg) > 0)
	{
		echo '<table>';
		echo 	'<thead>
					<th>Nom Projet</th>
					<th>Description</th>
					<th>Chef</th>
					<th>Notif</th>
				</thead>';
		echo '<tbody>';
		foreach ($this->msg as $key => $projet) {
			echo '<tr>';
				echo '<td>';
					echo '<a href="'.JRoute::_('index.php?option=com_gpr&task=projets.afficher&view=projets&id_projet='.$projet['id']).'" title="Voir le projet">'.$projet['nom'].'</a>';
				echo '</td>';
				echo '<td>';
					echo $projet['description'];
				echo '</td>';
				echo '<td>';
					if($projet['statut'] == 1)
						echo '<img height="25" length="25" src="'.JURI::base().'administrator/components/com_gpr/assets/images/star.png" alt="*"/>';
				echo '</td>';
				echo '<td>';
					if($projet['token'] == 1)
						echo '<img height="25" length="25" src="'.JURI::base().'administrator/components/com_gpr/assets/images/notif_red.png" title="Votre postit a été modifé" alt="(!)" />';
					else
						echo '<img height="25" length="25" src="'.JURI::base().'administrator/components/com_gpr/assets/images/notif_black.png" title="Pas de nouveau postit"/>';
				echo '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
	}
	else
	{
		echo '<p>Aucun projet pour l\'instant</p>';
	}
?>