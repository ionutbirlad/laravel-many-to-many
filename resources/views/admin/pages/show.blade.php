@extends('layouts.app')

@section('content')
  <h1>{{$page->title}}</h1>
  <div>
    {{$page->body}}
  </div>
  <hr>
  <small>Creata il {{$page->creatted_at}}</small>
@endsection
