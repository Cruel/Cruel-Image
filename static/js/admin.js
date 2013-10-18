
function adminImages(){
	var $gallery = $('.admin-gallery');
	if ($gallery.length == 0)
		return;
	$('#content').block({
		css:{top:'100px'},
		centerY:false
	});

	$.contextMenu({
		autoHide: true,
		selector: '.admin-gallery img',
		callback: function(key, options) {
			var images = [];
			$(".ui-selected").each(function() {
				images.push($(this).data('imageid'));
			});
			if (key == "delete"){
				$.post('', {
					delete: images
				}, function(data){
					for (var id in data.images){
						if (data.images[id].success){
							$gallery.masonry('remove', $('[data-imageid='+id+']')).masonry('reload');
						} else {
							$('[data-imageid='+id+']').css('outline','3px solid red');
							alert(data.images[id].message);
						}
					}
					if ($(".ui-selected").length == 0){
						$(".ui-selectee").css('opacity','');
					}
				});
			} else if (key == "view"){
				var img_arr = [];
				$(".ui-selected").each(function() {
					img_arr.push([$(this).data('imageurl')]);
				});
				console.log(img_arr);
				$.slimbox(img_arr, 0);
			}
		},
		items: {
			"view": {name: "View", icon: "view"},
			"delete": {name: "Delete", icon: "delete"},
			"sep1": "---------",
			"quit": {name: "Test", icon: "quit"}
		}
	});
	$(document).on("mousedown", ".ui-selectee", function(e) {
		if (e.which == 3) { //Right-clicked
			if (!$(this).hasClass('ui-selected')) {
				$('.ui-selectee').removeClass('ui-selected').css('opacity','0.3');
				$(this).addClass('ui-selected').css('opacity','');
			}
		}
	});

	var imageTimeout,
		selectable_opts = {
			start: function(){
				imageTimeout = setTimeout(function(){
					$('.ui-selectee').css('opacity','');
				}, 250);
			},
			stop: function(){
				clearTimeout(imageTimeout);
				$('.ui-selectee').css('opacity', ($('.ui-selected').length > 0) ? '0.3' : '');
				$('.ui-selected').css('opacity','');
			}
		};

	$gallery.hide().imagesLoaded(function(){
		$('#content').unblock();
		$gallery.show().selectable(selectable_opts).masonry({
				itemSelector : 'img',
				columnWidth : 200,
				gutterWidth : 8,
			}).infinitescroll({
				navSelector  : '#page-nav',    // selector for the paged navigation
				nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
				itemSelector : '.admin-gallery > img',     // selector for all items you'll retrieve
				loading: {
					msgText: "<em>Loading more images...</em>",
					finishedMsg: 'No more pages to load.',
					img: '/static/loading.gif'
				}
			},
			// trigger Masonry as a callback
			function( newElements ) {
				// hide new items while they are loading
				var $newElems = $( newElements).css({ opacity: 0 }).hide();
				// ensure that images load before adding to masonry layout
				$newElems.imagesLoaded(function(){
					$newElems.show().css('opacity', ($('.ui-selected').length > 0) ? '0.3' : '');
					$gallery.masonry( 'appended', $newElems, true );
				});
			}
		);
	}).progress( function( isBroken, $images, $proper, $broken ){
			$('.blockMsg').html('Loading Images... ' + ( $proper.length + $broken.length ) + '/' + $images.length);
		});
	$('#infinite-loading').hide();
}

$(function(){
	adminImages();
	$('#btnSettingsSave').click(function(){
		$.post('', {
			settings: {
				title: $('#title').val(),
				theme: $('#theme').val(),
				apikey: $('#apikey').val(),
				admintab: $('#admintab:checked').length
			}
		},
		function(data){
//			alert(data);
			window.location.reload();
		});
	});
});
