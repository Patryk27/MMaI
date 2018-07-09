@if(Session::has('messages'))
    <div class="alerts">
        @foreach(Session::get('messages') as $message)
            <div class="alert alert-{{ $message['type'] }}">
                {!! $message['content'] !!}
            </div>
        @endforeach
    </div>
@endif