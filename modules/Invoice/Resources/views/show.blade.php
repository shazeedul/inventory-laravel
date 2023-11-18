<div class="" style="padding: 50px 0">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>{{ ___('Invoice Details') }}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>{{ ___('Invoice Items') }}</h4>
                    <div>
                        <h4 class="d-inline">{{ ___('Customer Name') }}:</h4>
                        <p class="d-inline"><strong>{{ $invoice?->customer?->name }}</strong></p>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ ___('Product') }}</th>
                            <th>{{ ___('Quantity') }}</th>
                            <th>{{ ___('Unit Price') }}</th>
                            <th>{{ ___('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->invoiceDetails as $detail)
                            <tr>
                                <td>{{ $detail?->product?->name }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->unit_price }}</td>
                                <td>{{ $detail->price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="row gutters-sm d-flex justify-content-between mt-2">
                    <div class="col-md-6">
                        <strong>{{ ___('Invoice Date') }}:</strong> {{ $invoice->invoice_date }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@localize('Close')</button>
</div>
