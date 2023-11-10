<x-app-layout>
    <x-card>
        <div class="row">
            <div class="col-md-4">
                <div class="md-3">
                    <label for="example-text-input" class="form-label">@localize('Purchase Date')</label>
                    <input class="form-control example-date-input" name="date" type="date" id="date">
                </div>
            </div>
            <div class="col-md-4">
                <div class="md-3">
                    <label for="example-text-input" class="form-label">@localize('Supplier')</label>
                    <select name="supplier_id" id="supplier_id" class="form-control">
                        <option value="">@localize('Select Supplier')</option>
                        @foreach ($suppliers as $key => $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
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
                            <th class="text-center">@localize('Quantity')</th>
                            <th class="text-center">@localize('Unit Price')</th>
                            <th class="text-center">@localize('Description')</th>
                            <th class="text-center">@localize('Total')</th>
                            <th class="text-center">@localize('Action')</th>
                        </tr>
                    </thead>
                    <tbody id="purchaseItem">
                        <input type="hidden" id="rowCount" value="1">
                        <tr>
                            <td>
                                <select name="product_id[]" id="product_id_1" class="form-control product_id">
                                    <option value="">@localize('Select Product')</option>
                                    @foreach ($products as $key => $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="quantity[]" id="quantity_1" class="form-control"
                                    onchange="calculateTotalPrice(1)" onkeyup="calculateTotalPrice(1)">
                            </td>
                            <td>
                                <input type="number" name="unit_price[]" id="unit_price_1" class="form-control"
                                    onchange="calculateTotalPrice(1)" onkeyup="calculateTotalPrice(1)">
                            </td>
                            <td>
                                <input type="text" name="description[]" id="description_1" class="form-control">
                            </td>
                            <td>
                                <input type="number" name="total[]" id="total_1" class="form-control" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm removeRow"><i
                                        class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5"></td>
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
                <input type="number" id="grandTotal" class="text-end form-control grandTotal"
                    name="total_price" value="0.00" readonly />
            </li>
            <li class="nav-item pe-2">
                <button class="btn btn-secondary" type="submit">@localize('Submit')</button>
            </li>
        </ul>
    </div>
    @push('lib-styles')
        <link rel="stylesheet" href="{{ nanopkg_asset('vendor/select2/select2.min.css') }}" type="text/css" />
    @endpush
    @push('lib-scripts')
        <script src="{{ nanopkg_asset('vendor/select2/select2.min.js') }}"></script>
    @endpush
    @push('js')
        <script src="{{ module_asset('Purchase/js/app.min.js') }}"></script>
        <script>
            var products = @json($products);
        </script>
    @endpush
</x-app-layout>
