<?php
	//empeche l'accès direct au fichier
	defined('_JEXEC') or die ('Restricted access');

?>

<!-- Projets - Ajout membres -->
<h1> Test ajout membres</h1>
<form method="post" action=<?php echo "\"".JRoute::_('index.php?option=com_gpr&task=membres.ajouter&view=membres&id_projet='.$this->msg)."\""; ?>>
	ID membre a ajouter<input type = "text" name = "id_membre" />
	<input type = "submit" name="submit" VALUE="Valider" />
</form>