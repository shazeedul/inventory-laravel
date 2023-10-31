<?php

namespace Modules\Supplier\DataTables;

use Modules\Supplier\Entities\Supplier;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class SupplierDataTable extends DataTable
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
            ->editColumn('mobile_no', function ($query) {
                return $query->mobile_no ?? '--';
            })
            ->editColumn('email', function ($query) {
                return $query->email ?? '--';
            })
            ->editColumn('address', function ($query) {
                return $query->address ?? '--';
            })
            ->editColumn('status', function ($query) {
                return $this->statusBtn($query);
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
    public function query(Supplier $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('supplier-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->responsive(true)
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->orderBy(4)
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
                ->title(@localize('Name'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('mobile_no')
                ->title(@localize('Mobile No'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('email')
                ->title(@localize('Email'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('address')
                ->title(@localize('Address'))
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
        return 'Supplier_' . date('YmdHis');
    }

    /**
     * Status Button
     *
     * @param  Supplier  $supplier
     */
    private function statusBtn($supplier): string
    {
        $status = '<select class="form-control" name="status" id="status_id_' . $supplier->id . '" ';
        $status .= 'onchange="userStatusUpdate(\'' . route(config('theme.rprefix') . '.status-update', $supplier->id) . '\',' . $supplier->id . ',\'' . $supplier->status . '\')">';
        foreach (Supplier::statusList() as $key => $value) {
            $status .= "<option value='$key' " . selected($key, $supplier->status) . ">$value</option>";
        }
        $status .= '</select>';

        return $status;
    }
}