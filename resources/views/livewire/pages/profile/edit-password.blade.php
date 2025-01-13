<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="tab-pane fade" id="pills-change-passwork" role="tabpanel" aria-labelledby="pills-change-passwork-tab"
    tabindex="0">
    <div class="mb-20">
        <label for="your-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Password Saat Ini
            <span class="text-danger-600">*</span></label>
        <div class="position-relative">
            <input type="password" class="form-control radius-8" id="your-password"
                placeholder="Masukkan Password Saat Ini">
            <span
                class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                data-toggle="#your-password"></span>
        </div>
    </div>
    <div class="mb-20">
        <label for="your-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Password Baru <span
                class="text-danger-600">*</span></label>
        <div class="position-relative">
            <input type="password" class="form-control radius-8" id="your-password"
                placeholder="Masukkan Password Baru">
            <span
                class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                data-toggle="#your-password"></span>
        </div>
    </div>
    <div class="mb-20">
        <label for="confirm-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Konfirmasi Password
            <span class="text-danger-600">*</span></label>
        <div class="position-relative">
            <input type="password" class="form-control radius-8" id="confirm-password"
                placeholder="Masukkan Konfirmasi Password*">
            <span
                class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                data-toggle="#confirm-password"></span>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-start gap-3">
        <button type="button" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
            Save
        </button>
    </div>
</div>
