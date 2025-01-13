<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="tab-pane fade" id="pills-notification" role="tabpanel" aria-labelledby="pills-notification-tab" tabindex="0">
    <form action="#">
        <div class="row">
            <div class="col-sm-12">
                <div class="mb-20">
                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                        Lengkap
                        <span class="text-danger-600">*</span></label>
                    <input name="university" type="text" class="form-control radius-8"
                        value="{{ auth()->user()->name }}" id="university" placeholder="Masukkan nama universitas">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="mb-20">
                    <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email
                        <span class="text-danger-600">*</span></label>
                    <input type="email" class="form-control radius-8" value="{{ auth()->user()->email }}"
                        id="email" placeholder="Masukkan alamat email">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="mb-20">
                    <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Phone
                        <span class="text-danger-600">*</span></label>
                    <input type="email" class="form-control radius-8" id="email"
                        placeholder="Masukkan nomor telepon">
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-start gap-3">

            <button type="button" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                Save
            </button>
        </div>
    </form>
</div>
