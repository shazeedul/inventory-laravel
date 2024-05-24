<div id="jstree">
    <ul>
        <li data-jstree='{ "opened" : true }'>{{ localize('COA') }}
            <ul>
                @forelse ($accMainHead as $acHeadKye => $accHeadValue)
                    <li data-jstree='{ "selected" : false }' data-id="{{ $accHeadValue->id }}">
                        {{ $accHeadValue->name }} - {{ $accHeadValue->code }}
                        @foreach ($accSecondLabelHead as $acSecondHeadKye => $accSecondHeadValue)
                            @if ($accSecondHeadValue->parent_id == $accHeadValue->id)
                                <ul>
                                    <li data-jstree='{ "selected" : false }' data-id="{{ $accSecondHeadValue->id }}">
                                        {{ $accSecondHeadValue->name }} -
                                        {{ $accSecondHeadValue->code }}
                                        @if ($accHeadWithoutFands->where('parent_id', $accSecondHeadValue->id)->isNotEmpty())
                                            @foreach ($accHeadWithoutFands->where('parent_id', $accSecondHeadValue->id) as $allOtherKey => $accHeadWithoutFansValue)
                                                <ul>
                                                    <li data-jstree='{ "selected" : false }'
                                                        data-id="{{ $accHeadWithoutFansValue->id }}">
                                                        {{ $accHeadWithoutFansValue->name }} -
                                                        {{ $accHeadWithoutFansValue->code }}
                                                        @if ($accHeadWithoutFands->where('parent_id', $accHeadWithoutFansValue->id)->isNotEmpty())
                                                            @foreach ($accHeadWithoutFands->where('parent_id', $accHeadWithoutFansValue->id) as $allFourthKey => $fourthLabelValue)
                                                                <ul>
                                                                    <li data-jstree='{ "selected" : false }'
                                                                        data-id="{{ $fourthLabelValue->id }}">
                                                                        {{ $fourthLabelValue->name }} -
                                                                        {{ $fourthLabelValue->code }}
                                                                    </li>
                                                                </ul>
                                                            @endforeach
                                                        @endif
                                                    </li>
                                                </ul>
                                            @endforeach
                                        @endif
                                    </li>
                                </ul>
                            @endif
                        @endforeach
                    </li>
                @empty
                    <li>@localize('No Data Found')</li>
                @endforelse
            </ul>
        </li>
    </ul>
</div>
