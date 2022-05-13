ka_define("open-hours-table", ($tpl) => {
        let scope = {
            data: window.data,
            $fn: {
                del: (rowId) => {
                    scope.data.table.splice(rowId, 1);
                    $tpl.render(scope);
                },
                add: () => {
                    scope.data.table.push({day: "", times: ""});
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
            <td>Tag/Tage</td>
            <td>Uhrzeit</td>
            <td></td>
        </tr>
    </thead>
    <tr ka:for='let rowId in data.table'>
        <td><input class="form-control" type="text" kap:bind="data.table[rowId].day"></td>
        <td><input class="form-control" type="text" kap:bind="data.table[rowId].time"></td>
        <td class="text-end"><button class="btn btn-outline-secondary" kap:on:click="$fn.del(rowId)"><i class="bi bi-x-lg"></i></button></td>
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
