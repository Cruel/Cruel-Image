var filelist = [], imagelist = [];

function addFileToList(file){
	var image = new Image(),
		reader = new FileReader(),
		i = filelist.push(file);
	reader.onload = function (e) {
		image.src = e.target.result;
	};
	image.onload = function(){
		addImageToList(this, i-1);
	};
	reader.readAsDataURL(file);
}

function addImageToList(image, index){
	imagelist[index] = image;
	for (var i=0; i < imagelist.length; i++)
		if (imagelist[i] == undefined)
			return;
	if (imagelist.length == filelist.length){
		var addcount = 0;
		$('#scrollercaption').hide();
		$('#uploadbuttons').show('slow');
		for (i in imagelist){
			if (i >= $('#imagescroller li').length) {
				$('#imagescroller ul').append(
					$('<li></li>').append(imagelist[i]).css('opacity',0).delay(addcount*250).animate({opacity:1},1500)
				);
				addcount++;
				refreshScrollerWidth();
			}
		}
	}
}

function refreshScrollerWidth(){
	var x = 0,
		w = $('#imagescroller ul').width();
	$('#imagescroller ul').width('');
	$('#imagescroller li').each(function(){
		console.log($(this).outerWidth(true));
		x += $(this).outerWidth(true);
	});
	$('#imagescroller ul').width(x);
	if ($imagescroller.width() < x) {
		$imagescroller.mousemove(onImageScrollerMouseMove);
	} else {
		$imagescroller.unbind('mousemove');
		drawImageList();
	}
	console.log('Scroller Width: '+x);
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
	//$("#results").html(percent + " - " + $(this).offset().left + " - " + curX);
//	if (!scrollAnimating){
//		var leftDiff = Math.abs(newLeft-lastScrollLeft);
//		if (leftDiff > 100) {
//			scrollAnimating = true;
//			$list.animate({left:newLeft}, 200, function(){
//				scrollAnimating = false;
//			});
//		} else if (leftDiff > 5){
//			lastScrollLeft = newLeft;
			drawImageList(newLeft);
//		}
//	}
}

function drawImageList(offset, percent){
	if (typeof(offset) == 'undefined') offset = 0;
	if (typeof(percent) == 'undefined') percent = 100;
	var scrollerwidth = $('#imagescroller').width();
	$('#imageprogressbar').css('left', percent+'%');
	$('#imagescroller ul').css('left', offset);
}

var filetypes = /^image\/(gif|jpeg|png)$/;

function resetUploader(){
	filelist = [];
	imagelist = [];
	$('#imagescroller li').hide('slow', function(){
		$(this).remove();
	});
	$('#scrollercaption').fadeIn('slow');
	$('#uploadbuttons').hide('slow');
}

function clearResults(){
	$('#results > *').hide('slow', function(){
		$(this).remove();
	});
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
					if (filetypes.test(file.type)){
						addFileToList(file);
					} else {
						AddAjaxDiv('#results', "ajax_msg_error", 'The file type ('+file.type+') is not supported.');
					}
				}
			});
//			if (filelist.length > $('#imagescroller li').length)
//				addFileImages
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
			$imagescroller.unblock();
			resetUploader();
			clearResults();
			if (data.result.success) {
				var files = data.result.files,
					html = '',
					wrapclass = (files.length > 2) ? 'imgwrap-multi' : 'imgwrap';
				for (i in files){
					html += '<div class="'+wrapclass+'">\
								<img src="'+files[i].thumb_url+'" />\
								<input type="text" value="'+files[i].domain+files[i].url+'" onclick="this.select();" />\
								<button class="btnCopy">Copy</button>\
							</div>';
				}
				AddAjaxDiv('#results', "ajax_uploaded", html).hide().imagesLoaded(function(){
					$(this).show().find('.btnCopy').zclip({
						path: "static/ZeroClipboard.swf",
						copy: function(){
							return $(this).prev().val();
						},
						afterCopy: function(){
							$(this).poshytip('show');
						}
					}).poshytip({
							className: 'tip-twitter',
							timeOnScreen: 2000,
							content: 'Copied link',
							showOn: 'none',
							alignTo: 'target',
							alignX: 'center',
							offsetY: 5
						});
				})

			}
			for (i in data.result.errors)
				AddAjaxDiv('#results', "ajax_msg_error", data.result.errors[i]);
		},
		fail: function (e, data){
			$imagescroller.unblock();
			$('#uploadbuttons').show();
			alert('Failed. Try again.');
		}
	});
}

var $imagescroller,
	deletingImage = false;
function imagescroll_onload(){
	$imagescroller = $('#imagescroller');
	$('#uploadbuttons button').button();
	$('#uploadbuttons').hide();
	loadUploader();
	$('#btnAdd, #scrollercaption').click(function(){
		$('#files').click();
	});
	$("#btnClear").click(resetUploader);
	$('#btnUpload').click(function(){
		$('#uploadbuttons').hide();
		$imagescroller.block();
		var data = {
			title: $('#title').val()
		}
		$('#uploadform').fileupload('option',{ formData: data })
			.fileupload('send',{files: filelist});
	});
	$imagescroller.on('click', 'img', function(){
		if (imagelist.length == 1){
			resetUploader();
			return false;
		}
		if (deletingImage) return false;
		deletingImage = true;
		var i = $(this).parent().index();
		filelist.splice(i, 1);
		imagelist.splice(i, 1);
		$(this).parent().hide('slow',function(){
			$(this).remove();
			refreshScrollerWidth();
			deletingImage = false;
		});
	});
}