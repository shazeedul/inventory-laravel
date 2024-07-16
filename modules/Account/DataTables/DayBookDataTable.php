<?php

namespace Modules\Account\DataTables;

use Carbon\Carbon;
use App\Traits\Account\Report;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\AccountVoucherType;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Account\Entities\AccountVoucher;

class DayBookDataTable extends DataTable
{
    use Report;

    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn();
    }

    public function query(AccountVoucher $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('daybook-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->footerCallback(
                'function ( row, data, start, end, display ) {
                    var api = this.api(), data;
                    var intVal = function ( i ) {
                        return typeof i === "string" ?
                            i.replace(/[\$,]/g, "")*1 :
                            typeof i === "number" ?
                                i : 0;
                    };
                    totalDebit = api.column(5).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    totalCredit = api.column(6).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    totalBalance = api.column(7).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    $( api.column(4).footer() ).html("Total");
                    $( api.column(5).footer() ).html(totalDebit.toFixed(2));
                    $( api.column(6).footer() ).html(totalCredit.toFixed(2));
                }'
            )
            ->responsive(true)
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->buttons([
                Button::make('reset')->className('btn btn-primary box-shadow--4dp btn-sm-menu'),
                Button::make('reload')->className('btn btn-primary box-shadow--4dp btn-sm-menu'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Column::make('key')->title('SL')->orderable(false)->searchable(false),
            Column::make('voucher_date')->title('Date')->orderable(true)->searchable(true),
            Column::make('voucher_no')->title('Voucher No')->orderable(false)->searchable(true),
            Column::make('head_name')->title('Head Name')->orderable(false)->searchable(true),
            Column::make('ledger_comment')->title('Narration')->orderable(false)->searchable(true),
            Column::make('debit')->title('Debit')->orderable(false)->searchable(false),
            Column::make('credit')->title('Credit')->orderable(false)->searchable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'DayBook_' . date('YmdHis');
    }
}
