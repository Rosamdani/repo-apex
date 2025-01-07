@extends('layouts.layout')
@php
    $title = $tryout->nama;
    $subTitle = 'Detail Tryout';
@endphp

@section('content')
    <livewire:pages.katalog.detail :tryoutId="$id" />
@endsection
