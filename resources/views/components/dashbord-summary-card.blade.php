@props(['items' => []])


@php
    $colors = ['#674F1F', '#0C7E2E', '#4C0D65', '#101F84', '#7D073F', '#830808', '#095682', '#2E8B57', '#8B008B', '#FF1493', '#FF8C00', '#00CED1'];
    $index = 0;
@endphp
@foreach ($items as $section => $count)
    @php
        if ($index == count($colors)) {
            $index = 0;
        }
        $color = $colors[$index];
        $index++;
    @endphp
    <div class="col-md-2 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-white " style="background: {{ $color }}">
                <div class="d-flex justify-content-between align-items-baseline">
                    <h6 class="card-title mb-0">{{ $section }}</h6>
                </div>
                <div class="row">
                    <div class="col-6 col-md-12 col-xl-5">
                        <h3 class="mb-2">{{ $count }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
