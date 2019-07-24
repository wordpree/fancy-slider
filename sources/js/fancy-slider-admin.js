(function( $ ){
  'use strict';
   
   /* hide or show sub input area when a specific item is selected */
    function radioDisplay(_class,_id){
	    var prop = $('ul.'+ _class + ' ' +'#'+_id).prop('checked');
	    var $target = $('ul.'+ _class + ' li').slice(2);
	    if (prop === true){
	 	   $target.hide();
	    }
	    $('ul.'+ _class + ' li:nth-child(1)').find('input').click(function(){
	 	    $target.show();
	    });
	    $('ul.'+ _class + ' li:nth-child(2)').find('input').click(function(){
	 	    $target.hide();
	    });
    }

    /* check a number input area valid or not */
    function numberChk(){
 
	 	var $inputs = $('input[type="number"]');
	 	var pat = /^\d+$/;
	 	$inputs.each(function(index) {
	 		$(this).before('<span class="invalid">invalid figure</span>');	 
	 	});
 
	     $('.wrap form').on('submit',function(e){
	 	   	$inputs.each(function(index) {
	 	   		var $this = $(this);
	 	   		var target = $this.val();
	 	   		if (! pat.test( target ) ){
	 	   			$this.focus().prev().addClass('activate');
	 			    e.preventDefault();
	 			    return false;
	 		   	}
	 	   	});		   	
	     });
 
	    	$('.wrap form').on('click keydown',function(){
	 	    $('span.activate ').blur().removeClass('activate');
	 	});
    }

    /* dialog window for creating new slider */
    var sliderCreate = {
	    _dialog:function(){
		    $('#dialog').dialog({
		    	autoOpen:false,
		    	modal: true,
		    	resizable: false,
				width: '60%',
				height: 550,
				draggable: false,
				show: true,
				position: {
					my: "center",
					at: "center"
				},
				maxHeight: 800,
				maxWidth :1024,
		    });
		    $('.dialog a').click(function(e){
				e.preventDefault();
				$('#dialog').dialog('open');     
		    });
	    },
	    _wpMedia:function(){
		   	var img_frame;
		    $('.fs-image-upload').click(function(e){
			    e.preventDefault();
			    if (img_frame) {
			    	img_frame.open();
			    	return;
			    }
			    img_frame = wp.media.frames.img_frame = wp.media({
				    title: 'select image(s)',
				    button: {
				    	text: 'Create',
				    },
				    library: {
	                    type : 'image',
	                },
				    multiple:true
			    });
			    img_frame.on('select',function(){
			    	var attachment;
			    	var attachments = img_frame.state().get('selection');
			    	attachments.map(function(currentVal){
					    attachment = currentVal.toJSON();
					    var src = attachment.sizes.thumbnail.url || attachment.url ;
					    $('.fs-image-preview ul.thumbnail').append('<li class="image"><img src="' + src + '"></li>');
			    	});
			    });
			    img_frame.open();
		   });
	    },
	    _ajax:function(){
	    	$('.fs-btn button').click(function(e){
	    		e.preventDefault();
	    		jQuery.post( 
		    	ajaxurl, 
		        { 
		        	action: 'fs_newslider_create',
		        	nonce: $('.slider-container').data('nonce') 
		        }, 
	            function(res) {
	            	console.log(res);
				    alert(res);
		        });
	    	});    
	    },
	    _start:function(){
	    	this._dialog();
	    	this._wpMedia();
	    	this._ajax();
	    }

    };

   
   $( document ).ready(function(){
	   radioDisplay('lazyload','wpfs_lazyload_progressive');
	   radioDisplay('centre','wpfs_centre_disable');
	   radioDisplay('sync','wpfs_sync_disable');
	   radioDisplay('autoplay','wpfs_autoplay_disable');
	   numberChk();
	   sliderCreate._start();
	   
   });

   $( window ).load(function(){


   });




})( jQuery );