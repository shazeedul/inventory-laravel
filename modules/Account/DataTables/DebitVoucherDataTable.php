<?php

namespace Modules\Account\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Account\Entities\AccountVoucher;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DebitVoucherDataTable extends DataTable
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
            ->editColumn('status', function ($query) {
                return $query->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function ($query) {
                return $query->actionBtn(['show' => false, 'edit' => true, 'delete' => true]);
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id')
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     */
    public function query(AccountVoucher $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('financial_year-table')
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
            Column::make('name')
                ->title(@localize('Financial Year'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('start_date')
                ->title(@localize('From Date'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('end_date')
                ->title(@localize('To Date'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('status')
                ->title(@localize('Status'))
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
        return 'FinancialYear' . date('YmdHis');
    }
}
