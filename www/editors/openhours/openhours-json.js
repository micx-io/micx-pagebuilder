ka_define("open-hours-json", (self, $tpl) => {
        let scope = {
            data: window.data,
            days: [
                {text: "Montag", value: "1"},
                {text: "Dienstag", value: "2"},
                {text: "Mittwoch", value: "3"},
                {text: "Donnerstag", value: "4"},
                {text: "Freitag", value: "5"},
                {text: "Samstag", value: "6"},
                {text: "Sonntag", value: "7"},
            ],
            $fn: {
                del: (rowId) => {
                    scope.data.json.splice(rowId, 1);
                    $tpl.render(scope);
                },
                add: () => {
                    scope.data.json.push({dayOfWeek: "", from: "", till:"", status: null});
                    $tpl.render(scope);
                }
            }
        }
        $tpl.render(scope);
    },
    html`
<table class="table">
    <thead>
        <tr>
            <td>Wochentag</td>
            <td>Von / Bis Uhrzeit</td>
            <td>Status</td>
            <td></td>
        </tr>
    </thead>
    <tr ka:for='let rowId in data.json'>
        <td><select class="form-select" kap:options="days" kap:bind="data.json[rowId].dayOfWeek" ></td>
        <td>
            <div class="input-group">
                <input type="time" aria-label="Von Uhrzeit" class="form-control" kap:bind="data.json[rowId].from">
                <span class="input-group-text">bis</span>
                <input type="time" aria-label="Bis Uhrzeit" class="form-control" kap:bind="data.json[rowId].till">
            </div>
        </td>
        <td>
            <select class="form-select" kap:options="data._status_values" kap:bind="data.json[rowId].status">
        </td>
        <td class="text-end"><button class="btn btn-outline-secondary" kap:on:click="$fn.del(rowId)"><i class="bi bi-x-lg"></i></button></td>
    </tr>

    <tfoot>
    <tr>
        <td colspan="4" class="text-end">
            <button class="btn btn-outline-primary" kap:on:click="$fn.add()" title="Zeile hinzufügen"><i class="bi bi-plus-lg"></i></button>
        </td>
    </tr>
    </tfoot>
</table>`
);
