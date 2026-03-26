!(function ($) {
    "use strict";

    var WPKoiEditor = {

        activeSection: null,
        editedElement: null,

        init: function () {

            // Elementor fully loaded
            window.elementor.on("preview:loaded", function () {
                elementor.$preview[0].contentWindow.WPKoiElementsEditor = WPKoiEditor;
                WPKoiEditor.onPreviewLoaded();
            });

            // ADD FILTER ONLY AFTER ELEMENTOR INIT
            elementor.hooks.addFilter('editor/style/styleText', function (css, context) {

                if (!context) {
                    return css;
                }

                var model = context.model,
                    customCSS = model.get('settings').get('wpkoi_custom_css');

                var selector = '.elementor-element.elementor-element-' + model.get('id');

                if ('document' === model.get('elType')) {
                    selector = elementor.config.document.settings.cssWrapperSelector;
                }

                if (customCSS) {
                    css += customCSS.replace(/selector/g, selector);
                }

                return css;
            });

        },

        onPreviewLoaded: function () {
            $("#elementor-preview-iframe")[0].contentWindow.elementorFrontend.hooks.addAction(
                "frontend/element_ready/wpkoi-dropbar.default",
                function ($scope) {
                    $scope.find(".wpkoi-dropbar-edit-link").on("click", function () {
                        window.open($(this).attr("href"));
                    });
                }
            );
        },
    };

    // WAIT FOR ELEMENTOR INIT
    $(window).on("elementor:init", function () {
        WPKoiEditor.init();
    });

    window.WPKoiElementsEditor = WPKoiEditor;

})(jQuery);