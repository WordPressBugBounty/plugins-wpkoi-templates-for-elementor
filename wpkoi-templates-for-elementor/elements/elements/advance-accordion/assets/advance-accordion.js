(function($){

    var WPKoiAccordion = function($scope){
		
		var $accordion = $scope.find('.wpkoi-elements-adv-accordion');

        if (!$accordion.length) return;

        var speed = $accordion.data('speed') || 300;
		var contentSpeed = 300;
        var type = $accordion.data('type') || 'accordion';

        var $headers = $accordion.find('.wpkoi-elements-accordion-header');
		
		$accordion.find('.wpkoi-elements-accordion-header').css(
			'--anim-speed',
			speed + 'ms'
		);

        // Default active
        $accordion.find('.active-default').each(function(){
            $(this).addClass('active');
            $(this).next('.wpkoi-elements-accordion-content')
                .addClass('active')
                .show();
        });

        $headers.off('click.wpkoi').on('click.wpkoi', function(){
			
			var $this = $(this);
            var $content = $this.next('.wpkoi-elements-accordion-content');

            if (type === 'accordion') {

                if ($this.hasClass('active')) {
                    $this.removeClass('active');
                    $content.removeClass('active').slideUp(contentSpeed);
                } else {
                    $headers.removeClass('active');
                    $headers.next('.wpkoi-elements-accordion-content')
                        .removeClass('active')
                        .slideUp(contentSpeed);

                    $this.addClass('active');
                    $content.slideDown(contentSpeed, function(){
                        $content.addClass('active');
                    });
                }

            } else {

                if ($this.hasClass('active')) {
                    $this.removeClass('active');
                    $content.removeClass('active').slideUp(contentSpeed);
                } else {
                    $this.addClass('active');
                    $content.slideDown(contentSpeed, function(){
                        $content.addClass('active');
                    });
                }

            }

        });

    };

    // Elementor frontend hook
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/wpkoi-elements-adv-accordion.default',
            WPKoiAccordion
        );
    });

})(jQuery);