@extends('panel.layout.app')
@section('title', 'Search')

@section('content')
<iframe id="webViewer" height="1000" src="{{$defix->external_link}}"></iframe>

@endsection