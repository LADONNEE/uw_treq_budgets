let envWarningScrollLock = {
    scrollBarWidth: function () {
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
    },

    padContent: function (padding) {
        const content = document.querySelectorAll('.js-scroll-lock-padding');

        for (let i = 0; i < content.length; i++) {
            content[i].style.paddingRight = padding + 'px';
        }
    },

    scrollLock: function () {
        $('body').addClass('js-scroll-lock-padding');

        if (window.scrollbars.visible) {
            this.padContent(this.scrollBarWidth());
        }
        document.body.style.overflow = 'hidden';
    },

    scrollLockOff: function () {
        this.padContent(0);
        document.body.style.overflow = 'visible';
    }
};

(function (envWarningScrollLock) {
    const envTrigger = $('#env-warning-trigger');

    function showModal() {
        const modal = $('#the-modal');

        envWarningScrollLock.scrollLock();
        document.body.style.overflow = 'hidden';
        modal.addClass('transition').show().removeClass('transition');
    }

    function closeModal() {
        const modal = $(this).closest('.coe-util__modal__backdrop');

        modal.addClass('transition');
        setTimeout(function() {
            modal.hide();
            envWarningScrollLock.scrollLockOff();
        }, 300);
    }

    function triggerModal() {
        const modalTitle = 'Test Server';
        const modalHtml = `
            <div class="coe-util__modal__backdrop" style="display: none;" id="the-modal">
                <div class="coe-util__modal__position">
                    <div class="coe-util__modal__container">
                        <div class="coe-util__modal__header">
                            <div class="coe-util__modal__title"></div>
                            <div class="coe-util__modal__close js-coe-util--modal--close">&times;</div>
                        </div>
                        <div class="coe-util__modal__body"></div>
                        <div class="coe-util__modal__footer">
                            <button class="js-coe-util--modal--accept btn btn-primary">I Understand</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        const modal = $(modalHtml);

        envTrigger.append(modal);
        modal.find('.coe-util__modal__title').text(modalTitle);
        if (envTrigger.data('include')) {
            modal.find('.coe-util__modal__body').load(envTrigger.data('include'));
        } else {
            modal.find('.coe-util__modal__body')
                .text('This site is for testing purposes only. Work done here will not show up on the live site.');
        }

        showModal();
        modal.find('.js-coe-util--modal--accept, .js-coe-util--modal--close').click(setEnvCookie).click(closeModal);
    }

    function setEnvCookie() {
        document.cookie = `dismissEnvWarning=1; path=/;`;
    }

    function checkEnvCookieSet() {
        const cookieValue = document.cookie
            .split('; ')
            .find(row => row.startsWith('dismissEnvWarning'));

        return !!cookieValue;
    }

    function showBanner() {
        envTrigger.prepend(`
            <div class="arrow-ribbon modal-trigger" style="cursor: pointer;">
                Test Server
                <span style="display: block; color: white;" class="small" >What does this mean?</span>
            </div>
        `);

        $(envTrigger).find('.modal-trigger').click(function () {
            triggerModal()
        });
    }

    function init() {
        showBanner();
        if (!checkEnvCookieSet()) {
            triggerModal();
        }
    }

    if (envTrigger.length > 0) {
        init();
    }
})(envWarningScrollLock);
