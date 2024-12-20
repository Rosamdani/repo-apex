@extends('layouts.index')
@section('title', 'Perangkingan')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h2>{{ $userTryout->tryout->nama }}</h2>
    </div>
    <div class="col-12">
        <p>
            {{ \Carbon\Carbon::now()->locale('id_ID')->translatedFormat('l, j F Y') }}
        </p>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0">Jumlah Peserta: {{$totalUser}}</h6>
            </div>
            <div class="card-body" style="overflow-x: auto;">
                @php
                $alreadyRank = false;
                @endphp
                <table class="w-100">
                    <tbody>
                        @foreach ($allUserRank as $rank)
                        @php
                        if ($rank->user_id == Auth::user()->id){
                        $alreadyRank = true;
                        }
                        @endphp
                        <tr>
                            <td
                                class="align-middle p-2 {{ $rank->user_id == Auth::user()->id ? 'alternative-background' : ''}}">
                                {{ $rank->rank }}</td>
                            <td
                                class="align-middle p-2 {{ $rank->user_id == Auth::user()->id ? 'alternative-background' : ''}}">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $rank->user->avatar ?? 'https://ui-avatars.com/api/?name='.substr($rank->user->name, 0, 1).'&color=FFFFFF&background=09090b' }}"
                                        alt="" class="rounded-circle me-2" style="width: 50px; height: 50px;">
                                    <div>
                                        <p class="mb-0">{{ $rank->user->name }}</p>
                                        <p class="mb-0 text-muted">{{ $rank->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td
                                class="align-middle p-2 {{ $rank->user_id == Auth::user()->id ? 'alternative-background' : ''}}">
                                <p class="text-primary">{{ $rank->nilai}}</p>
                            </td>
                            <td
                                class="align-middle p-2 {{ $rank->user_id == Auth::user()->id ? 'alternative-background' : ''}}">
                                <p class="primary-text">{{ strtoupper($rank->status_lulus) }}</p>
                            </td>
                            @if($rank->rank == 1 && $rank->status_lulus == 'Lulus')
                            <td
                                class="align-middle p-2 {{ $rank->user_id == Auth::user()->id ? 'alternative-background' : ''}}">
                                <h3 class="primary-text">🏆</h3>
                            </td>
                            @elseif ($rank->rank == 2 && $rank->status_lulus == 'Lulus')
                            <td
                                class="align-middle p-2 {{ $rank->user_id == Auth::user()->id ? 'alternative-background' : ''}}">
                                <h3 class="primary-text">🥈</h3>
                            </td>
                            @elseif ($rank->rank == 3 && $rank->status_lulus == 'Lulus')
                            <td
                                class="align-middle p-2 {{ $rank->user_id == Auth::user()->id ? 'alternative-background' : ''}}">
                                <h3 class="primary-text">🥉</h3>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        @if (!$alreadyRank)
                        <tr class="table-active">
                            <td class="align-middle alternative-background p-2">{{ $userTryoutRank }}</td>
                            <td class="align-middle alternative-background p-2">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $userTryout->user->avatar ?? 'https://ui-avatars.com/api/?name='.substr($userTryout->user->name, 0, 1).'&color=FFFFFF&background=09090b' }}"
                                        alt="" class="rounded-circle me-2" style="width: 50px; height: 50px;">
                                    <div>
                                        <p class="mb-0">{{ $userTryout->user->name }}</p>
                                        <p class="mb-0 text-muted">{{ $userTryout->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle alternative-background p-2 primary-text">{{
                                strtoupper($status_lulus) }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection