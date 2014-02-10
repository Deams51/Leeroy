<?php
	//empeche l'accès direct au fichier
	defined('_JEXEC') or die ('Restricted access');
	JHTML::script(JURI::base().'administrator/components/com_gpr/assets/js/intro.js');
	$document = JFactory::getDocument();
	$document->addStyleSheet('administrator/components/com_gpr/assets/css/gpr.css');
	$document->addStyleSheet('administrator/components/com_gpr/assets/css/introjs.css');
	$document->addStyleSheet('administrator/components/com_gpr/assets/css/introjs-ie.css');

?>

<!-- @TODO Lien ou formulaire pour ajouter un projet -->
<a class="btn btn-large btn-success" href="javascript:void(0);" onclick="javascript:introJs().start();">Aide</a>
<div id="intro11" data-step="1" data-intro="Dans cette page, vous pouvez voir à quel(s) projet(s) vous êtes rattaché(s).">
<h1>Mes projets</h1>
<div id="intro12" data-step="2" data-intro="Vous pouvez créer un projet en cliquant sur ce lien.">
<a class="btn btn-large btn-success" href="<?php echo JRoute::_("index.php?option=com_gpr&task=projets.creer&view=projets");?>">Créer projet</a>
</div>
<?php
	
	echo '<table>';
	echo 	'<thead>
				<th id="intro13" data-step="3" data-intro="Le nom du projet sera dans cette colonne.">Nom Projet</th>
				<th id="intro14" data-step="4" data-intro="La description du projet sera présent dans cette colonne.">Description</th>
				<th id="intro15" data-step="5" data-intro="Vous saurez ici si vous êtes le chef du projet (étoile présente si vous l\'êtes)">Chef</th>
				<th id="intro16" data-step="6" data-intro="Vous saurez ici si votre post-it a été modifié par un autre membre du groupe (l\'icone sera en rouge).">Notification</th>
			</thead>';
	echo '<tbody>';
	if(count($this->msg) > 0)
	{
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
	}
	else
	{
		echo '<tr><td colspan = 4>Aucun projet pour l\'instant</td></tr>';
	}
	echo '</tbody>';
	echo '</table>';
?>
</div>