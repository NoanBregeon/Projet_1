@if($message)
<div class="alert alert-{{ $type ?? 'success' }} alert-dismissible fade show" role="alert">
    @if($icon)
        <i class="bi {{ $icon }} me-2"></i>
    @endif
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
