<?php

namespace Modules\Stock\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class StockReportDataTable extends DataTable
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
            ->editColumn('name', function ($query) {
                return $query->name;
            })
            ->editColumn('category', function ($query) {
                return $query->category->name;
            })
            ->editColumn('unit', function ($query) {
                return $query->unit->name;
            })
            ->editColumn('purchase_quantity', function ($query) {
                return $query->purchaseDetail->sum('quantity');
            })
            ->editColumn('invoice_quantity', function ($query) {
                return $query->invoiceDetail->sum('quantity');
            })
            ->editColumn('stock', function ($query) {
                return $query->quantity;
            })
            ->setRowId('id')
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()
            ->select('products.*', DB::raw('SUM(purchase_details.quantity) as total_purchase_quantity'), DB::raw('SUM(invoice_details.quantity) as total_invoice_quantity'))
            ->leftJoin('purchase_details', 'products.id', '=', 'purchase_details.product_id')
            ->leftJoin('invoice_details', 'products.id', '=', 'invoice_details.product_id')
            ->groupBy('products.id')
            ->with(['category', 'unit', 'purchaseDetail' => function ($q) {
                $q->whereHas('purchase', function ($q) {
                    $q->where('status', 1);
                });
            }, 'invoiceDetail' => function ($q) {
                $q->whereHas('invoice', function ($q) {
                    $q->where('status', 1);
                });
            }]);
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('stock-table')
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
                ->title(@localize('Product Name'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('category')
                ->title(@localize('Category'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::make('unit')
                ->title(@localize('Unit'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(true)
                ->orderable(false),
            Column::computed('purchase_quantity')
                ->title(@localize('Purchase Quantity'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(false)
                ->orderable(false),
            Column::computed('invoice_quantity')
                ->title(@localize('Invoice Quantity'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(false)
                ->orderable(false),
            Column::computed('stock')
                ->title(@localize('Stock'))
                ->addClass('text-center')
                ->width(100)
                ->searchable(false)
                ->orderable(false),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Stock_' . date('YmdHis');
    }
}
