
@if(Session::has('message'))
    <div class="container">
        <div class="alert alert-success alert-dismissable">
            {{Session::get('message')}}
        </div>
    </div>
@endif

@if(Session::has('message_warning'))
    <div class="container">
        <div class="alert alert-warning">
            {{Session::get('message_warning')}}
        </div>
    </div>
@endif