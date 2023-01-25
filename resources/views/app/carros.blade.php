@extends('layouts.app')

@section('content')
<carros-component :modelos="{{ $modelos }}"></carros-component>
@endsection