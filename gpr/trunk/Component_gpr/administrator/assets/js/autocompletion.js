function lookup(inputString){
	if(inputString.length <= 0){
		jQuery("#suggestions").hide();
	}
	else{
		jQuery.post("/?option=com_gpr&task=membres.find&view=membres", 
			{ nom : ""+inputString+"" },
			function(data){
				jQuery("#suggestions").html(data);

				//on lie l'évent click de chaque item
				jQuery(".suggestion_item").each(function (){
					jQuery(this).on('click', fill);
				});

				jQuery("#suggestions").show();
			}, 
			'html');
	}
}

function fill (event){
	jQuery("#nom_membre").val(jQuery(event.target).text());
	jQuery("#suggestions").hide();
}

jQuery(document).ready(function (){
	jQuery("#suggestions").hide();

	jQuery('#nom_membre').on('keyup', function(event){
		lookup(jQuery(event.target).val());
	});

});