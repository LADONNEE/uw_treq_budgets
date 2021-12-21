let scrollBarWidth = function() {
    // Creating invisible container
    const outer = document.createElement('div');
    outer.style.visibility = 'hidden';
    outer.style.overflow = 'scroll'; // forcing scrollbar to appear
    outer.style.msOverflowStyle = 'scrollbar'; // needed for WinJS apps
    document.body.appendChild(outer);

    // Creating inner element and placing it in the container
    const inner = document.createElement('div');
    outer.appendChild(inner);

    // Calculating difference between container's full width and the child width
    const scrollbarWidth = (outer.offsetWidth - inner.offsetWidth);

    // Removing temporary elements from the DOM
    outer.parentNode.removeChild(outer);

    return scrollbarWidth;
};

let padContent = function(padding) {
    const content = document.querySelectorAll('.js-scroll-lock-padding');

    for (let i = 0; i < content.length; i++) {
        content[i].style.paddingRight = padding + 'px';
    }
};

let scrollLock = function() {
    if (window.scrollbars.visible) {
        padContent(scrollBarWidth());
    }
    document.body.style.overflow = 'hidden';
};

let scrollLockOff = function() {
    padContent(0);
    document.body.style.overflow = 'visible';
};

export { scrollLock, scrollLockOff }
