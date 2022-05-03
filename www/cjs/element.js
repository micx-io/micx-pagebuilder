


function ka_define(name, callback, template=null) {


    let fn = class extends HTMLElement {
        connectedCallback() {
            let renderer = null;
            if (template !== null) {
                let tpl = KaToolsV1.templatify(template);
                this.appendChild(tpl);
                renderer = new KaV1Renderer(tpl);
            }
            callback(this, renderer);
        }
    }

    customElements.define(name, fn);
}
