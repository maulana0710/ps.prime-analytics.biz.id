function tabClicks(tabClickEvent) {
    let clickedTab = tabClickEvent.currentTarget;
    let clickedTabBlock = clickedTab.closest(".responsive-tabs-section");
    let currentTabs = clickedTabBlock.querySelectorAll(".responsive-tab");

    if (!clickedTab.classList.contains('close-accordion')
        && clickedTab.classList.contains('active-tab')
        && window.innerWidth < 768
        && (clickedTabBlock.classList.contains('responsive-simple-tabs-section') || clickedTabBlock.classList.contains('responsive-accordion-section'))) {
        clickedTab.classList.add("close-accordion");
    } else {
        clickedTab.classList.remove("close-accordion");
    }

    for (let i = 0; i < currentTabs.length; i++) {
        currentTabs[i].classList.remove("active-tab");
    }

    if (!clickedTab.classList.contains('close-accordion')) {
        clickedTab.classList.add("active-tab");
    }

    tabClickEvent.preventDefault();

    if (clickedTabBlock.classList.contains("responsive-tabs-big-image-section")) {
        let myImgPanes = clickedTabBlock.querySelectorAll(".responsive-tab-image");

        for (let i = 0; i < myImgPanes.length; i++) {
            myImgPanes[i].classList.remove("active-tab");

            if (myImgPanes[i].classList.contains(clickedTab.classList[0])) {
                myImgPanes[i].classList.add("active-tab");
            }
        }
    }

    let myContentPanes = clickedTabBlock.querySelectorAll(".responsive-tab-content");

    for (let i = 0; i < myContentPanes.length; i++) {
        myContentPanes[i].classList.remove("active-tab");
    }

    let anchorReference = tabClickEvent.target;
    let activePaneId = "";

    if (anchorReference.getAttribute("href")) {
        activePaneId = anchorReference.getAttribute("href");
    } else if (anchorReference.closest(".responsive-tab-link")) {
        activePaneId = anchorReference.closest(".responsive-tab-link").getAttribute("href");
    } else if (anchorReference.querySelector(".responsive-tab-link")) {
        activePaneId = anchorReference.querySelector(".responsive-tab-link").getAttribute("href");
    } else if (anchorReference.querySelectorAll(".responsive-tab-link")[0]) {
        activePaneId = anchorReference.querySelectorAll(".responsive-tab-link")[0].getAttribute("href");
    }

    if (activePaneId) {
        let activePane = clickedTabBlock.querySelector(activePaneId);

        if (activePane.classList.contains('sub-tab-content')) {
            activePane.closest('.responsive-tab-content').classList.add("active-tab");
            setAccordionHeight();
        } else {
            activePane.classList.add("active-tab");
            setAccordionHeight();
        }
    }
}

function subTabClicks(subTabClickEvent) {
    subTabClickEvent.stopPropagation();

    let clickedSubTab = subTabClickEvent.currentTarget;
    let clickedSubTabSection = clickedSubTab.closest(".responsive-tabs-section");
    let clickedSubTabBlock = clickedSubTab.closest(".responsive-tabs-section").querySelector('.responsive-tabs-content-list .active-tab');

    if (clickedSubTabSection.classList.contains('responsive-simple-tabs-section')
        && window.innerWidth < 768) {
        clickedSubTabBlock = clickedSubTabSection.querySelector('.responsive-tabs-list .active-tab');
    }

    let currentSubTabs = clickedSubTabBlock.querySelectorAll(".sub-tab-name");

    if (currentSubTabs.length === 0) {
        let clickedTabBlock = clickedSubTab.closest(".responsive-tabs-section").querySelector('.responsive-tabs-list .active-tab');
        currentSubTabs = clickedTabBlock.querySelectorAll(".sub-tab-name");
    }

    if (!clickedSubTab.classList.contains('close-accordion')
        && clickedSubTab.classList.contains('active-sub-tab')
        && window.innerWidth < 768
        && (clickedSubTabSection.classList.contains('responsive-simple-tabs-section') || clickedSubTabSection.classList.contains('responsive-accordion-section'))) {
        clickedSubTab.classList.add("close-accordion");
    } else {
        clickedSubTab.classList.remove("close-accordion");
    }

    for (let i = 0; i < currentSubTabs.length; i++) {
        currentSubTabs[i].classList.remove("active-sub-tab");
        const accordionItemBody = currentSubTabs[i].querySelector('.accordion-item-body');

        if (accordionItemBody) {
            accordionItemBody.style.maxHeight = 0;
        }
    }

    if (!clickedSubTab.classList.contains('close-accordion')) {
        clickedSubTab.classList.add("active-sub-tab");
        const accordionItemBody = clickedSubTab.querySelector('.accordion-item-body');

        if (accordionItemBody) {
            accordionItemBody.style.maxHeight = accordionItemBody.scrollHeight + "px";
        }
    }

    subTabClickEvent.preventDefault();

    let myContentSubPanes = clickedSubTabBlock.querySelectorAll(".sub-tab-content");

    for (let i = 0; i < myContentSubPanes.length; i++) {
        myContentSubPanes[i].classList.remove("active-sub-tab");
    }

    let anchorSubReference = subTabClickEvent.target;
    let activeSubPaneId = anchorSubReference.getAttribute("href");
    let activeSubPane = clickedSubTabBlock.querySelector(activeSubPaneId);

    if (activeSubPane) {
        activeSubPane.classList.add("active-sub-tab");
    }
}

