<?php

namespace Modules\Account\DataTables;

use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Account\Entities\AccountSubCode;
use Modules\Account\Entities\AccountVoucher;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class PendingVoucherDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  mixed  $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('checkbox', function ($row) {
                return '<input type="checkbox" name="voucher_checkbox[]" class="voucher_checkbox" value="' . $row->id . '">';
            })
            ->editColumn('voucher_no', function ($row) {
                return $row->voucherType?->name . '-' . $row->voucher_no;
            })
            ->filterColumn('voucher_no', function ($row, $keyword) {
                $row->whereHas('voucherType', function ($row) use ($keyword) {
                    $row->where('name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('chart_of_account_id', function ($row) {
                return $row->chartOfAccount?->name;
            })
            ->filterColumn('chart_of_account_id', function ($row, $keyword) {
                $row->whereHas('chartOfAccount', function ($row) use ($keyword) {
                    $row->where('name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('reverse_code', function ($row) {
                return $row->reverseCode?->name;
            })
            ->filterColumn('reverse_code', function ($row, $keyword) {
                $row->whereHas('reverseCode', function ($row) use ($keyword) {
                    $row->where('name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('account_sub_type_id', function ($row) {
                return $row->accountSubType?->name;
            })
            ->filterColumn('account_sub_type_id', function ($row, $keyword) {
                $row->whereHas('accountSubType', function ($row) use ($keyword) {
                    $row->where('name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('debit', function ($row) {
                return $row->debit ?? 0;
            })
            ->editColumn('credit', function ($row) {
                return $row->credit ?? 0;
            })
            ->rawColumns(['checkbox']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(AccountVoucher $model): QueryBuilder
    {
        return $model->newQuery()
            ->with([
                'chartOfAccount',
                'reverseCode',
                'accountSubType',
                'voucherType'
            ])
            ->where('is_approved', false)
            ->orderBy('voucher_date', 'desc');
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('pending_voucher-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->responsive(true)
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            // ->orderBy(4)
            ->buttons([
                Button::make('reset')->className('btn btn-primary box-shadow--4dp btn-sm-menu'),
                Button::make('reload')->className('btn btn-primary box-shadow--4dp btn-sm-menu'),
            ]);
    }

    /**
     * Get columns.
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
            Column::computed('checkbox')
                ->title('<input type="checkbox" id="check_all" onclick="selectAll()"> All')
                ->orderable(false)
                ->exportable(false)
                ->printable(false)
                ->width(10),
            Column::make('voucher_no')
                ->title(@localize('Voucher No'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(true),
            Column::make('voucher_date')
                ->title(@localize('Date'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(true),
            Column::make('chart_of_account_id')
                ->title(@localize('Account Name'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('ledger_comment')
                ->title(@localize('Ledger Comment'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('account_sub_type_id')
                ->title(@localize('Sub Type'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('reverse_code')
                ->title(@localize('Reversed Account'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('debit')
                ->title(@localize('Debit'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('credit')
                ->title(@localize('Credit'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'PendingVoucher' . date('YmdHis');
    }
}
