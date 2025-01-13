<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validation;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;
    public $image_url;
    public $name;
    public $username;
    public $no_telp;

    public function mount()
    {
        $this->image_url = auth()->user()->image_url;
        $this->name = auth()->user()->name;
        $this->username = auth()->user()->username;
        $this->no_telp = auth()->user()->no_telp;
    }

    public function rules()
    {
        return [
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
            'no_telp' => 'required|string|max:15',
        ];
    }

    public function messages()
    {
        return [
            'image_url.image' => 'Foto profil harus berupa gambar.',
            'image_url.mimes' => 'Format foto profil harus jpeg, png, jpg, gif, atau svg.',
            'image_url.max' => 'Ukuran foto profil maksimal 2MB.',
            'name.required' => 'Nama lengkap harus diisi.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan oleh pengguna lain.',
            'no_telp.required' => 'Nomor telepon harus diisi.',
        ];
    }

    public function save()
    {
        $this->validate();

        $imagePath = auth()->user()->image_url;
        if ($this->image_url !== '' && $this->image_url != null) {
            $imagePath = $this->image_url->store('profile', 'public'); // Path relatif
        }

        // Update data pengguna
        auth()
            ->user()
            ->update([
                'image_url' => $imagePath, // Simpan path relatif saja
                'name' => $this->name,
                'username' => $this->username,
                'no_telp' => $this->no_telp,
            ]);

        // Memberikan notifikasi
        session()->flash('success', 'Profil berhasil diperbarui.');
    }
}; ?>

<div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel" aria-labelledby="pills-edit-profile-tab"
    tabindex="0">
    <h6 class="text-md text-primary-light mb-16">Foto Profil</h6>

    <form wire:submit.prevent="save">
        @if ($errors->any())
            <div class="alert alert-danger mb-24">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success mb-24">
                {{ session('success') }}
            </div>
        @endif
        <!-- Upload Image Start -->
        <div class="mb-24 mt-16">
            <div class="avatar-upload">
                <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                    <input type='file' id="imageUpload" wire:model="image_url" accept=".png, .jpg, .jpeg" hidden>
                    <label for="imageUpload"
                        class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                        <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                    </label>
                </div>
                <div class="avatar-preview">
                    <div id="imagePreview">
                    </div>
                </div>
            </div>
        </div>
        <!-- Upload Image End -->
        <div class="row">
            <div class="col-sm-12">
                <div class="mb-20">
                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Lengkap
                        <span class="text-danger-600">*</span></label>
                    <input type="text" class="form-control radius-8" wire:model='name' id="name"
                        placeholder="Masukkan nama lengkap">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="mb-20">
                    <label for="username" class="form-label fw-semibold text-primary-light text-sm mb-8">Username <span
                            class="text-danger-600">*</span></label>
                    <input type="text" wire:model='username' class="form-control radius-8" wire " id="username" placeholder="Masukkan username">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="mb-20">
                    <label for="number" class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor
                        Telepon</label>
                    <input type="tel" class="form-control radius-8" id="number" wire:model='no_telp'
                        placeholder="Masukkan nomor telepon">
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-start gap-3">

            <button type="submit" class="btn btn-primary-600 border border-primary-600 text-md px-56 py-12 radius-8">
                Save
            </button>
        </div>
    </form>
</div>
@push('script')
    <script>
        // ======================== Upload Image Start =====================
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#imagePreview").css("background-image", "url(" + e.target.result + ")");
                    $("#imagePreview").hide();
                    $("#imagePreview").fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload").change(function() {
            readURL(this);
        });
        // ======================== Upload Image End =====================
    </script>
@endpush
