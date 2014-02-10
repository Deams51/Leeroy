<?php
	//empeche l'accès direct au fichier
	defined('_JEXEC') or die ('Restricted access');
	JHTML::script(JURI::base().'administrator/components/com_gpr/assets/js/intro.js');
	$document = JFactory::getDocument();
	$document->addStyleSheet('administrator/components/com_gpr/assets/css/introjs.css');
	$document->addStyleSheet('administrator/components/com_gpr/assets/css/introjs-ie.css');
?>

<a class="btn btn-large btn-success" href="javascript:void(0);" onclick="javascript:introJs().start();">Aide</a>
<div id="intro21" data-step="1" data-intro="Vous pouvez ici créer votre projet.">
<h1>Créer projet</h1>
<form method="post" action="<?php echo JRoute::_(''); ?>">
	<div id="intro22" data-step="2" data-intro="Le nom que le projet aura."><label for="nom">Nom du projet : </label><input type="text" name="nom" /></div><br />
	<div id="intro23" data-step="3" data-intro="Décrivez ici le projet (raison, but, etc...)"><label for="description">Description : </label><br />
	<textarea rows="7" name="description"></textarea></div><br />
	<div id="intro24" data-step="4" data-intro="Après avoir remplis les champs, cliquez ici pour créer votre projet."><input type="submit" name="submit" value="Valider" /></div><br />
</form>
</div>