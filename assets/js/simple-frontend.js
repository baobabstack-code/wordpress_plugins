// Simple Frontend Plugin JavaScript

jQuery(document).ready(function($) {
    'use strict';

    // Contact form submission
    $('#sfp-contact-form').on('submit', function(e) {
        e.preventDefault();

        var formData = {
            action: 'sfp_submit_contact',
            nonce: sfpData.nonce,
            name: $('#sfp-name').val(),
            email: $('#sfp-email').val(),
            message: $('#sfp-message').val()
        };

        $.ajax({
            url: sfpData.ajaxurl,
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('button.sfp-btn').prop('disabled', true).text('Sending...');
            },
            success: function(response) {
                if (response.success) {
                    $('#sfp-response')
                        .removeClass('error')
                        .addClass('success')
                        .text(response.data)
                        .fadeIn();
                    
                    $('#sfp-contact-form')[0].reset();
                    
                    setTimeout(function() {
                        $('#sfp-response').fadeOut();
                    }, 5000);
                } else {
                    showError(response.data);
                }
            },
            error: function() {
                showError('An error occurred. Please try again.');
            },
            complete: function() {
                $('button.sfp-btn').prop('disabled', false).text('Send Message');
            }
        });
    });

    // Show error message
    function showError(message) {
        $('#sfp-response')
            .removeClass('success')
            .addClass('error')
            .text(message)
            .fadeIn();
        
        setTimeout(function() {
            $('#sfp-response').fadeOut();
        }, 5000);
    }

    // Counter animation
    var countersAnimated = false;
    
    function animateCounters() {
        if (countersAnimated) return;
        
        $('.counter-number').each(function() {
            var $counter = $(this);
            var targetNumber = parseInt($counter.data('number'));
            var currentNumber = 0;
            var increment = targetNumber / 100;
            var duration = 2000; // 2 seconds
            var stepTime = duration / 100;

            var counterInterval = setInterval(function() {
                currentNumber += increment;
                
                if (currentNumber >= targetNumber) {
                    $counter.text(targetNumber);
                    clearInterval(counterInterval);
                } else {
                    $counter.text(Math.floor(currentNumber));
                }
            }, stepTime);
        });
        
        countersAnimated = true;
    }

    // Trigger counter animation on scroll
    $(window).on('scroll', function() {
        if ($('.counter-number').length && !countersAnimated) {
            var elementOffset = $('.counter-display').offset().top;
            var windowScroll = $(window).scrollTop() + $(window).height();
            
            if (windowScroll > elementOffset - 100) {
                animateCounters();
            }
        }
    });

    // Trigger counter animation if already in view
    if ($('.counter-number').length && !countersAnimated) {
        var elementOffset = $('.counter-display').offset().top;
        var windowScroll = $(window).scrollTop() + $(window).height();
        
        if (windowScroll > elementOffset) {
            animateCounters();
        }
    }
});
