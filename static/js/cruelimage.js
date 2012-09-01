$.blockUI.defaults.css = {
	border: 'none',
	padding: '10px',
	backgroundColor: '#000',
	'-webkit-border-radius': '10px',
	'-moz-border-radius': '10px',
	opacity: 0.8,
	color: '#fff'
};
$.blockUI.defaults.overlayCSS = {
	opacity:0
};

function loadImageCropper(selector){
	$(selector).change(function(){
		if (!FileReader){
			alert('Please use Firefox or Chrome for profile photos.');
			return false;
		}
		var file = this.files[0],
			image = new Image(),
			cropimage = new Image(),
			reader = new FileReader();
		function updatePreview(c){
			if (parseInt(c.w) > 0){
				var canvas = $('.cropthumb')[0],
					context = canvas.getContext('2d'),
					r = image.width / cropimage.width;
				context.drawImage(image, c.x*r, c.y*r, c.w*r, c.h*r, 0, 0, 150, 150);
			}
		}
		reader.onload = function (e) { image.src = e.target.result; };
		image.onload = function(){
			var ratio = image.width/image.height,
				$dialog = $('<div><div class="cropimage"></div><canvas class="cropthumb" width="150" height="150"></canvas></div>');
			cropimage.src = image.src;
			if (image.width > 650){
				cropimage.width = 650;
				cropimage.height = 650/ratio;
			}
			$(cropimage).appendTo($dialog.find(".cropimage"));
			$dialog.find('.cropimage img').Jcrop({
				onChange: updatePreview,
				onSelect: updatePreview,
				aspectRatio: 1
			},function(){
				$dialog.dialog({
					modal: true,
					title: 'Crop the profile photo',
					resizable: false,
					draggable: false,
					width: cropimage.width+200,
					close: function(){
						$dialog.remove();
						$(selector).replaceWith($(selector).clone(true));
					},
					buttons: {
						Cancel: function(){$(this).dialog("close");},
						Done: function() {
							var photodata = $('.cropthumb')[0].toDataURL('image/jpeg');
							$('#photodata').val(photodata);
							loadPhotoData(photodata);
							$(this).dialog("close");
						}
					}
				});
			});
		};
		reader.readAsDataURL(file);
	});
}

function CloseAjaxDiv(obj){
	$(obj).slideUp('fast', function(){
		$(this).remove();
	});
	//alert($(obj).html());
}

function AddAjaxDiv(container, classname, msg){
	return $(container).prepend('<div class="ajax_msg clearfix '+classname+'">'+msg+'<div onclick="CloseAjaxDiv(this.parentNode);" class="delicon"></div></div>')
		.children().first();
}

function handleXhrError(xhr) {
	document.open();
	document.write(xhr.responseText);
	document.close();
}

$(function(){
	loadImageCropper("#filecrop");
	// loadPhotoData($('#photodata').val());

	if ($('#scanfile').length > 0)
		loadScanUploader();
	// $.ajaxSetup({error: handleXhrError});

	imagescroll_onload();
});