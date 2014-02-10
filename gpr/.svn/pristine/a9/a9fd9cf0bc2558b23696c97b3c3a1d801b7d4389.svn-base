<?php
	//Empeche l'acces direct au fichier
	defined('_JEXEC') or die ('Restricted access');

	//on inclut le css et js
	JHTML::script(JURI::base().'media/jui/js/jquery.js');
	JHTML::script(JURI::base().'/media/jui/js/jquery-noconflict.js');
	JHTML::script(JURI::base().'administrator/components/com_gpr/assets/js/fenetres_modales.js');
	JHTML::stylesheet(JURI::base().'administrator/components/com_gpr/assets/css/fenetres_modales.css');


	if(count($this->msg['infos_projet']) > 0)
	{
		//on récupère l'utilisateur connecté
		$user = JFactory::getUser();
		//statut de l'utilisateur (true si propriétaire, false si membre
		$proprietaire = $this->getModel()->estProprietaire($user->id, $this->msg['infos_projet']['id']);

		echo '<h1 style="font-size:24px">Projet : '.$this->msg['infos_projet']['nom'].'</h1>';

		if($proprietaire)
		{
			// mettre ici tous les liens réservés au chef de projet
			echo '<a href="'.JRoute::_('index.php?option=com_gpr&task=membres.ajouter&view=membres&id_projet='.$this->msg['infos_projet']['id']).'" 
					title="Ajouter un membre au projet">Ajouter un membre</a>';
			echo '<a id="supprimer_projet" href="'.JRoute::_('index.php?option=com_gpr&task=projets.supprimer&view=projets&id_projet='.$this->msg['infos_projet']['id']).'">
						Supprimer le projet</a>';
			echo '<a href="'.JRoute::_('index.php?option=com_gpr&task=services.gestion&view=services&id_projet='.$this->msg['infos_projet']['id']).'">
						Demander un service</a>';
		}?>

		<h2>Mon équipe</h2>

		<table>
		<thead>
			<th>Nom</th>
			<th>Supprimer</th>
			<th>Transférer les droits</th>
		</thead>
	  	<tbody>
		
		<?php
		foreach ($this->msg['infos_membres'] as $key => $membre)
		{
			echo '<tr>';
			if($proprietaire)
				echo '<td><a href="#">'.$membre['nom'].'</a>';
			else
				echo '<td colspan="3"><a href="#">'.$membre['nom'].'</a>';

			// si le membre est propriétaire on affiche une étoile
			if($this->getModel()->estProprietaire($membre['id'], $this->msg['infos_projet']['id']))
			{
				echo '<img height="20" width="20" src="./administrator/components/com_gpr/assets/images/star.png" alt="*" />';
			}

			echo '</td>';

			//si l'utilisateur connecté est propriétaire on affiche les liens pour supprimer un membre et tranférer les droits
			if($proprietaire)
			{
				echo '<td>';
					//si l'utilisateur connecté est différent de celui traité
					// on affiche le lien de suppression
					if($membre['id'] != $user->id)
					{
						echo '<a class="supprimer_membre" id="supprimer_membre_'.$key.'" 
							href="'.JRoute::_('index.php?option=com_gpr&task=membres.supprimer&view=membres&id_membre='.$membre['id'].'&id_projet='.$this->msg['infos_projet']['id']).'"
							title="Supprimer le membre du projet">
							<img width="25" length="25" src="./administrator/components/com_gpr/assets/images/delete.png" alt="Supprimer"/></a>';
					}
					echo '</td>';

					echo '<td>';
					//si l'utilisateur connecté est différent de celui traité
					//on affiche le lien de transfert de droits
					if($membre['id'] != $user->id)
					{
						echo '<a class="trasfert" id="transfert_'.$key.'"
							href="'.JRoute::_('index.php?option=com_gpr&task=membres.transferer_droits&view=membres&id_membre='.$membre['id'].'&id_projet='.$this->msg['infos_projet']['id']).'"
							title="Transférer les droits du projet au membre">
							<img width="25" length="25" src="'.JURI::base().'/administrator/components/com_gpr/assets/images/transfert_droits.png" alt="Transfert des droits"/></a>';
					}
				echo '</td>';
			}
			echo '</tr>';
		}?>

		</tbody>
	</table>

	<h2>Description</h2>
	<?php echo '<p id="description">'.$this->msg['infos_projet']['description'].'</p>'; ?>

	<h2>Services</h2>
	<?php
		if(isset($this->msg['demandes']['svn']) > 0 || isset($this->msg['demandes']['vps']))
		{
			?>
	<table>
		<thead>
			<th>Service demandé</th>
			<th>Date</th>
			<th>Etat de la demande</th>
		</thead>
		<tbody>
	<?php
			//affichage des demandes svn
			if(isset($this->msg['demandes']['svn']))
			{
				foreach ($this->msg['demandes']['svn'] as $key => $svn) {
					echo '<tr>';
						echo '<td>Svn</td>';
						echo '<td>'.$svn['date'].'</td>';
						echo '<td>'.$svn['etat'].'</td>';
					echo '</tr>';
				}
			}

			//affichage des demandes vps
			if(isset($this->msg['demandes']['vps']))
			{
				foreach ($this->msg['demandes']['vps'] as $key => $vps) {
					echo '<tr>';
						echo '<td>Vps</td>';
						echo '<td>'.$vps['date'].'</td>';
						echo '<td>'.$vps['etat'].'</td>';
					echo '</tr>';
				}
			}
		}
		else
		{
			echo '<tr><td colspan="3">Aucune demande pour ce projet</td></tr>';
		}
	?>
		</tbody>
	</table>
	<?php
	}
	else
	{
		echo "<tr>Le projet demandé n'existe pas.</tr>";
	}
	?>
	<script>
		jQuery(document).ready(function (){

			// message avant la suppresion du projet
			var mess_sup_proj = 
				'Vous êtes sur le point de supprimer le projet.<br />'+
					'<ul>'+
						'<li>Tous les servcies associés au projet seront arrêtés, et donc inaccessibles.</li>'+
						'<li>Toutes les données liées au projet (svn par exemple) seront inaccessibles.</li>'+
					'</ul>'+
				'Êtes-vous sur de vouloir continuer ?';

			//message avant la suppression d'un membre
			var mess_sup_membre = 
				'Vous êtes sur le point de supprimer le membre du projet<br />'+
				'<ul><li>Il ne poura plus accéder au projet et aux services associés</li></ul>'+
				'Voulez-vous vraiment continuer ?';
			//message avant transfert des droits
			var mess_transfert =
				'Vous êtes sur le point de transférer les droits d\'administration à un autre membre<br />'+
				'<ul>'+
				'<li>Vous ne serez plus propriétaire du projet</li>'+ 
				'<li>Vous n\'aurez plus aucun droits d\'administration sur le projet<br />'+
				'(suppression et ajout de membre, demande de services, etc ...)</li></ul>'+
				'Voulez-vous vraiment continuer ?';

			//on crée la confirm box pour la suppression du projet
			jQuery('#supprimer_projet').confirmBox({
				title : 'Suppresion du projet',
				msg : mess_sup_proj,
				yes_text : 'Continuer',
				no_text : 'Annuler',
				yes : function(){
						window.location = jQuery('#supprimer_projet').attr('href');
					}
			});

			<?php 
			for ($key = 1; $key < count($this->msg['infos_membres']); $key++)
			{
			?>
				//on crée la confirm box pour la suppression d'un membre
				jQuery(<?php echo "'#supprimer_membre_".$key."'"; ?>).confirmBox({
					title : 'Suppresion d\'un membre',
					msg : mess_sup_membre,
					yes_text : 'Continuer' ,
					no_text : 'Annuler',
					yes : function(){
							window.location = jQuery(<?php echo "'#supprimer_membre_".$key."'"; ?>).attr('href');
						}
				});
			<?php } ?>

			<?php 
			for ($key = 1; $key < count($this->msg['infos_membres']); $key++)
			{
			?>
				//on crée la confirm box pour la suppression d'un membre
				jQuery(<?php echo "'#transfert_".$key."'"; ?>).confirmBox({
					title : 'Suppresion d\'un membre',
					msg : mess_transfert,
					yes_text : 'Continuer' ,
					no_text : 'Annuler',
					yes : function(){
							window.location = jQuery(<?php echo "'#transfert_".$key."'"; ?>).attr('href');
						}
				});
			<?php } ?>
		});
	</script>