

class KaTemplate extends HTMLElement {


    async connectedCallback() {
        await KaToolsV1.domReady();

        let div = document.createElement("div");
        div.innerHTML = this.innerHTML;
        let tpl = KaToolsV1.templatify(div);
        this.innerHTML = "";
        this.appendChild(tpl);
        let renderer = new KaV1Renderer(tpl);
        if (this.hasAttribute("wait")) {
            let wd = this.getAttribute("wait").split("@");
            let eventName = wd[0];
            let target = document;
            if (wd.length === 2) {
                target = KaToolsV1.querySelector(wd[1]);
            }
            target.addEventListener(eventName, (event) => {
                console.log("[ka-tpl] Rendering on event", event);
                renderer.render({$event: event});
            })
            return;
        }
        renderer.render({});

    }
}

customElements.define("ka-tpl", KaTemplate);
