<?php

namespace Modules\Account\DataTables;

use Carbon\Carbon;
use App\Models\CashBook;
use App\Traits\Account\Report;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Modules\Account\Entities\ChartOfAccount;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CashBookDataTable extends DataTable
{
    use Report;

    public function dataTable($query): DataTableAbstract
    {
        $fromDate = Carbon::now()->subDay(30)->format('d/m/Y');
        $toDate = date('d/m/Y');
        $acc_coa_id = ChartOfAccount::where('head_level', 4)->where('is_active', 1)->where('is_cash_nature', 1)->first()->id ?? '';

        $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');

        $request = new \Illuminate\Http\Request();
        $request['from_date'] = $fromDate;
        $request['to_date'] = $toDate;
        $request['acc_coa_id'] = $acc_coa_id;

        $getBalanceOpening = $this->getOpeningBalance($request);
        $getTransactionList = $this->getTransactionDetail($request, $getBalanceOpening);

        $tableFooter = [
            'totalDebit' => $getTransactionList->pluck('debit')->sum(),
            'totalCredit' => $getTransactionList->pluck('credit')->sum()
        ];

        $acc_type = Cache::remember($request->acc_coa_id, 3600, function () use ($request) {
            return ChartOfAccount::findOrFail($request->acc_coa_id);
        });

        if (in_array($acc_type->acc_type_id, [1, 4])) {
            $tableFooter['totalBalance'] = ($getBalanceOpening + $tableFooter['totalDebit']) - $tableFooter['totalCredit'];
        } else {
            $tableFooter['totalBalance'] = ($getBalanceOpening + $tableFooter['totalCredit']) - $tableFooter['totalDebit'];
        }

        $data = $getTransactionList->map(function ($data, $key) {
            return [
                'key' => (int) $key + 1,
                'voucher_date' => $data->voucher_date,
                'voucher_no' => $data->voucher_no,
                'account_name' => $data->accReverseCode ? $data->accReverseCode->account_name : '---',
                'head_name' => $data->accCoa->account_name,
                'ledger_comment' => $data->ledger_comment,
                'debit' => number_format($data->debit, 2),
                'credit' => number_format($data->credit, 2),
                'balance' => number_format($data->balance, 2)
            ];
        });

        return datatables()->of($data)
            ->with('tableFooter', $tableFooter);
    }

    public function query(): \Illuminate\Support\Collection
    {
        
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): Builder
    {
        return $this->builder()
            ->setTableId('cashbook-table')
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
            ['data' => 'key', 'name' => 'key', 'title' => 'sl'],
            ['data' => 'voucher_date', 'name' => 'voucher_date', 'title' => 'date'],
            ['data' => 'account_name', 'name' => 'account_name', 'title' => 'account_name'],
            ['data' => 'head_name', 'name' => 'head_name', 'title' => 'head_name'],
            ['data' => 'ledger_comment', 'name' => 'ledger_comment', 'title' => 'ledger_comment'],
            ['data' => 'debit', 'name' => 'debit', 'title' => 'debit', 'class' => 'text-end'],
            ['data' => 'credit', 'name' => 'credit', 'title' => 'credit', 'class' => 'text-end'],
            ['data' => 'balance', 'name' => 'balance', 'title' => 'balance', 'class' => 'text-end'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'CashBook_' . date('YmdHis');
    }
}
