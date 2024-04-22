<?php

namespace Modules\Account\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Account\Entities\AccountOpeningBalance;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OpeningBalanceDataTable extends DataTable
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
            ->editColumn('financial_year_id', function ($query) {
                return $query->financialYear->name;
            })
            ->editColumn('chart_of_account_id', function ($query) {
                return $query->chartOfAccount->name;
            })
            ->editColumn('account_sub_type_id', function ($query) {
                return $query->accountSubType->name ?? null;
            })
            ->editColumn('action', function ($query) {
                $button = '';
                $button .= '<a href="' . route('admin.account.opening.balance.edit', $query->id) . '" class="btn btn-primary btn-sm me-2"><i class="fa fa-edit"></i></a>';
                $button .= '<a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="' . "delete_modal('" . route('admin.account.opening.balance.destroy', $query->id) . '\')"  title="' . localize('Delete') . '"><i class="fa fa-trash"></i></a>';
                return $button;
            })
            ->rawColumns(['action'])
            ->setRowId('id')
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     */
    public function query(AccountOpeningBalance $model): QueryBuilder
    {
        return $model->newQuery()->with(['accountSubType', 'financialYear', 'chartOfAccount']);
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('opening_balance-table')
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
            Column::make('financial_year_id')
                ->title(@localize('Year'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('opening_date')
                ->title(@localize('Date'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('chart_of_account_id')
                ->title(@localize('Account'))
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
            Column::make('action')
                ->title(@localize('Action'))
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
        return 'SubCode' . date('YmdHis');
    }
}
