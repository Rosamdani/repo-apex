@extends('layouts.layout')
@php
    $title = $userTryout->tryout->nama;
    $subTitle = 'Pembahasan';
@endphp

@section('content')
    <livewire:pages.pembahasan.index :tryoutId="$userTryout->tryout_id" />
    <div x-data="{ showModal: false }" @open-modal.window="showModal = true" @close-modal.window="showModal = false">
        <!-- Overlay -->
        <div class="modal fade" tabindex="-1" role="dialog" x-bind:class="{ 'show d-block': showModal }" x-show="showModal"
            x-cloak @keydown.escape.window="showModal = false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title">Download Pembahasan</h5>
                        <button type="button" class="btn-close" aria-label="Close" x-on:click="showModal = false"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <p>File pembahasan anda dipersiapkan untuk di download...</p>

                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" x-on:click="showModal = false">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overlay backdrop -->
        <div x-show="showModal" x-cloak class="modal-backdrop fade show" style="z-index: 1050;"></div>
    </div>
@endsection
