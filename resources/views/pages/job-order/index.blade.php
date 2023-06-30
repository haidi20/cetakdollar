@extends('layouts.master')

@section('content')
    <div id="root" class="full-height">
        <job-order base-url="{{ $baseUrl }}" user="{{ $user }}" statuses="{{ $statuses }}" />
    </div>
@endsection

@section('style')
    <style>
        #root {
            /* background-color: white; */
        }
    </style>
@endsection

@section('script')
    {{--  --}}
@endsection
