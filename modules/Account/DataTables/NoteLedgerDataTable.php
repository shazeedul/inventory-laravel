<?php

namespace Modules\Account\DataTables;

use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Modules\Account\Traits\Report;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Services\DataTable;
use Modules\Account\Entities\ChartOfAccount;

class NoteLedgerDataTable extends DataTable
{
    use Report;

    public function dataTable($query): DataTableAbstract
    {
        $forthLevel = request()->input('chart_of_account_id') != null ? ChartOfAccount::where('parent_id', request()->input('chart_of_account_id'))->get() : collect();

        if (request()->input('voucher_date') != null) {
            $dateRange = explode(" to ", request()->input('voucher_date'));
            $fromDate = Carbon::createFromFormat('Y-m-d', $dateRange[0])->format('Y-m-d');
            $toDate = Carbon::createFromFormat('Y-m-d', $dateRange[1])->format('Y-m-d');
        } else {
            $fromDate = Carbon::now()->subDay(30)->format('Y-m-d');
            $toDate = Carbon::now()->format('Y-m-d');
        }

        $request = new \Illuminate\Http\Request();
        $request['from_date'] = $fromDate;
        $request['to_date'] = $toDate;

        $data = $forthLevel->map(function ($data, $key) use ($request) {
            $request['chart_of_account_id'] = $data->id;
            return [
                'key' => (int) $key + 1,
                'head_name' => $data->name,
                'debit' => in_array($data->account_type_id, [1, 4]) ? $this->getClosingBalance($request) : 0.00,
                'credit' => in_array($data->account_type_id, [1, 4]) ? 0.00 : $this->getClosingBalance($request),
            ];
        });

        return datatables()->of($data);
    }

    public function query(): \Illuminate\Support\Collection
    {
        return collect([]);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): Builder
    {
        return $this->builder()
            ->setTableId('controlLedger-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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
            Column::make('key')->title('SL')->orderable(false)->searchable(false),
            Column::make('head_name')->title('Head Name')->orderable(false)->searchable(true),
            Column::make('debit')->title('Debit')->orderable(false)->searchable(false),
            Column::make('credit')->title('Credit')->orderable(false)->searchable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'ControlLedger_' . date('YmdHis');
    }
}
