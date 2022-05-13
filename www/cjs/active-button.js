
KaToolsV1.createElement = (tag, proto, parentElem=null) => {
    if (parentElem === null)
        parentElem = document;
    let newElem = parentElem.createElement(tag);

}


class ActiveButton {



    constructor(elem, onclick=null, optClasses="") {
        /**
         * @var {HTMLElement}
         */
        this._button;
        this._spinner;
        this._origElement;
        this._optClasses;

        if ( ! (elem instanceof HTMLElement)) {
            elem = KaToolsV1.querySelector(elem);
        }
        this._optClasses = optClasses;
        this._button = elem;
        if (onclick)
            this._button.addEventListener("click", onclick);
    }

    deactivate(spinner=true) {
        this._button.setAttribute("disabled", "disabled");
        if (spinner)
            this.spinner();
    }

    acivate() {
        this._button.removeAttribute("disabled");
        this.spinner(false);
    }

    click() {
        this._button.dispatchEvent(new Event("click"));
    }

    spinner(active = true) {
        if(active) {
            if (this._button.querySelector("[role='status']")) {
                this._spinner = this._button.querySelector("[role='status']").cloneNode(true);
                this._spinner.removeAttribute("hidden");
            } else {
                this._spinner = document.createElement("span");
                this._spinner.setAttribute("class", "spinner-border spinner-border-sm");
            }
            let origIcon = this.#button.querySelector("i");
            if (origIcon !== null) {
                this._origElement = origIcon.cloneNode(true);
                origIcon.replaceWith(this._spinner);
            } else {
                this._button.appendChild(this._spinner);
            }
            return;
        }
        if (this._spinner) {
            if (this._origElement !== null) {
                this._spinner.replaceWith(this._origElement)
                return;
            }
            this._button.removeChild(this.#spinner);
            this._spinner = null;
        }
    }

}
