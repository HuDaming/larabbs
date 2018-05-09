@foreach (['success', 'message', 'danger', 'info'] as $msg)
    @if (session()->has($msg))
        <div class="alert alert-{{ $msg }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            {{ session($msg) }}
        </div>
    @endif
@endforeach