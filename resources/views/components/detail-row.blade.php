@props(['label'])
<div class="row py-2 border-bottom">
    <div class="col-md-3 text-muted">{{ $label }}</div>
    <div class="col-md-9">{{ $slot->isEmpty() ? '-' : $slot }}</div>
</div>
