


async function api_call(path, method="GET", body=null) {
    let url = `/v1/pagebuilder/${KaToolsV1.route.params.sub_id}/${KaToolsV1.route.params.site_id}/${path}`;
    let response = await fetch(url, {method: method, body: body !== null ? JSON.stringify(body) : null});
    return response.json();
}
