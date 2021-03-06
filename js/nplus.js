jQuery(document).ready( function($) {
	
	function send_popup( title, text, popup_class, delay ) {
		
		// Initialize parameters
		title = title !== '' ? '<span class="title">' + title + '</span>' : '';
		text = text !== '' ? text : '';
		popup_class = popup_class !== '' ? popup_class : 'update';
		delay = typeof delay === 'number' ? delay : 20000;
		
		var object = $('<div/>', {
		    class: 'popup_notification ' + popup_class,
		    html: title + text + '<span class="close">&times;</span>'
		});
		
		$('#popup_container').prepend(object);
		
		$(object).hide().fadeIn(500);
		
		setTimeout(function() {
			
			$(object).slideUp(500);
			
		}, delay);
	}
	
	$('<div/>', { id: 'popup_container' } ).appendTo('body');
	$('body').on('click', '.close', function () { $(this).parent().slideUp(200); });
	
	var nmsg;

    $(document).on( 'heartbeat-tick.nplus_tick', function( e, data ) {
        
		console.log(data);
        
        if ( !data['message'] )
        	return;

		$.each( data['message'], function( index, notification ) {
			if ( index != nmsg ){
				send_popup( notification['title'], notification['content'], notification['type'] );
			}
			nmsg = index;
		});
    });
});