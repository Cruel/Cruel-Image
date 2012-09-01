function slimbox($selector){
	$selector.slimbox({
		initialWidth: 25,
		initialHeight: 25,
		overlayFadeDuration: 100,
		resizeDuration: 200,
		captionAnimationDuration: 100,
	}, null, function(el) {
		return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
	});
}

$(function(){
	var $gallery = $('.gallery');
	$('#content').block({
		css:{top:'100px'},
		centerY:false
	});
	$gallery.hide().imagesLoaded(function(){
		$('#content').unblock();
		$gallery.show().masonry({
			itemSelector : 'a',
		}).infinitescroll({
				navSelector  : '#page-nav',    // selector for the paged navigation
				nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
				itemSelector : '.gallery > a',     // selector for all items you'll retrieve
				loading: {
					msgText: "<em>Loading more images...</em>",
					finishedMsg: 'No more pages to load.',
					img: 'static/loading.gif'
				}
			},
			// trigger Masonry as a callback
			function( newElements ) {
				// hide new items while they are loading
				var $newElems = $( newElements).css({ opacity: 0 }).hide();
				// ensure that images load before adding to masonry layout
				$newElems.imagesLoaded(function(){
					slimbox($newElems);
					$newElems.show().animate({ opacity: 1 }, 1000);
					$gallery.masonry( 'appended', $newElems, true );
				});
			}
		);
	}).progress( function( isBroken, $images, $proper, $broken ){
		$('.blockMsg').html('Loading Images... ' + ( $proper.length + $broken.length ) + '/' + $images.length);
	});
	$('#infinite-loading').hide();
	slimbox($("a[rel^='lightbox']"));
});

// TODO: AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
// if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
// 	jQuery(function($) {
// 			slimbox($("a[rel^='lightbox']"));
// 	});
// }