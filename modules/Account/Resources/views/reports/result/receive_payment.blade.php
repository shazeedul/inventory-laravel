<div class="card-body">
    <div class="table-responsive">
        <table class="table display table-bordered table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th><strong>{{ localize('Particulars') }}</strong></th>
                    <th class="text-end"><strong>{{ localize('Balance') }}</strong></th>
                </tr>
            </thead>
            <tbody>
                {{-- Opening Balance --}}
                <tr>
                    <td>{{ localize('Opening Balance') }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left: 100px"> <span class=""> {{ $cashNatureParent?->name }} </span>
                    </td>
                    <td class="text-end">{{ number_format($cashNatureParent?->totalOpening, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 100px">{{ $bankNatureParent?->name }}</td>
                    <td class="text-end">{{ number_format($bankNatureParent?->totalOpening, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 100px">{{ $advanceLedger?->name }}</td>
                    <td class="text-end">{{ number_format($advanceLedger?->totalOpening, 2, '.', ',') }}</td>
                </tr>

                {{-- Receipt --}}
                <tr>
                    <td>{{ localize('Receipt') }}</td>
                    <td></td>
                </tr>
                @foreach ($receiptThirdLevelDetail as $thirdLevelValue)
                    <tr>
                        <td style="padding-left: 100px">{{ $thirdLevelValue?->name }}</td>
                        <td></td>
                    </tr>
                    @foreach ($receiptFourthLevelFinal->where('parent_id', $thirdLevelValue?->id) as $fourthLabelValue)
                        <tr>
                            <td style="padding-left: 200px">{{ $fourthLabelValue['name'] }}</td>
                            <td class="text-end">{{ number_format($fourthLabelValue['total_amount'], 2, '.', ',') }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td style="padding-left: 100px" class="text-end"><strong>{{ localize('Total Receipt') }}</strong>
                    </td>
                    <td class="text-end">
                        <strong>{{ number_format($receiptFourthLevelFinal->sum('total_amount'), 2, '.', ',') }}</strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-end"><strong>{{ localize('Grand Total') }}</strong></td>
                    <td class="text-end">
                        <strong>{{ number_format((float) $receiptFourthLevelFinal->sum('total_amount') + (float) $cashNatureParent?->totalOpening + (float) $bankNatureParent?->totalOpening + (float) $advanceLedger?->totalOpening, 2, '.', ',') }}</strong>
                    </td>
                </tr>

                {{-- Payment --}}
                <tr>
                    <td>{{ localize('Payment') }}</td>
                    <td></td>
                </tr>
                @foreach ($paymentThirdLevelDetail as $thirdLevelValue)
                    <tr>
                        <td style="padding-left: 100px">{{ $thirdLevelValue?->name }}</td>
                        <td></td>
                    </tr>
                    @foreach ($paymentFourthLevelFinal->where('parent_id', $thirdLevelValue?->id) as $fourthLabelValue)
                        <tr>
                            <td style="padding-left: 200px">{{ $fourthLabelValue['name'] }}</td>
                            <td class="text-end">{{ number_format($fourthLabelValue['total_amount'], 2, '.', ',') }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td style="padding-left: 100px" class="text-end"><strong>{{ localize('Total Payment') }}</strong>
                    </td>
                    <td class="text-end">
                        <strong>{{ number_format($paymentFourthLevelFinal->sum('total_amount'), 2, '.', ',') }}</strong>
                    </td>
                </tr>

                {{-- Closing Balance --}}
                <tr>
                    <td>{{ localize('Closing Balance') }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left: 100px"> <span class=""> {{ $cashNatureParent?->name }} </span>
                    </td>
                    <td class="text-end">{{ number_format($cashNatureParent?->totalClosing, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 100px">{{ $bankNatureParent?->name }}</td>
                    <td class="text-end">{{ number_format($bankNatureParent?->totalClosing, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 100px">{{ $advanceLedger?->name }}</td>
                    <td class="text-end">{{ number_format($advanceLedger?->totalClosing, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td class="text-end"><strong>{{ localize('Grand Total') }}</strong></td>
                    <td class="text-end">
                        <strong>{{ number_format((float) $paymentFourthLevelFinal->sum('total_amount') + (float) $cashNatureParent?->totalClosing + (float) $bankNatureParent?->totalClosing + (float) $advanceLedger?->totalClosing, 2, '.', ',') }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
