<?php

namespace Modules\Account\DataTables;

use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Modules\Account\Traits\Report;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Modules\Account\Entities\AccountVoucher;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\AccountVoucherType;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

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
        $voucherTypeId = request()->input('voucher_type_id');
        if ($voucherTypeId === null || $voucherTypeId === 'all') {
            $voucherTypeId = null;
        }

        if (request()->input('voucher_date') != null) {
            $dateRange = explode(" to ", request()->input('voucher_date'));
            $fromDate = Carbon::createFromFormat('Y-m-d', $dateRange[0])->format('Y-m-d');
            $toDate = Carbon::createFromFormat('Y-m-d', $dateRange[1])->format('Y-m-d');
        } else {
            $fromDate = Carbon::now()->subDay(30)->format('Y-m-d');
            $toDate = Carbon::now()->format('Y-m-d');
        }

        return $model->newQuery()
            ->when($voucherTypeId, function ($q) use ($voucherTypeId) {
                $q->where('account_voucher_type_id', $voucherTypeId);
            })
            ->where(function ($q) use ($fromDate, $toDate) {
                $q->where('voucher_date', '>=', $fromDate)
                    ->where('voucher_date', '<=', $toDate);
            })
            ->where('is_approved', 1);
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
            Column::make('DT_RowIndex')
                ->title(@localize('SL'))
                ->addClass('text-center')
                ->width(30)
                ->searchable(false)
                ->orderable(false),
            Column::make('voucher_date')
                ->title(@localize('Date'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('voucher_no')
                ->title(@localize('Voucher No'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('chart_of_account_id')
                ->title('Head Name')
                ->orderable(false)
                ->searchable(true),
            Column::make('ledger_comment')
                ->title('Narration')
                ->orderable(false)
                ->searchable(true),
            Column::make('debit')
                ->title('Debit')
                ->orderable(false)
                ->searchable(false),
            Column::make('credit')
                ->title('Credit')
                ->orderable(false)
                ->searchable(false),
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
