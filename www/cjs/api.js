


async function api_call(path, method="GET", body=null, search=null) {
    let url = `/v1/pagebuilder/${KaToolsV1.route.params.sub_id}/${KaToolsV1.route.params.site_id}/${path}`;
    if (search !== null) {
        url += "?" + (new URLSearchParams(search));
    }
    let response = await fetch(url, {method: method, body: body !== null ? JSON.stringify(body) : null});
    if ( ! response.ok)
        alert("Request failed: " + response.statusText);
    return response.json();
}
