ka_define("open-hours-vacation", ($tpl) => {
        let scope = {
            data: window.data,

            $fn: {
                del: (index) => {
                    scope.data.vacation.splice(index, 1);
                    $tpl.render(scope);
                },
                add: () => {
                    scope.data.vacation.push({from: "", till:"", short_text: "", text: ""});
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
            <td>Von / Bis Datum</td>
            <td>Nachricht</td>
            <td></td>
        </tr>
    </thead>
    <tr ka:for='let rowId in data.vacation'>

        <td>
            <div class="input-group">
                <input type="date" aria-label="Von Datum" class="form-control" kap:bind="data.vacation[rowId].from">
                <span class="input-group-text">bis</span>
                <input type="date" aria-label="Bis Datum" class="form-control" kap:bind="data.vacation[rowId].till">
            </div>
        </td>
        <td>
            <input type="text" aria-label="Kurzbeschreibung" placeholder="Kurzbeschreibung" class="form-control" kap:bind="data.vacation[rowId].title">
            <textarea class="form-control" placeholder="Hinweistext" kap:options="data._status_values" kap:bind="data.vacation[rowId].text"></textarea>
        </td>
        <td class="text-end">
            <button class="btn btn-outline-secondary" kap:on:click="$fn.del(rowId)"><i class="bi bi-x-lg"></i></button>
        </td>
    </tr>
    <tfoot>
    <tr>
        <td colspan="4" class="text-end">
            <button class="btn btn-outline-primary" kap:on:click="$fn.add()"><i class="bi bi-plus-lg"></i></button>
        </td>
    </tr>
    </tfoot>
</table>`
);
