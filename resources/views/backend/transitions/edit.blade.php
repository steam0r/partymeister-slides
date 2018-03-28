@extends('motor-backend::layouts.backend')

@section('htmlheader_title')
    {{ trans('motor-backend::backend/global.home') }}
@endsection

@section('contentheader_title')
    {{ trans('partymeister-slides::backend/transitions.edit') }}
    {!! link_to_route('backend.transitions.index', trans('motor-backend::backend/global.back'), [], ['class' => 'pull-right float-right btn btn-sm btn-danger']) !!}
@endsection

@section('main-content')
	@include('motor-backend::errors.list')
	@include('partymeister-slides::backend.transitions.form')
@endsection