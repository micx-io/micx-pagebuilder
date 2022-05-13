/**
 *
 * @param name
 * @param callback
 * @param {HTMLElement|string|null} template
 * @param {string|null} waitEvent
 */
function ka_define(name, callback, template=null, waitEvent=null) {


    let fn = class extends HTMLElement {
        async connectedCallback() {
            await KaToolsV1.domReady();

            let renderer = null;
            if (template !== null) {
                let tpl = KaToolsV1.templatify(template);
                this.appendChild(tpl);
                renderer = new KaV1Renderer(tpl);
            }
            if (waitEvent !== null) {
                let wd = waitEvent.split("@");
                let eventName = wd[0];
                let target = document;
                if (wd.length === 2) {
                    target = KaToolsV1.querySelector(wd[1]);
                }
                target.addEventListener(eventName, (event) => {
                    callback(this, renderer, event);
                })
                return;
            }
            callback(... await KaToolsV1.provider.arguments(callback, {
                "$this": this,
                "$tpl": renderer
            }));
        }
    }

    customElements.define(name, fn);
}


function html(htmlContent) {
    let e = document.createElement("template");
    e.innerHTML = htmlContent;
    return e;
}

/* @var $tpl {KaV1Renderer} */
