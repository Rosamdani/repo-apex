@extends('layouts.layout')
@php
    $title = 'Ranking';
    $subTitle = 'Ranking';
@endphp

@section('content')
    <livewire:pages.ranking.index :tryoutId="$id" />
@endsection
