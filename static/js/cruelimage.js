$.blockUI.defaults.css = {
	top:'10%',
	border: 'none',
	padding: '10px',
	backgroundColor: '#000',
	'-webkit-border-radius': '10px',
	'-moz-border-radius': '10px',
	opacity: 0.8,
	color: '#fff'
};
$.blockUI.defaults.overlayCSS = {
	backgroundColor: '#000',
	opacity:0
};

function loadImageCropper(index){
	var file = filelist[index],
		image = imagelist[index],
		cropimage = new Image(),
		origwidth,
		coords,
		jcrop_api, // http://deepliquid.com/content/Jcrop_API.html
		$dialog;
	function updatePreview(c){
		if (parseInt(c.w) > 0){
			coords = c;
			var canvas = $dialog.find('.cropthumb')[0],
				context = canvas.getContext('2d'),
				r = origwidth / cropimage.width,
				r2 = c.w / c.h;
			context.clearRect (0, 0, 150, 150);
			context.drawImage(image, c.x*r, c.y*r, c.w*r, c.h*r, 0, 0, Math.min(150,150*r2), Math.min(150,150/r2));
		}
	}
	cropimage.onload = function(){
		var ratio = cropimage.width/cropimage.height;
		origwidth = cropimage.width;
		$dialog = $('.cropdialog').clone();

		if (cropimage.width > 650){
			cropimage.width = 650;
			cropimage.height = 650/ratio;
		}
		$(cropimage).appendTo($dialog.find(".cropimage"));
		$dialog.find('.cropimage img').Jcrop({
			onChange: updatePreview,
			onSelect: updatePreview
		},function(){
			jcrop_api = this;
			$dialog.dialog({
				modal: true,
				title: 'Crop the profile photo',
				resizable: false,
				draggable: false,
				width: cropimage.width+200,
				close: function(){
					$dialog.remove();
				},
				buttons: {
					Cancel: function(){$(this).dialog("close");},
					Done: function() {
						var r = origwidth / cropimage.width,
							c = document.createElement('canvas'),
							ctx = c.getContext('2d');
						c.width = coords.w * r;
						c.height = coords.h * r;
						//var photodata = $('.cropthumb')[0].toDataURL('image/jpeg');
						ctx.drawImage(image, coords.x*r, coords.y*r, coords.w*r, coords.h*r, 0, 0, c.width, c.height);
						c.toBlob(
							function (blob) {
								//blob.lastModifiedDate = filelist[index].lastModifiedDate;
								blob.name = filelist[index].name;
								addFileToList(blob, index);
							},
							filelist[index].type
						);
						$(this).dialog("close");
					}
				}
			});
			$dialog.find('select').change(function(){
				jcrop_api.setOptions({aspectRatio:$(this).val()});
			});
		});
	};
	cropimage.src = image.src;
}

function CloseAjaxDiv(obj){
	$(obj).slideUp('fast', function(){
		$(this).remove();
	});
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
	if ($('#scanfile').length > 0)
		loadScanUploader();
	// $.ajaxSetup({error: handleXhrError});

	imagescroll_onload();

	if (!FileReader){
		alert("Your browser doesn't support this site. Please use Chrome or Firefox.");
	}
});