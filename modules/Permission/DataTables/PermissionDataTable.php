<?php

namespace Modules\Permission\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Permission\Entities\Permission;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PermissionDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  QueryBuilder  $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return $query->actionBtn([
                    'show' => false,
                    'edit' => true,
                    'delete' => true,
                ]);
            })
            ->setRowId('id')
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Permission $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('permission-table')
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
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title(__('SI'))->searchable(false)->orderable(false)->width(30)->addClass('text-center'),
            Column::make('name')->title(__('Name'))->defaultContent('N/A'),
            Column::make('group')->title(__('Group'))->defaultContent('N/A'),
            Column::make('guard_name')->title(__('Guard'))->defaultContent('N/A'),
            Column::make('updated_at')->title(__('Last Update'))->defaultContent('N/A'),
            Column::computed('action')
                ->title(__('Action'))
                ->searchable(false)
                ->exportable(false)
                ->printable(false)
                ->width(80)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Permission_'.date('YmdHis');
    }
}