function setAccordionHeight() {
    let activeSubTabs = document.querySelectorAll('.active-sub-tab');
    for (let i = 0; i < activeSubTabs.length; i++) {
        let accordionItemBody = activeSubTabs[i].querySelector('.accordion-item-body');
        if (accordionItemBody) {
            accordionItemBody.style.maxHeight = accordionItemBody.scrollHeight + "px";
        }
    }
}

function contentMobileClicks(contentMobileClickEvent) {
    contentMobileClickEvent.stopPropagation();
}

/**
 * Slider Widget JS Handler Class
 */
class ResponsiveTabsWidgetHandler extends elementorModules.frontend.handlers.Base {
    /**
     * On Init
     *
     * Runs when the widget is loaded and initialized in the frontend.
     */
    onInit() {
        const tabsList = document.querySelectorAll('.responsive-tabs-list');
        let isDown = false;
        let startX;
        let scrollLeft;

        for (let i = 0; i < tabsList.length; i++) {
            if (tabsList[i].classList.contains('responsive-tabs-with-scroll')) {
                tabsList[i].addEventListener('mousedown', (e) => {
                    isDown = true;
                    startX = e.pageX - tabsList[i].offsetLeft;
                    scrollLeft = tabsList[i].scrollLeft;
                });
                tabsList[i].addEventListener('mouseleave', () => {
                    isDown = false;
                });
                tabsList[i].addEventListener('mouseup', () => {
                    isDown = false;
                    let leftBtn = tabsList[i].closest(".responsive-tabs-section").querySelector('.tab-scroll-angle-left');
                    let rightBtn = tabsList[i].closest(".responsive-tabs-section").querySelector('.tab-scroll-angle-right');
                    checkScrollBtn (rightBtn, leftBtn, tabsList[i]);
                });
                tabsList[i].addEventListener('mousemove', (e) => {
                    if(!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - tabsList[i].offsetLeft;
                    const walk = x - startX;
                    tabsList[i].scrollLeft = scrollLeft - walk;
                });
                tabsList[i].addEventListener('touchend', () => {
                    let leftBtn = tabsList[i].closest(".responsive-tabs-section").querySelector('.tab-scroll-angle-left');
                    let rightBtn = tabsList[i].closest(".responsive-tabs-section").querySelector('.tab-scroll-angle-right');
                    checkScrollBtn (rightBtn, leftBtn, tabsList[i]);
                });
            }
        }

        let tabs = document.querySelectorAll(".responsive-tab");

        for (let i = 0; i < tabs.length; i++) {
            tabs[i].removeEventListener("click", tabClicks);
            tabs[i].addEventListener("click", tabClicks);
        }

        let subTabs = document.querySelectorAll(".sub-tab-name");

        for (let i = 0; i < subTabs.length; i++) {
            subTabs[i].removeEventListener("click", subTabClicks);
            subTabs[i].addEventListener("click", subTabClicks);
        }

        if (window.innerWidth < 768) {
            let subTabContentMobile = document.querySelectorAll(".sub-tab-content-mobile");

            for (let i = 0; i < subTabContentMobile.length; i++) {
                subTabContentMobile[i].removeEventListener("click", contentMobileClicks);
                subTabContentMobile[i].addEventListener("click", contentMobileClicks);
            }

            let tabContentMobile = document.querySelectorAll(".tab-content-mobile");

            for (let i = 0; i < tabContentMobile.length; i++) {
                tabContentMobile[i].removeEventListener("click", contentMobileClicks);
                tabContentMobile[i].addEventListener("click", contentMobileClicks);
            }
        }

        function rightScrollButton() {
            let tabLists = document.querySelectorAll(".responsive-tabs-list");

            for (let i = 0; i < tabLists.length; i++) {
                if (tabLists[i].classList.contains('responsive-tabs-with-scroll')) {
                    let leftBtn = tabLists[i].closest(".responsive-tabs-section").querySelector('.tab-scroll-angle-left');
                    let rightBtn = tabLists[i].closest(".responsive-tabs-section").querySelector('.tab-scroll-angle-right');
                    checkScrollBtn (rightBtn, leftBtn, tabLists[i]);
                }
            }
        }

        rightScrollButton();
        setAccordionHeight();

        function checkScrollBtn (rightBtn, leftBtn, currentScrollBlock) {
            let widthRange = Math.round(currentScrollBlock.scrollWidth - currentScrollBlock.clientWidth);
            let scrollLeft = Math.round(currentScrollBlock.scrollLeft);

            if (scrollLeft > 0 && scrollLeft <= widthRange) {
                leftBtn.classList.add("active");
            } else {
                leftBtn.classList.remove("active");
            }

            if (scrollLeft >= widthRange) {
                rightBtn.classList.remove("active");
            } else {
                rightBtn.classList.add("active");
            }
        }

        let rightScrollBtn = document.querySelectorAll(".tab-scroll-angle-right");

        function rightScrollBtnClicks(btnClickEvent) {
            let clickedBtn = btnClickEvent.currentTarget;
            let clickedBtnSection = clickedBtn.closest(".responsive-tabs-section");
            let nextBtn = clickedBtnSection.querySelector('.tab-scroll-angle-left');
            let currentScrollBlock = clickedBtnSection.querySelector('.responsive-tabs-list');
            let startListPosition = currentScrollBlock.scrollLeft;
            let step = currentScrollBlock.querySelector(".responsive-tab").clientWidth;

            currentScrollBlock.scrollLeft = Math.round(startListPosition + step);

            checkScrollBtn(clickedBtn, nextBtn, currentScrollBlock);
        }

        for (let i = 0; i < rightScrollBtn.length; i++) {
            rightScrollBtn[i].addEventListener("click", rightScrollBtnClicks)
        }

        let leftScrollBtn = document.querySelectorAll(".tab-scroll-angle-left");

        function leftScrollBtnClicks(btnClickEvent) {
            let clickedBtn = btnClickEvent.currentTarget;
            let clickedBtnSection = clickedBtn.closest(".responsive-tabs-section");
            let nextBtn = clickedBtnSection.querySelector('.tab-scroll-angle-right');
            let currentScrollBlock = clickedBtnSection.querySelector('.responsive-tabs-list');
            let startListPosition = currentScrollBlock.scrollLeft;
            let step = currentScrollBlock.querySelector(".responsive-tab").clientWidth;

            currentScrollBlock.scrollLeft = Math.round(startListPosition - step);

            checkScrollBtn(nextBtn, clickedBtn, currentScrollBlock);
        }

        for (let i = 0; i < leftScrollBtn.length; i++) {
            leftScrollBtn[i].addEventListener("click", leftScrollBtnClicks)
        }
    }
}

/**
 * Register JS Handler for the Slider Widget
 *
 * When Elementor frontend was initiated, and the widget is ready, register the widget
 * JS handler.
 */
window.addEventListener('elementor/frontend/init', () => {
    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(ResponsiveTabsWidgetHandler, {$element});
    };

    elementorFrontend.hooks.addAction('frontend/element_ready/responsive-tabs-with-icons.default', addHandler);
    elementorFrontend.hooks.addAction('frontend/element_ready/responsive-tabs-with-small-images.default', addHandler);
    elementorFrontend.hooks.addAction('frontend/element_ready/responsive-tabs-with-big-image.default', addHandler);
    elementorFrontend.hooks.addAction('frontend/element_ready/responsive-accordion.default', addHandler);
    elementorFrontend.hooks.addAction('frontend/element_ready/responsive-simple-tabs-with-icons.default', addHandler);
});

window.addEventListener('load', function () {
    setAccordionHeight();
});

window.addEventListener('resize', function () {
    setAccordionHeight();
});