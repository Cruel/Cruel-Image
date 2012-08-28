var filelist = [], imagelist = [];

function addFileToList(file){
	var image = new Image(),
		reader = new FileReader();
	reader.onload = function (e) {
		image.src = e.target.result;
	};
	image.onload = function(){
		addImageToList(this);
		filelist.push(file);
	};
	reader.readAsDataURL(file);
}

function addImageToList(image){
	imagelist.push(image);
	$('#imagescroller ul')
		.append($('<li><div class="imgdelete"></div></li>')
		.append(image));
	refreshScrollerWidth();
}

function refreshScrollerWidth(){
	var x = 0;
	$('#imagescroller li').each(function(){
		x += $(this).outerWidth(true);
	});
	$('#imagescroller ul').width(x);
}

var timer3622 = 0;
function progresstest(){
    timer3622 += 0.01;
    canvasprogress(timer3622);
    if (timer3622 <= 1.0)
        setTimeout(progresstest, 30);
    else {
        timer3622 = 0;
        alert('Complete!');
        $imagescroller.mousemove(onImageScrollerMouseMove);
        drawImageList();
    }
}

function canvasprogress(p){
	var w = $('#imagescroller').width(),
		max = Math.max($('#imagescroller ul').width(), $('#imagescroller').width());
	drawImageList((w-max)*p, p*100);
}

var lastScrollLeft = 0,
	scrollAnimating = false;
function onImageScrollerMouseMove(e){
	var w		= $(this).width(),
		edge	= w * 0.15,
		new_w	= w - (edge*2),
		curX	= e.pageX - $(this).offset().left - edge,
		$list	= $(this).find('ul');
	curX = Math.max(curX, 0);
	curX = Math.min(curX, new_w);
	var percent = curX / new_w,
		newLeft = -percent*($list.width() - w);
	//$("#testlol").html(percent + " - " + $(this).offset().left + " - " + curX);
	if (!scrollAnimating){
		var leftDiff = Math.abs(newLeft-lastScrollLeft);
		if (leftDiff > 400) {
			scrollAnimating = true;
			$list.animate({left:newLeft}, 200, function(){
				scrollAnimating = false;
			});
		} else if (leftDiff > 15){
			lastScrollLeft = newLeft;
			drawImageList(newLeft);
		}
	}
}

function drawImageList(offset, percent){
	if (typeof(offset) == 'undefined') offset = 0;
	if (typeof(percent) == 'undefined') percent = 100;
	var scrollerwidth = $('#imagescroller').width();
	$('#imageprogressbar').css('left', percent+'%');
	$('#imagescroller ul').css('left', offset);
}

var filetypes = /^image\/(gif|jpeg|png)$/;
function fileChange() {
	$.each(this.files, function (index, file) {
		var duplicate = false;
		for (i in filelist)
			if (filelist[i].name == file.name)
				duplicate = true;
		if (!duplicate){
			if (filetypes.test(file.type)){
				addFileToList(file);
			} else {
				alert('The file type ('+file.type+') is not supported.');
			}
		}
	});
	// reloadFileList();
	// drawImageList();
}

function resetUploader(){
	filelist = [];
	imagelist = [];
	$('#imagescroller li').remove();
}

function loadUploader(){
	$(document).bind('drop dragover', function (e) {
		e.preventDefault(); // Prevent browser's default action
	});

	$(document).bind('dragover', function (e) {
		var dropZone = $imagescroller,
			timeout = window.dropZoneTimeout;
		if (!timeout) {
			dropZone.addClass('in');
		} else {
			clearTimeout(timeout);
		}
		if (e.target === dropZone[0]) {
			dropZone.addClass('hover');
		} else {
			dropZone.removeClass('hover');
		}
		window.dropZoneTimeout = setTimeout(function () {
			window.dropZoneTimeout = null;
			dropZone.removeClass('in hover');
		}, 100);
	});

	$('#uploadform').fileupload({
		// url: '/upload_file',
		dataType: 'json',
		dropZone: $imagescroller,
		fileInput: $("#files"),
		fileTypes: filetypes,
		//sequentialUploads: true,
		singleFileUploads: false,
		maxFileSize: 4000,
		add: function (e, data) {
			$.each(data.files, function (index, file) {
				var duplicate = false;
				for (i in filelist)
					if (filelist[i].name == file.name)
						duplicate = true;
				if (!duplicate){
					// if (filetypes.test(file.type)){
						addFileToList(file);
					// } else {
					// 	alert('The file type ('+file.type+') is not supported.');
					// }
				}
			});
			// $('#upload-controller').blockEx();
		},
		'progress': function(e, data){
			var progress = data.loaded / data.total; //parseInt(data.loaded / data.total, 10);
			var w = $('#imagescroller').width(),
			max = Math.max($('#imagescroller ul').width(), $('#imagescroller').width());
			drawImageList((w-max)*progress, progress*100);
		},
		change: function (e, data) {
			//loadImage(data.files[0]);
		},
		drop: function (e, data) {
			//loadImage(data.files[0]);
			$.each(data.files, function (index, file) {
				//alert('Dropped file: ' + file.name);
				//loadImage(file);
			});
		},
		done: function (e, data) {
			resetUploader();
			if (data.result.success) {
				// alert(data.result.files);
				AddAjaxDiv('#results', "ajax_msg_success", 'Successfully uploaded: <input type="text" value="'+data.result.files+'" onclick="this.select();" />');
			} else {
				// alert(data.result.errors);
				AddAjaxDiv('#results', "ajax_msg_error", data.result.errors);
			}
		},
		fail: function (e, data){
			alert('Failed. Try again.');
		}
	});
}

var $imagescroller;
function imagescroll_onload(){
	$imagescroller = $('#imagescroller');
	$('button').button();
	loadUploader();
	// $("#files").change(fileChange);
	$("#btnAdd").click(function(){
		$('#files').click();
	});
	$("#btnClear").click(resetUploader);
	$('#btnUpload').click(function(){
		// $imagescroller.unbind('mousemove');
		// progresstest();
		var data = {
			title: $('#title').val()
		}
		$('#uploadform').fileupload('option',{ formData: data })
			.fileupload('send',{files: filelist});
	});
	$imagescroller.on('click', '.imgdelete', function(){
		var i = $(this).parent().index();
		filelist.splice(i, 1);
		imagelist.splice(i, 1);
		$(this).parent().hide('slow',function(){
			$(this).remove();
			refreshScrollerWidth();
			$imagescroller.mousemove();
		});
	}).mousemove(onImageScrollerMouseMove);
}