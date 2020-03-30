@extends('sso::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        sso.index: {!! config('sso.name') !!}
    </p>
@endsection
