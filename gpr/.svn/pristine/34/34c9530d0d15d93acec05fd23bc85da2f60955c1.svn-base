<?php
	//empeche l'accès direct au fichier
	defined('_JEXEC') or die ('Restricted access');
	JHTML::script(JURI::base().'administrator/components/com_gpr/assets/js/intro.js');
	$document = JFactory::getDocument();
	$document->addStyleSheet('administrator/components/com_gpr/assets/css/introjs.css');
	$document->addStyleSheet('administrator/components/com_gpr/assets/css/introjs-ie.css');

?>
<a class="btn btn-large btn-success" href="javascript:void(0);" onclick="javascript:introJs().start();">Aide</a>
<div data-step="1" data-intro="Un formulaire que vous devez remplir pour souscrire à une demande">
<h1>Gestion des services</h1>
<?php
	//on récupère l'utilisateur connecté
	$user = JFactory::getUser();
	//statut de l'utilisateur (true si propriétaire, false si membre
	$proprietaire = $this->getModel()->estProprietaire($user->id, $this->msg['id_projet']);

	if($proprietaire)
	{
?>
<h2>Demander un service</h2>
<form method="post" action=<?php echo "\"".JRoute::_('')."\""; ?>>
	<input style="float:left" type="checkbox" name="svn" />
	<label for="svn" data-step="2" data-intro="Pour faire une demande de SVN, cochez ici.">SVN (Subversion)</label>
	<div style="clear:both;"></div>
	<fieldset id="form_svn">
		<div data-step="4" data-intro="Vous devez décrire ici, pourquoi vous avez besoin d'un SVN. Cette raison sera lue et validée par l'administrateur.">
		<label for="description_svn">Description du besoin : </label>
		<textarea name="description_svn"></textarea>
		</div>
		<div data-step="5" data-intro="Vous renseignez ici la taille de votre demande de SVN.">
		<label for="taille_svn">Espace disque (MB) : </label><input type="text" name="taille_svn" />
		</div>
		<div data-step="6" data-intro="Vous devez ensuite choisir les droits d'accès à ce SVN.">
		<label for="droits_svn">Droits</label>
		<select name="droits_svn" size="1">
			<option value="0">Privé (aucuns droits pour les non-membres du projet)</option>
			<option value="1">Protégé (lecture seulement pour les non membres du projet)</option>
		</select>
		</div>
	</fieldset>
	<input style="float:left" type="checkbox" name="vps" />
	<label for="svn" data-step="3" data-intro="Pour faire une demande de VPS, cochez ici.">VPS (Virtual Private Server)</label>
	<div style="clear:both;"></div>
	<fieldset id="form_vps">
		<div data-step="7" data-intro="Vous devez décrire ici, pourquoi vous avez besoin d'un VPS. Cette raison sera lue et validée par l'administrateur.">
		<label for="description_vps">Description du besoin : </label>
		<textarea name="description_vps"></textarea>
		</div>
		<div data-step="8" data-intro="Vous renseignez ici la taille de votre demande de VPS.">
		<label for="taille_vps">Espace disque (MB) : </label><input type="text" name="taille_vps" />
		</div>
		<div data-step="9" data-intro="Vous devez renseignez ici, la taille de RAM minimale.">
		<label for="ram_mini_vps">RAM mini (MB) : </label><input type="text" name="ram_mini_vps" />
		</div>
		<div data-step="10" data-intro="Vous devez renseignez ici, la taille de RAM maximale.">
		<label for="ram_burst_vps">RAM burst (MB) : </label><input type="text" name="ram_burst_vps" />
		</div>
		<div data-step="11" data-intro="Vous devez remplir ici, le nombre de CPUs demandés">
		<label for="cpu_vps">Nombre de cpu : </label><input type="text" name="cpu_vps" />
		</div>
	</fieldset>
	<div data-step="12" data-intro="Après avoir rempli sur les champs nécessaires à votre demande, cliquez sur ce bouton. Une demande sera envoyée à l'administrateur.">
		<input type="submit" name="submit" value = "Valider" />
	</div>
</form>
</div>
<script>
	jQuery(document).ready(function(){
		jQuery('#form_svn').hide();
		jQuery('#form_vps').hide();

		jQuery('input[type=checkbox]').click(function (){
			if(jQuery(this).is(':checked'))
			{
				jQuery('#form_'+jQuery(this).attr('name')).show();
			}
			else
			{
				jQuery('#form_'+jQuery(this).attr('name')).hide();
			}
		});
	});
</script>
<?php } ?>