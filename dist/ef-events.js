function EFEventsModals(focusables = 'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), [tabindex="0"]') {
  this.modal;
  this.focusables = focusables;
}

EFEventsModals.prototype.init = function(target, id) {
  var instance = new Object();
  instance.htmlId = id;
  instance.button = target;
  instance.modal = document.getElementById(id);
  instance.container = instance.modal.parentNode;
  instance.close = Array.from(instance.modal.getElementsByTagName("button")).pop();

  instance.modal.classList.add("expanded");
  instance.container.setAttribute("aria-hidden", false);
  instance.button.setAttribute("aria-expanded", true);
  instance.close.focus();

  var modalFocusables = instance.modal.querySelectorAll(this.focusables);
  instance.focusables = Array.prototype.slice.call(modalFocusables);

  document.addEventListener("keydown", this.keyup, true);

  this.modal = instance;
}

EFEventsModals.prototype.click = function() {
  EFEventsModals.modals.forEach(function(item) {
    if (event.target === item.modal) {
      event.preventDefault();
    }
  });
}

EFEventsModals.prototype.keyup = function() {
  let KEY = {
    TAB: 9,
    ESCAPE: 27
  }

  if (event.keyCode == KEY.ESCAPE) {
    EFEventsModals.closeAll("escaped");

  } else if (event.keyCode === KEY.TAB) {
    let focusables = EFEventsModals.modal.focusables;
    let first = focusables[0];
    let last = focusables[focusables.length - 1];

    let dontMove = first === last;
    let targetFirst = ! event.shiftKey && last === document.activeElement;
    let targetLast = event.shiftKey && first === document.activeElement;
    if (dontMove) {
      event.preventDefault();

    } else if (targetLast) {
      last.focus();
      event.preventDefault();

    } else if (targetFirst) {
      first.focus();
      event.preventDefault();

    }
  }
}

EFEventsModals.prototype.closeAll = function(reason = null) {
  let modal = EFEventsModals.modal;
  if (modal !== null) {
    let containerClicked = reason === null && modal.container === event.target;
    let closeClicked     = reason === null && modal.close === event.target;
    let escapeKey        = reason === "escaped";
    if (modal !== null && (containerClicked || closeClicked || escapeKey)) {
      modal.container.setAttribute("aria-hidden", true);
      modal.button.setAttribute("aria-expanded", false);
      modal.modal.classList.remove("expanded");
      modal.button.focus();
      document.removeEventListener("keydown", this.keyup, true);
      EFEventsModals.modal = null;
    }
  }
}

EFEventsModals = new EFEventsModals;
