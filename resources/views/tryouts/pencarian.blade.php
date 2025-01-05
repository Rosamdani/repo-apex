@extends('layouts.layout')

@section('content')
    <livewire:pages.ranking.index :keyword="$id" />
@endsection
