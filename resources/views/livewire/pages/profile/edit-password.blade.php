<?php

use Livewire\Volt\Component;

new class extends Component {
    public $current_password;
    public $new_password;
    public $confirm_password;

    public function rules()
    {
        return [
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Password saat ini harus diisi.',
            'new_password.required' => 'Password baru harus diisi.',
            'new_password.min' => 'Password baru harus memiliki minimal 8 karakter.',
            'confirm_password.required' => 'Konfirmasi password harus diisi.',
            'confirm_password.same' => 'Konfirmasi password harus sama dengan password baru.',
        ];
    }

    public function save()
    {
        $this->validate();
        $user = auth()->user();

        if (!Hash::check($this->current_password, $user->password)) {
            session()->flash('error', 'Password saat ini salah.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        session()->flash('success', 'Password berhasil diubah.');
    }
}; ?>
<div class="tab-pane fade show active" id="pills-change-passwork" role="tabpanel"
    aria-labelledby="pills-change-passwork-tab" tabindex="0">
    <form wire:submit.prevent="save">
        @if (session()->has('success'))
            <div class="alert alert-success mt-4">{{ session('success') }}</div>
        @elseif (session()->has('error'))
            <div class="alert alert-danger mt-4">{{ session('error') }}</div>
        @endif
        <div class="mb-20">
            <label for="current-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Password Saat
                Ini
                <span class="text-danger-600">*</span></label>
            <div class="position-relative">
                <input type="password" class="form-control radius-8" id="current-password"
                    placeholder="Masukkan Password Saat Ini" wire:model.defer="current_password">
                @error('current_password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="mb-20">
            <label for="new-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Password Baru <span
                    class="text-danger-600">*</span></label>
            <div class="position-relative">
                <input type="password" class="form-control radius-8" id="new-password"
                    placeholder="Masukkan Password Baru" wire:model.defer="new_password">
                @error('new_password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="mb-20">
            <label for="confirm-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Konfirmasi
                Password
                <span class="text-danger-600">*</span></label>
            <div class="position-relative">
                <input type="password" class="form-control radius-8" id="confirm-password"
                    placeholder="Masukkan Konfirmasi Password*" wire:model.defer="confirm_password">
                @error('confirm_password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-start gap-3">
            <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                Save
            </button>
        </div>
    </form>
</div>
