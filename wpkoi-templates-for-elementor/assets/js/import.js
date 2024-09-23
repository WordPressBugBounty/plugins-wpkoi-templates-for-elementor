// Admin page menu
document.addEventListener('DOMContentLoaded', function () {
    // Get all buttons and sections
    const buttons = document.querySelectorAll('#wet-sticky-header .nav-btn');
    const sections = document.querySelectorAll('#wet-page-body > div');

    // Add click event listener to each button
    buttons.forEach(button => {
        button.addEventListener('click', function () {
            // Get the target section from the button's data attribute
            const targetId = this.getAttribute('data-target');

            // Hide all sections
            sections.forEach(section => {
                section.style.display = 'none';
            });

            // Show the targeted section
            const targetSection = document.getElementById(targetId);
            targetSection.style.display = 'block';
        });
    });
});

// Update Element features
jQuery(document).ready(function($) {
    $('#wtfe-submit').on('click', function(e) {
        e.preventDefault();

        // Collect data from the form
        var data = {
            action: 'wpkoi_templates_for_elementor_lite_wtfe_submit',
            security: wtfe_ajax_obj.nonce,  // Pass the nonce created in PHP
            wtfe_element_effects: $('#wtfe_element_effects').is(':checked') ? 1 : 0,
            wtfe_advanced_headings: $('#wtfe_advanced_headings').is(':checked') ? 1 : 0,
            wtfe_countdown: $('#wtfe_countdown').is(':checked') ? 1 : 0,
            wtfe_darkmode: $('#wtfe_darkmode').is(':checked') ? 1 : 0,
            wtfe_scrolling_text: $('#wtfe_scrolling_text').is(':checked') ? 1 : 0,
            wtfe_qr_code: $('#wtfe_qr_code').is(':checked') ? 1 : 0
        };

        // Make the AJAX request
        $.ajax({
            url: wtfe_ajax_obj.ajax_url,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    $('#wtfe-response-message').html('<div class="notice notice-success"><p>' + response.data.message + '</p></div>');
                } else {
                    $('#wtfe-response-message').html('<div class="notice notice-error"><p>' + response.data.message + '</p></div>');
                }
            },
            error: function(xhr, status, error) {
                $('#wtfe-response-message').html('<div class="notice notice-error"><p>Something went wrong. Please try again.</p></div>');
            }
        });
    });
});

// Import process
jQuery(document).ready(function($) {
    var templateId, templateTitle, pageId;

    $('.wtfe-import-button').on('click', function() {
        templateId = $(this).data('template-id');
        templateTitle = $(this).data('template-title');
        $('#wtfe-import-popup').fadeIn();
        $('#wtfe-import-status').text('Click "Start Import" to begin. It will import the page template to Elementor templates and generates a new page with the template content.');

        $('#wtfe-start-import').show();
        $('#wtfe-link-page').remove();
    });

    $('#wtfe-start-import').on('click', function() {
		$('#wtfe-start-import').hide();
        $('#wtfe-import-status').text('1/3 Importing Elementor template...');

        $.ajax({
            type: 'POST',
            url: wtfe_ajax_obj.ajax_url,
            data: {
                action: 'wtfe_import_template_ajax',
                security: wtfe_ajax_obj.ajax_nonce,  // Pass nonce for security check
                template_id: templateId,
                template_title: templateTitle
            },
            success: function(response) {
                if (response.success) {
                    $('#wtfe-import-status').text(response.data.message);
                    createNewPage(response.data.template_id);
                } else {
                    $('#wtfe-import-status').text('Import Failed: ' + response.data);
                }
            },
            error: function() {
                $('#wtfe-import-status').text('An error occurred during the import.');
            }
        });
    });

    function createNewPage(importedTemplateId) {
        $('#wtfe-import-status').text('2/3 Creating new page...');

        $.ajax({
            type: 'POST',
            url: wtfe_ajax_obj.ajax_url,
            data: {
                action: 'wtfe_create_page_ajax',
                security: wtfe_ajax_obj.ajax_nonce,
                template_id: importedTemplateId,
                template_title: templateTitle
            },
            success: function(response) {
                if (response.success) {
                    $('#wtfe-import-status').text(response.data.message);
                    updatePageMeta(response.data.page_id, importedTemplateId);
                } else {
                    $('#wtfe-import-status').text('Page Creation Failed: ' + response.data);
                }
            },
            error: function() {
                $('#wtfe-import-status').text('An error occurred during the page creation.');
            }
        });
    }

    function updatePageMeta(pageId, importedTemplateId) {
        $('#wtfe-import-status').text('3/3 Updating page content and meta...');

        $.ajax({
            type: 'POST',
            url: wtfe_ajax_obj.ajax_url,
            data: {
                action: 'wtfe_update_page_meta_ajax',
                security: wtfe_ajax_obj.ajax_nonce,
                page_id: pageId,
                template_id: importedTemplateId
            },
            success: function(response) {
                if (response.success) {
                    $('#wtfe-import-status').text(response.data.message);
                    $('#wtfe-start-import').hide();
                    $('#wtfe-popup-content').append('<a id="wtfe-link-page" href="' + response.data.page_url + '" target="_blank" class="button button-primary">Go to the new page</a>');
                } else {
                    $('#wtfe-import-status').text('Meta Update Failed: ' + response.data);
                }
            },
            error: function() {
                $('#wtfe-import-status').text('An error occurred during the meta update.');
            }
        });
    }

    $('#wtfe-popup-close').on('click', function() {
        $('#wtfe-import-popup').fadeOut();
    });
});
