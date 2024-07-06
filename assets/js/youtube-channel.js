/* jshint esversion: 6 */

// initialize BiggerPicture
let bp = BiggerPicture({
    target: document.body
});
document.addEventListener('click', function(event) {
    // If the clicked element doesn`t have the right selector, bail
    if (!event.target.parentElement || !event.target.parentElement.matches('.ytc-lightbox')) return;
    // Don`t follow the link
    event.preventDefault();
    // Trigger BiggerPicture
    bp.open({
        items: event.target.parentElement,
        el: event.target.parentElement,
        scale: 0.80
    });
}, false);