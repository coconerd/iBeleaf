<div class="alert alert-{{ $type ?? 'info' }}">
    {{ $slot }}
    @if(isset($footer))
        <div class="alert-footer">
            {{ $footer }}
        </div>
    @endif
</div> 