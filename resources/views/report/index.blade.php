@extends('layout')
@section('content')
    <search message="{{ $company ?? 'No company matched. Search again' }}" alone />
@endsection