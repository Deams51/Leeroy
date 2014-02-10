/**
	* Usage :
	---------
	jQuery(selector).confirmBox(options);
	If several elements match the provided selector, confirmBox will be called
	only on the first element

	* Options :
	-----------
	title : confirm box title
	msg : confirm box message
	yes_text : text for 'yes' button
	no_text : text for 'no' button
	yes : handler for the 'yes' button
	no : handler for the 'no' button
			use hideConfirmBox(jQuery('selector').attr('id')); in order to hide the confirm box after you did
			your business in the no function
*/

/**
  * Link the click event of a jquery object with a confirm box
  * @param : options - associative array (object) containing the options
  * for the confirm box (see above for a list of available options)
  * @return : the jQuery objet
  */
var confirm = function confirmBox(options)
{
	//default options
	var title = 'Confirm box'; //confirm box title
	var msg = 'Are you sure you want to perform this operation ?'; //confirm box message
	var yes_text = 'Yes'; //text for 'yes' button
	var no_text = 'No'; //text for 'no' button
	var yes = defaultYes; //handler for the 'yes' button
	var no = defaultNo; //handler for the 'no' button


	name = this.attr('id');

	//if there is no id we use the name
	if(name == 'undefined'){
		name = this.attr('name');
		//if there is no name, we use a random string
		if(name == 'undefined'){
			name = '';
			for( var i=0; i < 5; i++ )
			{
				var possible = "abcdefghijklmnopqrstuvwxz";
        		name += possible.charAt(Math.floor(Math.random() * possible.length));
        		this.attr('id', name);
        	}
		}
	}

	//Check for options
	if(typeof options != 'undefined')
	{
		if(typeof options['title'] == 'string'){
			title = options['title'];}

		if(typeof options['msg'] == 'string'){
			msg = options['msg'];}

		if(typeof options['yes_text'] == 'string'){
			yes_text = options['yes_text'];}

		if(typeof options['no_text'] == 'string'){
			no_text = options['no_text'];}

		if(typeof options['yes'] == 'function'){
			yes = options['yes'];}

		if(typeof options['no'] == 'function'){
			no = options['no'];}
	}
	//end check for options

	//create the html confirm box
	createConfirmBox(name, title, msg, yes_text, no_text);

	//get the confirm box object
	confirm_box = jQuery('#'+name+'_confirm');

	//get the confirm box overlay object
	confirm_box_overlay = jQuery('#'+name+'_confirm_overlay');

	//Attach the no method to no button's click event
	confirm_box.find('button.confirm_no').on('click', no);
	//Attach the yes method to yes button's click event
	confirm_box.find('button.confirm_yes').on('click', yes);
	
	// close the confirm box when user click on background
	confirm_box_overlay.on('click', function (){
		jQuery('#'+jQuery(this).attr('id').replace('_overlay', '')).hide();
		jQuery('#'+jQuery(this).attr('id')).hide();
	})

	this.on('click', function (event){
		event.preventDefault();

		jQuery('#'+jQuery(this).attr('id')+'_confirm').show();
		jQuery('#'+jQuery(this).attr('id')+'_confirm_overlay').show();
	});

	return this;
}

/**
  * Create the html structure for the confirm box
  * and add it into the document
  * @param : title - title for the confirm box
  * @param : name - base name for classes and ids of html elements
  * @param : yes_text - text for the yes button
  * @param : no_text - text for the no button
  * @param : yes - handler for the yes button's click event
  * @param : no - handler for the no button's click event
  * @return : object - the confirm box and the confirm box overlay
  */
function createConfirmBox(name, title, msg, yes_text, no_text)
{
	var confirm_box = '';
	var confirm_box_overlay;
	var box_title = '';
	var box_msg = '';
	var buttons = '';
	var yes_button = '';
	var no_button = '';

	box_title = '<h2 class="confirm_modale_title">'+
				'<img alt="/!\\" src="/administrator/components/com_gpr/assets/images/warning.png" />'+
				title+
				'</h2>';
	box_msg = '<div class="confirm_modale_msg">'+
				msg+
				'</div>';
	no_button = '<button type="button" class="confirm_no">'+
				no_text+
				'</button>';
	yes_button = '<button type="button" class="confirm_yes">'+
				yes_text+
				'</button>';			
	buttons = '<div class="confirm_modale_buttons">'+
				no_button+
				yes_button+
				'<div class="clearBoth"></div>'+
				'</div>';
	confirm_box = '<div class="confirm_modale" id="'+name+'_confirm">'+
					box_title+
					box_msg+
					buttons+
					'</div>'+
					'<div class="modale_overlay" id="'+name+'_confirm_overlay"></div>';

	//add confirm box to document
	jQuery('body').append(confirm_box);
}

/**
  * Default handler for the 'yes' button
  */
function defaultYes(){
	alert('Confirm');
}

/**
  * Default handler for the 'no' button
  */
function defaultNo(){
	hideConfirmBox(jQuery(this).parent().parent().attr('id').replace('_confirm', ''));
}

/**
  * Hide a confirm box
  * @param : name - name of the confirm box to hide
  * @return void
  */
function hideConfirmBox (name)
{
	jQuery('#'+name+'_confirm').hide();
	jQuery('#'+name+'_confirm_overlay').hide();
}


jQuery(document).ready(function (){
	
	jQuery.fn.confirmBox = confirm;

});