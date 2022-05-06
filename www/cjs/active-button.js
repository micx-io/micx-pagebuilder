
KaToolsV1.createElement = (tag, proto, parentElem=null) => {
    if (parentElem === null)
        parentElem = document;
    let newElem = parentElem.createElement(tag);

}


class ActiveButton {

    /**
     * @var {HTMLElement}
     */
    #button;
    #spinner;
    #origElement;
    #optClasses;

    constructor(elem, onclick=null, optClasses="") {
        if ( ! (elem instanceof HTMLElement)) {
            elem = KaToolsV1.querySelector(elem);
        }
        this.#optClasses = optClasses;
        this.#button = elem;
        if (onclick)
            this.#button.addEventListener("click", onclick);
    }

    deactivate(spinner=true) {
        this.#button.setAttribute("disabled", "disabled");
        if (spinner)
            this.spinner();
    }

    acivate() {
        this.#button.removeAttribute("disabled");
        this.spinner(false);
    }

    click() {
        this.#button.dispatchEvent(new Event("click"));
    }

    spinner(active = true) {
        if(active) {
            if (this.#button.querySelector("[role='status']")) {
                this.#spinner = this.#button.querySelector("[role='status']").cloneNode(true);
                this.#spinner.removeAttribute("hidden");
            } else {
                this.#spinner = document.createElement("span");
                this.#spinner.setAttribute("class", "spinner-border spinner-border-sm");
            }
            let origIcon = this.#button.querySelector("i");
            if (origIcon !== null) {
                this.#origElement = origIcon.cloneNode(true);
                origIcon.replaceWith(this.#spinner);
            } else {
                this.#button.appendChild(this.#spinner);
            }
            return;
        }
        if (this.#spinner) {
            if (this.#origElement !== null) {
                this.#spinner.replaceWith(this.#origElement)
                return;
            }
            this.#button.removeChild(this.#spinner);
            this.#spinner = null;
        }
    }

}
