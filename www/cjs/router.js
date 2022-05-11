KaToolsV1.routes = {};
KaToolsV1.route = {
    "name": null,
    "route_orig": null,
    "params": {},
    "search": new URLSearchParams(window.location.search)
}

function ka_href(route_name, params = {}, search = null) {
    params = {...KaToolsV1.route.params, ...params};
    console.log(params);
    let route = KaToolsV1.routes[route_name];

    route = route.replaceAll(/{([a-zA-Z0-9_]+)}/g, (match, name) => {
        return params[name];
    });
    if (search !== null)
        return route + "?" + (new URLSearchParams(search));
    return route;
}

function ka_goto(route_name, params={}, search=null) {
    window.location.href = ka_href(route_name, params, search);
}

function ka_import_node(e) {
    if (e instanceof HTMLTemplateElement) {
        return document.importNode(e.content, true).cloneNode(true);
    }
    return e.cloneNode(true);
}


class KasimirV1_Router extends HTMLElement {


    routeDef2RegEx(route) {
        let regex = new RegExp(/{(?<param>[a-zA-Z0-9_]+)}/g)
        let r = route.replaceAll(regex, (matches, param) => {
            return `(?<${param}>[^/]+)`
        });
        return `^${r}$`;
    }

    async connectedCallback() {
        await KaToolsV1.domReady();
        let pathname = location.pathname;
        if (pathname.endsWith("/"))
            pathname = pathname.slice(0, -1);

        let foundRouteElement = null;
        let defaultElement = null;
        for (let e of this.children) {
            console.log(e);
            if (e.hasAttribute("default_route")) {
                defaultElement = e;
                continue;
            }
            if (e.getAttribute("route") !== null) {
                let route = e.getAttribute("route");
                if (route.endsWith("/"))
                    route = route.slice(0, -1);

                KaToolsV1.routes[e.getAttribute("route_name")] = route;

                let regex = new RegExp(this.routeDef2RegEx(route));

                let match = regex.exec(pathname);
                if (match === null)
                    continue;

                KaToolsV1.route.name = e.getAttribute("route_name");
                KaToolsV1.route.params = match.groups;

                foundRouteElement = e;

            }
        }
        if (foundRouteElement !== null)
            this.append(ka_import_node(foundRouteElement));
        else
            console.error("[ka-router] Undefined route: " + pathname);
            //this.append(ka_import_node(defaultElement));
    }

}

customElements.define("ka-router", KasimirV1_Router);


class KaToolsV1_Include extends HTMLElement {


    _importScriptRecursive(node, src) {
        let chels = node instanceof HTMLTemplateElement ? node.content.childNodes : node.childNodes;

        for (let s of chels) {
            if (s.tagName !== "SCRIPT") {
                this._importScriptRecursive(s, src);
                continue;
            }
            let n = document.createElement("script");

            for (let attName of s.getAttributeNames())
                n.setAttribute(attName, s.getAttribute(attName));
            n.innerHTML = s.innerHTML;
            try {
                let handler = onerror;
                window.onerror = (msg, url, line) => {
                    console.error(`[ka-include]: Script error in '${src}': ${msg} in line ${line}:\n>>>>>>>>\n`,
                        n.innerHTML.split("\n")[line-1],
                        "\n<<<<<<<<\n",
                        n.innerHTML);
                }
                s.replaceWith(n);
                window.onerror = handler;
            } catch (e) {
                console.error(`[ka-include]: Script error in '${src}': ${e}`, e);
                throw e;
            }
        }
    }

    static get observedAttributes() { return ["src"] }
    async attributeChangedCallback(name, oldValue, newValue) {
        if (name !== "src")
            return;
        if (newValue === "" || newValue === null)
            return;
        console.log("load by attr", newValue);
        let src = this.getAttribute("src");
        let result = await fetch(src);
        this.innerHTML = await result.text();
        this._importScriptRecursive(this, src);
    }

    async connectedCallback() {
        let src = this.getAttribute("src");
        if (src === "" || src === null)
            return;

    }

}

customElements.define("ka-include", KaToolsV1_Include);
