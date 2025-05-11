"use strict";

JOS.init({
  // disable: false, // Disable JOS gloabaly | Values :  'true', 'false'
  // debugMode: true, // Enable JOS debug mode | Values :  'true', 'false'
  passive: false, // Set the passive option for the scroll event listener | Values :  'true', 'false'

  once: true, // Disable JOS after first animation | Values :  'true', 'false' || Int : 0-1000
  animation: "fade-up", // JOS global animation type | Values :  'fade', 'slide', 'zoom', 'flip', 'fade-right', 'fade-left', 'fade-up', 'fade-down', 'zoom-in-right', 'zoom-in-left', 'zoom-in-up', 'zoom-in-down', 'zoom-out-right', 'zoom-out-left', 'zoom-out-up', 'zoom-out-down', 'flip-right', 'flip-left', 'flip-up', 'flip-down, spin, revolve, stretch, "my-custom-animation"
  // animationInverse: "static", // Set the animation type for the element when it is scrolled out of view | Values :  'fade', 'slide', 'zoom', 'flip', 'fade-right', 'fade-left', 'fade-up', 'fade-down', 'zoom-in-right', 'zoom-in-left', 'zoom-in-up', 'zoom-in-down', 'zoom-out-right', 'zoom-out-left', 'zoom-out-up', 'zoom-out-down', 'flip-right', 'flip-left', 'flip-up', 'flip-down, spin, revolve, stretch, "my-custom-animation"
  timingFunction: "ease", // JOS global timing function | Values :  'ease', 'ease-in', 'ease-out', 'ease-in-out', 'linear', 'step-start', 'step-end', 'steps()', 'cubic-bezier()', 'my-custom-timing-function'
  //mirror : false, // Set whether the element should animate back when scrolled out of view | Values :  'true', 'false'
  threshold: 0, // Set gloabal the threshold for the element to be visible | Values :  0-1
  delay: 0.5, // Set global the delay for the animation to start | Values :  0,1,2,3,4,5
  duration: 0.7, // Set global the duration for the animation playback | Values :  flota : 0-1 & int : 0,1,2,3,4,5

  // startVisible: "true", // Set whether the element should animate when the page is loaded | Values :  'true', 'false' || MS : 0-10000
  scrollDirection: "down", // Set the scroll direction for the element to be visible | Values :  'up', 'down', 'none'
  //scrollProgressDisable: true // disable or enable scroll callback function | Values :  'true', 'false'
  // intersectionRatio: 0.4, // Set the intersection ratio between which the element should be visible | Values :  0-1 (automaticaly set)
  // rootMargin_top: "0%", // Set by which percent the element should animate out (Recommended value between 10% to -30%)
  // rootMargin_bottom: "-50%", // Set by which percent the element should animate out (Recommended value between -10% to -60%)
  rootMargin: "0% 0% 15% 0%", // Set the root margin for the element to be visible | Values :  _% _% _% _%  (automaticaly set)
});

document.addEventListener('DOMContentLoaded', function() {
    // Handle JOS animations
    function handleJOSAnimations() {
        const josElements = document.querySelectorAll('.jos:not(.active)');

        josElements.forEach(element => {
            const rect = element.getBoundingClientRect();
            const isVisible = (
                rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.85 &&
                rect.bottom >= 0
            );

            if (isVisible) {
                element.classList.add('active');
            }
        });
    }

    handleJOSAnimations();

    window.addEventListener('scroll', handleJOSAnimations, { passive: true });

    function setupCodeBlocks() {
        const codeBlocks = document.querySelectorAll('pre');

        codeBlocks.forEach(block => {
            // Check if copy button already exists
            if (block.querySelector('.copy-button')) return;

            // Create copy button
            const copyButton = document.createElement('button');
            copyButton.className = 'absolute p-1 text-sm text-white rounded copy-button top-2 right-2 bg-white/10 hover:bg-white/20';
            copyButton.innerHTML = '<i class="fa-regular fa-copy"></i>';
            copyButton.title = 'Copy code';

            // Style the pre element for button positioning
            block.style.position = 'relative';

            copyButton.addEventListener('click', () => {
                const code = block.querySelector('code').innerText;
                navigator.clipboard.writeText(code).then(() => {
                    copyButton.innerHTML = '<i class="fa-regular fa-check"></i>';
                    setTimeout(() => {
                        copyButton.innerHTML = '<i class="fa-regular fa-copy"></i>';
                    }, 2000);
                });
            });

            block.appendChild(copyButton);
        });
    }

    setupCodeBlocks();

    document.addEventListener('livewire:load', function() {
        Livewire.hook('message.processed', (message, component) => {
            setupCodeBlocks();
            handleJOSAnimations();
        });
    });
});
