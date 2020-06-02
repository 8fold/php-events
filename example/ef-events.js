var efEVisible = false;
var efEModal;
var efEContainer;
var efEButton;
var efECloseButton;

function efEventsDisplayModal(target, id) {
    efEModal       = document.getElementById(id);
    efEContainer   = efEModal.parentNode;
    efEButton      = target;
    efECloseButton = efEModal.getElementsByTagName("button");

    if (efECloseButton.length > 0) {
        efECloseButton = efECloseButton[0];
    }

    efEModal.classList.add("expanded");
    efEModal.setAttribute("tabindex", 1);
    efEContainer.setAttribute("aria-hidden", false);
    efEButton.setAttribute("aria-expanded", true);
    efECloseButton.focus();
    efEVisible = true;


    document.addEventListener("focus", function(event) {
        if (efEVisible && ! efEModal.contains(event.target)) {
            event.stopPropagation();
            efEModal.focus();
        }
    }, true);

    document.addEventListener("keydown", function(event) {
        if (efEVisible && (! event.keyCode || event.keyCode === 27)) {
            efEventsCloseModals();
        }
    });
}

function efEventsCloseModals() {
    if (event.target === efEModal.parentNode || event.target === efECloseButton) {
        efEModal.classList.remove("expanded");
        efEModal.setAttribute("tabindex", -1);
        efEContainer.setAttribute("aria-hidden", true);
        efEButton.setAttribute("aria-expanded", false);
        efEButton.focus();
    }
}
