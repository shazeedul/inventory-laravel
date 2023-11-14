<div class="" style="padding: 50px 0">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>{{ ___('Purchase Details') }}</h3>
            </div>
            <div class="card-body">
                <h4>{{ ___('Purchase Items') }}</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ ___('Product') }}</th>
                            <th>{{ ___('Quantity') }}</th>
                            <th>{{ ___('Unit Price') }}</th>
                            <th>{{ ___('Description') }}</th>
                            <th>{{ ___('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase->purchaseDetails as $detail)
                            <tr>
                                <td>{{ $detail->product->name }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->unit_price }}</td>
                                <td>{{ Str::limit($detail->description, 10) }}</td>
                                <td>{{ $detail->price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="row gutters-sm d-flex justify-content-between mt-2">
                    <div class="col-md-6">
                        <strong>{{ ___('Purchase Date') }}:</strong> {{ $purchase->purchase_date }}
                    </div>
                    <div class="col-md-6">
                        <strong>{{ ___('Total Price') }}:</strong> {{ $purchase->total_price }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@localize('Close')</button>
</div>
