@extends('layout')
@section('content')
<report code="{{ $company->code }}" :report="{{ $company->report }}"></report> 
@endsection