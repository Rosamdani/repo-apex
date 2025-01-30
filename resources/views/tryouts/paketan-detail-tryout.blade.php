@extends('layouts.layout')
@php
    $title = $paket->paket;
    $subTitle = 'Paket Tryout';
@endphp

@section('content')
    <livewire:pages.katalog.paketan :paketId="$id" />
@endsection
