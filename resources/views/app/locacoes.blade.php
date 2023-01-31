@extends('layouts.app')

@section('content')
<locacoes-component :clientes="{{ $clientes }}" :carros="{{ $carros }}"></locacoes-component>
@endsection