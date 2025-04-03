@extends('layout.layout')

@section('content')

<div class="flex">
   
    @include('products.allProducts')
    @include('products.create')
</div>
   

    @endsection