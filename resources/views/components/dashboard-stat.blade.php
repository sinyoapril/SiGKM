@props(['item'])
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card h-100">
        <div class="card-body d-flex align-items-center">
            <div class="avatar flex-shrink-0 me-3"><span class="avatar-initial rounded bg-label-{{ $item['color'] }}"><i
                        class="bx {{ $item['icon'] }}"></i></span></div>
            <div><span class="fw-semibold d-block mb-1">{{ $item['label'] }}</span>
                <h3 class="card-title mb-0">{{ $item['value'] }}</h3>
            </div>
        </div>
    </div>
</div>
