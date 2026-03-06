(function($){

    function initWPKoiSticky($element){

        const data = $element.data('wpkoi-sticky');
        if (!data) return;

        let settings;

        try {
            settings = typeof data === 'string' ? JSON.parse(data) : data;
        } catch (e) {
            return;
        }

        // NORMALIZING

        settings.top = parseFloat(settings.top) || 0;
        settings.bottom = parseFloat(settings.bottom) || 0;
        settings.zindex = parseInt(settings.zindex) || 10;
        settings.disable_on = Array.isArray(settings.disable_on) ? settings.disable_on : [];
        settings.stop_at = typeof settings.stop_at === 'string'
            ? settings.stop_at.trim()
            : '';
        settings.mode = settings.mode === 'viewport' ? 'viewport' : 'parent';
        settings.parallax = settings.parallax === 'yes';
        settings.parallax_speed = parseFloat(settings.parallax_speed) || 1;

        const hasStopSelector = settings.stop_at !== '';

        // VARIABLES

        const parent = $element.parent();

        let isActive = true;
        let placeholder = null;
        let originalElementTop = $element.offset().top;
        let fixedLeft = null;
        let fixedWidth = null;
		

        // HELPER FUNCTIONS

        function safeQuerySelector(selector){
            if (!selector) return null;
            try {
                return document.querySelector(selector);
            } catch {
                return null;
            }
        }

        function ensurePlaceholder(rect){
            if (placeholder) return;

            placeholder = $('<div class="wpkoi-sticky-placeholder"></div>');

            placeholder.css({
                height: rect.height + 'px',
                width: rect.width + 'px',
                flex: '0 0 ' + rect.width + 'px',
                boxSizing: 'border-box'
            });

            $element.after(placeholder);
        }

        function calculateParentEnd(parentTop, parentHeight, elementHeight){
            let end = parentTop + parentHeight - elementHeight - settings.bottom;

            if (settings.parallax && settings.parallax_speed < 1) {
                const extra = (parentHeight - elementHeight) * (1 - settings.parallax_speed);
                end += extra;
            }

            return end;
        }

        function calculateEnd(parentTop, parentHeight, elementHeight){

            if (!hasStopSelector) {
                return calculateParentEnd(parentTop, parentHeight, elementHeight);
            }

            const stopElement = safeQuerySelector(settings.stop_at);

            if (!stopElement) {
                return calculateParentEnd(parentTop, parentHeight, elementHeight);
            }

            const stopTop = stopElement.getBoundingClientRect().top + window.scrollY;
            return stopTop - elementHeight - settings.bottom;
        }

        function checkResponsive(){

            const width = window.innerWidth;

            if (
                (settings.disable_on.includes('desktop') && width >= 1025) ||
                (settings.disable_on.includes('tablet') && width <= 1024 && width >= 768) ||
                (settings.disable_on.includes('mobile') && width < 768)
            ){
                isActive = false;
            }
        }

        function isStopOutsideParent(){

            if (!hasStopSelector) return false;

            const stopElement = safeQuerySelector(settings.stop_at);
            if (!stopElement) return false;

            const stopTop = stopElement.getBoundingClientRect().top + window.scrollY;
            const parentBottom = parent.offset().top + parent.outerHeight();

            return stopTop > parentBottom;
        }

        // STICKY STATES

        function activateFixed(){

            const rect = $element[0].getBoundingClientRect();
            const scrollTop = $(window).scrollTop();
            const parentTop = parent.offset().top;

            ensurePlaceholder(rect);

            if (fixedLeft === null) {
                fixedLeft = rect.left;
                fixedWidth = rect.width;
            }

            let finalTop = settings.top;

            if (settings.parallax && settings.parallax_speed < 1) {
                const delta = scrollTop - (parentTop - settings.top);
                finalTop = settings.top - delta * (1 - settings.parallax_speed);
            }

            $element.css({
                position: 'fixed',
                top: finalTop + 'px',
                left: fixedLeft + 'px',
                width: fixedWidth + 'px',
                zIndex: settings.zindex
            });
        }

        function activateFixedAtEnd(){

            if (!hasStopSelector) return;

            const stopElement = safeQuerySelector(settings.stop_at);
            if (!stopElement) return;

            const rect = $element[0].getBoundingClientRect();
            const stopTop = stopElement.getBoundingClientRect().top + window.scrollY;

            ensurePlaceholder(rect);

            const freezeTop = stopTop - rect.height - settings.bottom;

            $element.css({
                position: 'fixed',
                top: (freezeTop - window.scrollY) + 'px',
                left: rect.left + 'px',
                width: rect.width + 'px',
                zIndex: settings.zindex
            });
        }

        function activateAbsolute(){

            const rect = $element[0].getBoundingClientRect();
            const parentRect = parent[0].getBoundingClientRect();

            ensurePlaceholder(rect);

            let absoluteTop;

            if (settings.parallax && settings.parallax_speed < 1) {
                absoluteTop = rect.top - parentRect.top;
            } else {
                absoluteTop = parent.outerHeight() - rect.height - settings.bottom;
            }

            const relativeLeft = rect.left - parentRect.left;

            $element.css({
                position: 'absolute',
                top: absoluteTop + 'px',
                left: relativeLeft + 'px',
                width: rect.width + 'px',
                zIndex: settings.zindex
            });
        }

        function resetSticky(){

            fixedLeft = null;
            fixedWidth = null;

            if (placeholder) {
                placeholder.remove();
                placeholder = null;
            }

            $element.css({
                position: '',
                top: '',
                left: '',
                width: '',
                zIndex: ''
            });
        }

        // MAIN LOGIC

        function updateSticky(){

            if (!isActive) {
                resetSticky();
                return;
            }

            const scrollTop = $(window).scrollTop();
            const parentTop = parent.offset().top;
            const parentHeight = parent.outerHeight();
            const elementHeight = $element.outerHeight();

            const start = settings.mode === 'viewport'
                ? originalElementTop - settings.top
                : parentTop - settings.top;

            const end = calculateEnd(parentTop, parentHeight, elementHeight);

            if (scrollTop > start && scrollTop < end) {

                activateFixed();

            } else if (scrollTop >= end) {

                if (settings.mode === 'viewport' && hasStopSelector) {
                    activateFixedAtEnd();
                } else {
                    isStopOutsideParent()
                        ? activateFixedAtEnd()
                        : activateAbsolute();
                }

            } else {
                resetSticky();
            }
        }

        // EVENTS

        checkResponsive();
        updateSticky();

        let ticking = false;

        function onScroll(){
            if (!ticking) {
                requestAnimationFrame(function(){
                    updateSticky();
                    ticking = false;
                });
                ticking = true;
            }
        }

        $(window).on('scroll', onScroll);

        let resizeTimer;

        $(window).on('resize', function(){

            originalElementTop = $element.offset().top;

            clearTimeout(resizeTimer);

            resizeTimer = setTimeout(function(){
                resetSticky();
                checkResponsive();
                updateSticky();
            }, 150);
        });
    }

    function initAll(){
        $('.wpkoi-sticky-container').each(function(){
            initWPKoiSticky($(this));
        });
    }

    $(window).on('elementor/frontend/init', function(){

        elementorFrontend.hooks.addAction(
            'frontend/element_ready/container.default',
            function($scope){
                if ($scope.hasClass('wpkoi-sticky-container')) {
                    initWPKoiSticky($scope);
                }
            }
        );

        initAll();
    });

})(jQuery);