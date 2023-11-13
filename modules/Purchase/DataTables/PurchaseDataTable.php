<?php

namespace Modules\Purchase\DataTables;

use Modules\Purchase\Entities\Purchase;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class PurchaseDataTable extends DataTable
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
            ->editColumn('date', function ($query) {
                return $query->date->format('d M, Y');
            })
            ->editColumn('status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Approve</span>' : '<span class="badge bg-danger">Pending</span>';
            })
            ->addColumn('action', function ($query) {
                return '<div aria-label="Action Button">
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm my-1 mx-1" onclick="' . "axiosModal('" . route(config('theme.rprefix') . '.show', $query->id) . '\')"><i class="fa fa-eye"></i></a>
                    <a href="' . route(config('theme.rprefix') . '.edit', $query->id) . '" class="btn btn-secondary btn-sm my-1 mx-1" ><i class="fa fa-edit"></i></a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="' . "delete_modal('" . route(config('theme.rprefix') . '.destroy', $query->id) . '\')"  title="' . __('Delete') . '"><i class="fa fa-trash"></i></a>
                </div>';
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id')
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Purchase $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('purchase-table')
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
            Column::make('purchase_no')
                ->title(@localize('Purchase No'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(true),
            Column::make('date')
                ->title(@localize('Date'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(true),
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
        return 'Purchase_' . date('YmdHis');
    }
}
