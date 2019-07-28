/*-----------------------------------------------------------------------------------

	File Name: trishakuck.js
	Author: Xavian Ang (xavianang@openmindsresources.com)
	Description: Additional js changes to trishakuck.com

-----------------------------------------------------------------------------------*/

jQuery(document).ready(function(){



});

jQuery(window).scroll(function(){
	if( jQuery('body').hasClass('home') && jQuery('body').hasClass('desktop-device') )
	{
		var from_top = jQuery(window).scrollTop();
		if( from_top > 80 )
		{
			jQuery('.navbar-fixed-top').addClass('bg-enabled');
		}
		else
		{
			jQuery('.navbar-fixed-top').removeClass('bg-enabled');	
		}
	}
});