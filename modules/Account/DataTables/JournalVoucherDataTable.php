<?php

namespace Modules\Account\DataTables;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Modules\Account\Entities\AccountVoucher;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class JournalVoucherDataTable extends DataTable
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
            ->addColumn('action', function ($query) {
                $button = '';
                $button .= '<a href="javascript:void(0);"  onclick="' . "axiosModal('" . route('admin.account.voucher.journal.show', $query->id) . '\')" title="' . localize('Show') . '" class="btn btn-primary btn-sm me-2"><i class="fas fa-eye"></i></a>';
                if (!$query->is_approved) {
                    $button .= '<a href="' . route('admin.account.voucher.journal.edit', $query->id) . '" class="btn btn-secondary btn-sm me-2"><i class="fas fa-edit"></i></a>';
                    $button .= '<a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="' . "delete_modal('" . route('admin.account.voucher.journal.destroy', $query->id) .  '\')"  title="' . localize('Delete') . '"><i class="fa fa-trash"></i></a>';
                } else {
                    $button .= '<a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="' . "reverseData('" . $query->id . '\')"  title="' . localize('Reverse') . '"><i class="fas fa-undo"></i></a>';
                }
                return $button;
            })
            ->editColumn('ledger_comment', function ($query) {
                return Str::limit($query->ledger_comment, 10);
            })
            ->editColumn('account_sub_type_id', function ($query) {
                return $query->accountSubType?->name ?? '--';
            })
            ->editColumn('chart_of_account_id', function ($query) {
                return $query->chartOfAccount?->name ?? '--';
            })
            ->editColumn('reverse_code', function ($query) {
                return $query->reverseCode?->name;
            })
            ->editColumn('debit', function ($query) {
                return $query->debit ?? 0.00;
            })
            ->editColumn('credit', function ($query) {
                return $query->credit ?? 0.00;
            })
            ->rawColumns(['action'])
            ->setRowId('id')
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     */
    public function query(AccountVoucher $model): QueryBuilder
    {
        return $model->newQuery()->with(['chartOfAccount', 'reverseCode', 'accountSubType'])->where('account_voucher_type_id', 4)->orderBy('voucher_no', 'DESC');
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('journal-voucher-table')
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
            Column::make('voucher_no')
                ->title(@localize('Voucher No'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('date')
                ->title(@localize('Date'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
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
            Column::make('reversed_code')
                ->title(@localize('Reversed Account'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::computed('action')
                ->title(@localize('Action'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(false)
                ->orderable(false)
                ->exportable(false)
                ->printable(false),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'JournalVoucher' . date('YmdHis');
    }
}
