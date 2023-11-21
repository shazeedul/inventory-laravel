<x-app-layout>
    <form action="{{ route('admin.invoice.approve', $invoice->id) }}" method="post">
        @csrf
        <input type="hidden" id="invoice_id" value="{{ $invoice->id }}">
        <x-card>
            <div class="row">
                <div class="col-md-4">
                    <div class="md-3">
                        <label for="example-text-input" class="form-label">@localize('Invoice Date')</label>
                        <input class="form-control example-date-input" type="date" id="date" @readonly(true)
                            value="{{ $invoice->invoice_date }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="md-3">
                        <label for="example-text-input" class="form-label">@localize('Customer')</label>
                        <input type="text" class="form-control" id="customer_id" @readonly(true)
                            value="{{ $invoice->customer->name }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">@localize('Product')</th>
                                    <th class="text-center">@localize('Stock')</th>
                                    <th class="text-center">@localize('Quantity')</th>
                                    <th class="text-center">@localize('Price')</th>
                                    <th class="text-center">@localize('Total')</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItem">
                                @foreach ($invoice->invoiceDetails as $item)
                                    <tr>
                                        <td style="width: 15%">
                                            <p class="text-center">{{ $item->product->name }}</p>
                                        </td>
                                        <td
                                            style="{{ $item->quantity < $item->product->quantity ? 'background-color: green' : 'background-color: red' }}">
                                            <p class="text-center">
                                                {{ $item->product->quantity ?? 0 }}</p>
                                        </td>
                                        <td>
                                            <p class="text-center">{{ $item->quantity }}</p>
                                        </td>
                                        <td>
                                            <p class="text-center">{{ $item->unit_price }}</p>
                                        </td>
                                        <td>
                                            <p class="text-center">{{ $item->price }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="footer m-3">
                <ul class="nav align-items-end justify-content-end text-end">
                    <li class="nav-item pe-2">
                        <b>@localize('Total Amount')</b>
                        <input type="number" id="grandTotal" class="text-end form-control grandTotal"
                            name="total_price" value="{{ $invoice->invoiceDetails->sum('price') }}" readonly />
                    </li>
                    <li class="nav-item pe-2">
                        <button class="btn btn-secondary" id="approve" type="button">@localize('Submit')</button>
                    </li>
                </ul>
            </div>
        </x-card>
    </form>
    @push('js')
        <script src="{{ module_asset('Invoice/js/approve.min.js') }}"></script>
    @endpush
</x-app-layout>
