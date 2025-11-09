@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
    </h2>

    <div class="py-12">
        <div class="bg-white p-6 shadow-sm rounded-lg">
            {{ __("You're logged in!") }}
        </div>
    </div>
</div>
@endsection
