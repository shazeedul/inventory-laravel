<x-app-layout>
    <form action="{{ route('admin.invoice.create') }}" method="post">
        @csrf
        <x-card>
            <div class="row">
                <div class="col-md-4">
                    <div class="md-3">
                        <label for="example-text-input" class="form-label">@localize('Invoice Date')</label>
                        <input class="form-control example-date-input" name="date" type="date" id="date"
                            value="{{ Carbon::now()->format('Y-m-d') ?? old('date') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="md-3">
                        <label for="example-text-input" class="form-label">@localize('Customer')</label>
                        <select name="customer_id" id="customer_id" class="form-control">
                            <option value="">@localize('Select Customer')</option>
                            @foreach ($customers as $key => $c)
                                <option value="{{ $c->id }}" @selected(old('customer_id') == $c->id)>{{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </x-card>
        <div class="row">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">@localize('Product')</th>
                                <th class="text-center">@localize('Category')</th>
                                <th class="text-center">@localize('Unit')</th>
                                <th class="text-center">@localize('Stock')</th>
                                <th class="text-center">@localize('Quantity')</th>
                                <th class="text-center">@localize('Unit Price')</th>
                                <th class="text-center">@localize('Total')</th>
                                <th class="text-center">@localize('Action')</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItem">
                            <input type="hidden" id="rowCount" value="1">
                            <tr>
                                <td style="width: 25%">
                                    <select name="product_id[]" id="product_id_1" class="form-control product_id"
                                        onclick="get_product()" required>
                                        <option value="">@localize('Select Product')</option>
                                        @foreach ($products as $key => $p)
                                            <option value="{{ $p->id }}">{{ $p->name }} --
                                                {{ $p->category->name }}({{ $p->unit->name }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-number-input" id="category_1" readonly />
                                </td>
                                <td>
                                    <input type="number" class="form-control form-number-input" id="unit_1" readonly />
                                </td>
                                <td>
                                    <input type="number" class="form-control form-number-input" id="stock_1" readonly />
                                </td>
                                <td>
                                    <input type="number" name="quantity[]" id="quantity_1"
                                        class="form-control form-number-input" onchange="calculateTotalPrice(1)"
                                        onkeyup="calculateTotalPrice(1)" value="0.00">
                                </td>
                                <td>
                                    <input type="number" name="unit_price[]" id="unit_price_1"
                                        class="form-control form-number-input" onchange="calculateTotalPrice(1)"
                                        onkeyup="calculateTotalPrice(1)" value="0.00">
                                </td>
                                <td>
                                    <input type="number" name="total[]" id="total_1" class="form-control"
                                        value="0.00" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm removeRow"><i
                                            class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7"></td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" id="addRow"><i
                                            class="fa fa-plus"></i></button>
                                </td>
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
                    <input type="number" id="grandTotal" class="text-end form-control grandTotal" name="total_price"
                        value="0.00" readonly />
                </li>
                <li class="nav-item pe-2">
                    <button class="btn btn-secondary" type="submit">@localize('Submit')</button>
                </li>
            </ul>
        </div>
    </form>
    @push('lib-styles')
        <link rel="stylesheet" href="{{ nanopkg_asset('vendor/select2/select2.min.css') }}" type="text/css" />
    @endpush
    @push('lib-scripts')
        <script src="{{ nanopkg_asset('vendor/select2/select2.min.js') }}"></script>
    @endpush
    @push('js')
        <script src="{{ module_asset('Invoice/js/app.min.js') }}"></script>
        <script>
            var products = @json($products);
        </script>
    @endpush
</x-app-layout>
