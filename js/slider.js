$(document).ready(function() { 
	$('.shuffle').fadeIn(1000).randomImage({path: 'images/logos/'});
	$(window).everyTime(6000, function(i) {
		$('.shuffle').each(function(){          
			var seconds = $(this).index('.shuffle')+'500';
			//filename = $(this).attr('src').substring($(this).attr('src').lastIndexOf('/')+1,$(this).attr('src').length)
			$(this).wait(seconds).fadeOut(1500, function(){
				$(this).fadeIn(1500).randomImage({path: 'images/logos/'});
			});
		});
	});
});

(function($){ $.randomImage = { defaults: { path: 'images/logos/', myImages: [ '10021.png', '10035.png', '10051.png', '10057.png', '10093.png', '10138.png', '10139.png', '10142.png', '10145.png', '10150.png', '10153.png', '10179.png', '10222.png', '10239.png', '10919.png', '10979.png', '10987.png', '10989.png', '10996.png', '11007.png', '11055.png', '11097.png', '11105.png', '11118.png', '11142.png', '11150.png', '11158.png', '11163.png', '11164.png', '11180.png', '11187.png', '11207.png', '11218.png', '11221.png', '11867.png', '12131.png', '12444.png', '12574.png', '14753.png', '14755.png', '14771.png', '14776.png', '14791.png', '14899.png', '14909.png', '14929.png', '14988.png', '15377.png', '15451.png', '16062.png', '16300.png', '16331.png', '16361.png', '16365.png', '16374.png', '16376.png', '16409.png', '16485.png', '16615.png', '16617.png', '16715.png', '16743.png', '16834.png', '17927.png', '18179.png', '18284.png', '18339.png', '18480.png', '18511.png', '18544.png', '18715.png', '18718.png', '18822.png', '19211.png', '20789.png', '21744.png', '24533.png', '24959.png', '25595.png', '28506.png', '29058.png', '30017.png', '30156.png', '30420.png', '31046.png', '31801.png', '31950.png', '32161.png', '32645.png', '33178.png', '33395.png', '34710.png', '34763.png', '35513.png', '43377.png', '44228.png', '44347.png', '45654.png', '49438.png', '56099.png', '56783.png', '58570.png', '58649.png', '62079.png', '66804.png', '70387.png', '72861.png', '75176.png', '76366.png', '82541.png', '89535.png']   } }

    $.fn.extend({ randomImage:function(config) { 
            var config = $.extend({}, $.randomImage.defaults, config); 
            return this.each(function() {
                var imageNames = config.myImages;
                var imageNamesSize = imageNames.length;
                var randomNumber = Math.floor(Math.random()*imageNamesSize);
                var selectedImage = imageNames[randomNumber];
                var fullPath = config.path + selectedImage;
				if($('.shuffle[src="'+fullPath+'"]').length > 0)
				{
					//imageNames2 = config.myImages;
					for(i = 1; i > 0; i++)
					{ 
						//alert(imageNames[randomNumber]);
						//imageNames.splice(randomNumber, 1);
						//alert(imageNames[randomNumber]);
						//imageNamesSize = imageNames.length;
						randomNumber = Math.floor(Math.random()*imageNamesSize);
						selectedImage = imageNames[randomNumber];
						fullPath = config.path + selectedImage;
						if($('.shuffle[src="'+fullPath+'"]').length === 0)
						{i = -1;}
					}
				}
                $(this).attr( { src: fullPath, alt: selectedImage } ); 
            }); 
        }
    });
})(jQuery);

$.fn.wait = function(time, type) {
        time = time || 2000;
        type = type || "fx";
        return this.queue(type, function() {
            var self = this;
            setTimeout(function() {
                $(self).dequeue();
            }, time);
        });
    };