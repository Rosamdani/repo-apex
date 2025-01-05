@extends('layouts.layout')
@php
    $title = $userTryout->tryout->nama;
    $subTitle = 'Pembahasan';
@endphp

@section('content')
    <livewire:pages.pembahasan.index :tryoutId="$userTryout->tryout_id" />
@endsection
