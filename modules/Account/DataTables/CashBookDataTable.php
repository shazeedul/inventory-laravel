<?php

namespace Modules\Account\DataTables;

use Carbon\Carbon;
use App\Traits\Account\Report;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Services\DataTable;
use Modules\Account\Entities\ChartOfAccount;

class CashBookDataTable extends DataTable
{
    use Report;

    public function dataTable(Request $request): DataTableAbstract
    {
        $chart_of_account_id = ChartOfAccount::where('head_level', 4)->where('is_active', 1)->where('is_cash_nature', 1)->first()->id ?? '';

        $fromDate = Carbon::now()->subDay(30)->format('Y-m-d');
        $toDate = Carbon::now()->format('Y-m-d');

        $request = new \Illuminate\Http\Request();
        $request['from_date'] = $fromDate;
        $request['to_date'] = $toDate;
        $request['chart_of_account_id'] = $chart_of_account_id;

        $getBalanceOpening = $this->getOpeningBalance($request);
        $getTransactionList = $this->getTransactionDetail($request, $getBalanceOpening);

        $tableFooter = [
            'totalDebit' => $getTransactionList->pluck('debit')->sum(),
            'totalCredit' => $getTransactionList->pluck('credit')->sum()
        ];

        $acc_type = Cache::remember($request->chart_of_account_id, 3600, function () use ($request) {
            return ChartOfAccount::findOrFail($request->chart_of_account_id);
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
                'voucher_no' => $data->voucherType->short_name . '-' . $data->voucher_no,
                'account_name' => $data->reverseCode ? $data->reverseCode->name : '---',
                'head_name' => $data->chartOfAccount->name,
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
            ->setTableId('cashbook-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->footerCallback(
                'function ( row, data, start, end, display ) {
                    var api = this.api(), data;
                    var intVal = function ( i ) {
                        return typeof i === "string" ?
                            i.replace(/[\$,]/g, "")*1 :
                            typeof i === "number" ?
                                i : 0;
                    };
                    totalDebit = api.column(6).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    totalCredit = api.column(7).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    totalBalance = api.column(8).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    $( api.column(5).footer() ).html("Total");
                    $( api.column(6).footer() ).html(totalDebit.toFixed(2));
                    $( api.column(7).footer() ).html(totalCredit.toFixed(2));
                    $( api.column(8).footer() ).html(totalBalance.toFixed(2));
                }'
            )
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
            Column::make('voucher_date')->title('Date')->orderable(true)->searchable(true),
            Column::make('voucher_no')->title('Voucher No')->orderable(false)->searchable(true),
            Column::make('account_name')->title('Account Name')->orderable(false)->searchable(true),
            Column::make('head_name')->title('Head Name')->orderable(false)->searchable(true),
            Column::make('ledger_comment')->title('Narration')->orderable(false)->searchable(true),
            Column::make('debit')->title('Debit')->orderable(false)->searchable(false),
            Column::make('credit')->title('Credit')->orderable(false)->searchable(false),
            Column::make('balance')->title('Balance')->orderable(false)->searchable(false),
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
