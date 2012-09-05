function createDatabase(){
	$.post('', $('form').serialize()+'&db_create=yes', function(data){
		console.log(data);
		if (data.success){
			$('#db_fields').hide('slow');
			$('#config_fields').show('slow');
		} else {
			$('#db_error').html(data.error);
		}
	});
	return false;
}

function install(){
	$.post('', $('form').serialize(), function(data){
		if (data.success){
			$('#config_file').html(data.config_file);
			$('#config_fields').hide('slow');
			$('#install_message').show('slow').find('textarea').html(data.config);
		} else {
			$('#install_error').html(
				"Failed to write to <em>"+data.config_file+"</em><br />\
				Possible perrmissions issue. If you wish to create the file yourself:<br />\
				<textarea>"+data.config+"</textarea>"
			);
		}
	});
	return false;
}

$(function(){
	$('#btnCreateDatabase').click(createDatabase);
	$('#btnInstall').click(install);
});