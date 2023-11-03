<?php

namespace Modules\Customer\DataTables;

use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Customer\Entities\Customer;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class CustomerDataTable extends DataTable
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
            ->editColumn('image', function ($query) {
                return '<img src="' . asset('images/user.png') . '" class="img-fluid" width="50" height="50" alt="User Image">';
            })
            ->editColumn('status', function ($query) {
                return $this->statusBtn($query);
            })
            ->addColumn('action', function ($query) {
                return $query->actionBtn(['show' => false, 'edit' => true, 'delete' => true]);
            })
            ->rawColumns(['image', 'status', 'action'])
            ->setRowId('id')
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Customer $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('customer-table')
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
                ->title(@localize('Name'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('image')
                ->title(@localize('Image'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(false)
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
        return 'Customer' . date('YmdHis');
    }

    /**
     * Status Button
     *
     * @param  Customer  $customer
     */
    private function statusBtn($customer): string
    {
        $status = '<select class="form-control" name="status" id="status_id_' . $customer->id . '" ';
        $status .= 'onchange="userStatusUpdate(\'' . route(config('theme.rprefix') . '.status-update', $customer->id) . '\',' . $customer->id . ',\'' . $customer->status . '\')">';
        foreach (Customer::statusList() as $key => $value) {
            $status .= "<option value='$key' " . selected($key, $customer->status) . ">$value</option>";
        }
        $status .= '</select>';

        return $status;
    }
}
