<?php

use Livewire\Volt\Component;

new class extends Component {
    public $universitas;
    public $tahun_masuk;
    public $status_pendidikan;
    public $semester;

    public function mount()
    {
        $this->universitas = auth()->user()->userAcademy?->universitas;
        $this->tahun_masuk = auth()->user()->userAcademy?->tahun_masuk;
        $this->status_pendidikan = auth()->user()->userAcademy?->status_pendidikan;
        $this->semester = auth()->user()->userAcademy?->semester;
    }

    public function rules()
    {
        return [
            'universitas' => 'required|string|max:255',
            'tahun_masuk' => 'required|integer|min:1900|max:2800',
            'status_pendidikan' => 'required|string|in:koas,pre-klinik',
            'semester' => 'nullable|required_if:status_pendidikan,pre-klinik|integer|min:1|max:20',
        ];
    }

    public function messages()
    {
        return [
            'universitas.required' => 'Nama universitas harus diisi.',
            'tahun_masuk.required' => 'Tahun masuk harus diisi.',
            'tahun_masuk.integer' => 'Tahun masuk harus berupa angka.',
            'tahun_masuk.min' => 'Tahun masuk minimal 1900.',
            'tahun_masuk.max' => 'Tahun masuk maksimal 2800.',
            'status_pendidikan.required' => 'Status pendidikan harus diisi.',
            'semester.integer' => 'Semester harus berupa angka.',
            'semester.min' => 'Semester minimal 1.',
            'semester.max' => 'Semester maksimal 20.',
            'semester.required_if' => 'Semester harus diisi.',
        ];
    }

    public function save()
    {
        $this->validate();
        $userAcademy = auth()->user()->userAcademy;
        if ($userAcademy) {
            $userAcademy->update([
                'universitas' => $this->universitas,
                'tahun_masuk' => $this->tahun_masuk,
                'status_pendidikan' => $this->status_pendidikan,
                'semester' => $this->semester,
            ]);
        } else {
            auth()
                ->user()
                ->userAcademy()
                ->create([
                    'universitas' => $this->universitas,
                    'tahun_masuk' => $this->tahun_masuk,
                    'status_pendidikan' => $this->status_pendidikan,
                    'semester' => $this->semester,
                ]);
        }
        $this->js('window.location.reload()');
    }
}; ?>

<div class="tab-pane fade" id="pills-notification" role="tabpanel" aria-labelledby="pills-notification-tab" tabindex="0">
    <form wire:submit.prevent='save'>
        <div class="row">
            @if (session()->has('success'))
                <div class="col-sm-12 alert alert-success mb-24">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="col-sm-12 alert alert-danger mb-24">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-sm-12">
                <div class="mb-20">
                    <label for="universitas" class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                        Lengkap
                        <span class="text-danger-600">*</span></label>
                    <input name="university" type="text"
                        class="form-control radius-8 {{ $errors->has('universitas') ? 'is-invalid' : '' }}"
                        wire:model='universitas' id="universitas" placeholder="Masukkan nama universitas">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="mb-20">
                    <label for="tahun_masuk" class="form-label fw-semibold text-primary-light text-sm mb-8">Tahun Masuk
                        <span class="text-danger-600">*</span></label>
                    <input type="number" min="1800" max="{{ date('Y') }}"
                        class="form-control radius-8 {{ $errors->has('tahun_masuk') ? 'is-invalid' : '' }}"
                        wire:model='tahun_masuk' id="tahun_masuk" placeholder="Masukkan alamat email">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="mb-20">
                    <label for="status_pendidikan" class="form-label fw-semibold text-primary-light text-sm mb-8">
                        Status Pendidikan
                        <span class="text-danger-600">*</span>
                    </label>
                    <!-- Tambahkan x-data untuk mengelola statusPendidikan -->
                    <select x-data="{ statusPendidikan: @entangle('status_pendidikan') }"
                        class="form-select radius-8 {{ $errors->has('status_pendidikan') ? 'is-invalid' : '' }}"
                        wire:model='status_pendidikan' id="status_pendidikan"
                        x-on:change="statusPendidikan = $event.target.value">
                        <option value="">Pilih Status Pendidikan</option>
                        <option value="koas">Koas</option>
                        <option value="pre-klinik">Preklinik</option>
                    </select>
                    @error('status_pendidikan')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Bagian untuk input semester -->
            <div class="col-sm-12 semester" x-data="{ statusPendidikan: @entangle('status_pendidikan') }" x-show="statusPendidikan === 'pre-klinik'">
                <div class="mb-20">
                    <label for="semester" class="form-label fw-semibold text-primary-light text-sm mb-8">
                        Semester
                        <span class="text-danger-600">*</span>
                    </label>
                    <input type="number" min="1" max="20"
                        class="form-control radius-8 {{ $errors->has('semester') ? 'is-invalid' : '' }}"
                        wire:model='semester' id="semester" placeholder="Masukkan semester">
                    @error('semester')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-start gap-3">

            <button type="submit" class="btn btn-primary-600 border border-primary-600 text-md px-56 py-12 radius-8">
                <span wire:loading wire:target="save">Menyimpan...</span>
                <span wire:loading.remove wire:target="save">Simpan</span>
            </button>
        </div>
    </form>
</div>
@push('script')
    <script>
        $(document).on('change', '#status_pendidikan', function() {
            if ($(this).val() == 'pre-klinik') {
                $('.semester').show();
            } else {
                $('.semester').hide();
            }
        })

        $('#status_pendidikan').trigger('change');
    </script>
@endpush
