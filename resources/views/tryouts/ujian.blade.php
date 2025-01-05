@extends('layouts.tryout-layout')
@php
    $title = 'Ujian';
@endphp

@section('content')
    <livewire:pages.tryout.questions :tryoutId="$tryout->id" />
@endsection

@push('script')
    <div class="modal fade" id="informasiModal" tabindex="-1" role="dialog" aria-labelledby="informasiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 75vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="informasiModalLabel">Informasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-style">
                        <li>
                            Selama ujian, Anda dapat menggunakan tombol keyboard <code>&lt;</code> dan <code>&gt;</code>
                            untuk
                            menampilkan soal sebelumnya atau berikutnya.
                        </li>
                        <li>
                            Tombol <code>Jeda</code> digunakan untuk menjeda ujian sementara waktu. Waktu yang tersisa akan
                            disimpan dan Anda dapat melanjutkan ujian kembali.
                        </li>
                        <li>
                            Tombol <code>Selesai</code> digunakan untuk menyelesaikan ujian. Pastikan Anda telah menjawab
                            semua
                            soal sebelum menekan tombol ini.
                        </li>
                        <li>
                            Perlu diingat bahwa apabila waktu ujian habis maka ujian Anda akan dianggap selesai secara
                            otomatis.
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var informasiModal = new bootstrap.Modal(document.getElementById('informasiModal'));
            informasiModal.show();
        });
    </script>
@endpush
