
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
    constructor(elem, onclick=null) {
        if ( ! (elem instanceof HTMLElement)) {
            elem = KaToolsV1.querySelector(elem);
        }
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

    spinner(active = true) {
        if(active) {
            this.#spinner = document.createElement("span");
            this.#spinner.setAttribute("class", "spinner-border spinner-border-sm");
            this.#button.appendChild(this.#spinner);
            return;
        }
        if (this.#spinner) {
            this.#button.removeChild(this.#spinner);
            this.#spinner = null;
        }

    }

}
