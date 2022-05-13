

class KaTemplate extends HTMLElement {


    async connectedCallback() {
        await KaToolsV1.domReady();

        let div = document.createElement("div");
        div.innerHTML = this.innerHTML;
        let tpl = KaToolsV1.templatify(div);
        this.innerHTML = "";
        this.appendChild(tpl);
        let scope = {};
        let renderer = new KaV1Renderer(tpl);
        if (this.hasAttribute("scopeinit")) {
            let scopeinit = this.getAttribute("scopeinit")
            try {
                let cb = KaToolsV1.eval(scopeinit, {}, this);
                scope = cb(...await KaToolsV1.provider.arguments(cb));
            } catch (e) {
                console.error(`[ka-tpl] scopeinit: '${scopeinit}' excpetion: ${e} in`, this);
                throw e;
            }

        }
        if (this.hasAttribute("wait")) {
            let wd = this.getAttribute("wait").split("@");
            let eventName = wd[0];
            let target = document;
            if (wd.length === 2) {
                target = KaToolsV1.querySelector(wd[1]);
            }
            target.addEventListener(eventName, (event) => {
                console.log("[ka-tpl] Rendering on event", event);
                scope.$event = event;
                renderer.render(scope);
            })
            return;
        }


        renderer.render(scope);

    }
}

customElements.define("ka-tpl", KaTemplate);
