<div class="col-6 col-lg-3">
  <div class="card border-0 shadow-sm">
    <div class="card-body p-3">
      <div class="d-flex align-items-center mb-3">
        <div class="me-3 p-2 rounded-3 bg-{{$color}} bg-opacity-10 text-{{$color}}"
          style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
          <i class="{{$icon}}"></i>
        </div>
        <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">{{$title}}
        </div>
      </div>
      <div class="d-flex align-items-baseline justify-content-between">
        <h3 class="mb-0 fw-bold">{{$value}}</h3>
        @if(isset($delta) && $delta)
        <div class="text-{{ str_contains($delta, '-') ? 'danger' : 'success' }} small fw-bold">
          <i class="fas fa-{{ str_contains($delta, '-') ? 'arrow-down' : 'arrow-up' }}"></i> {{$delta}}
        </div>
        @endif
      </div>
    </div>
  </div>
</div>