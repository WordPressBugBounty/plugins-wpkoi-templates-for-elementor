// Admin page menu
document.addEventListener('DOMContentLoaded', function () {
    // Get all buttons and sections
    const buttons = document.querySelectorAll('#wpkoi-new-header .nav-btn');
    const sections = document.querySelectorAll('#wpkoi-page-body > div');

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

jQuery(document).ready(function($) {
	// Select the div with the class 'wpkoi-video'
	var videoDiv = document.querySelector('.wpkoi-video');

	// Create the iframe element
	var iframe = document.createElement('iframe');

	// Set the iframe's src attribute to the YouTube video link
	iframe.src = 'https://www.youtube.com/embed/Sixhe0-Ftl4';
	iframe.frameBorder = '0';
	iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
	iframe.allowFullscreen = true;

	// Append the iframe to the div
	videoDiv.appendChild(iframe);
});