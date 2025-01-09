<?php

use Livewire\Volt\Component;
use App\Models\Testimoni;

new class extends Component {
    public $tryoutId;
    public $userTestimonial;

    public $testimoni;

    public $nilai;

    public function mount($tryoutId)
    {
        $this->tryoutId = $tryoutId;
        $this->userTestimonial = Testimoni::where('user_id', auth()->id())->where('tryout_id', $tryoutId)->first();
    }

    public function rules()
    {
        return [
            'testimoni' => 'required|max:100',
            'nilai' => 'required|integer|min:1|max:5',
        ];
    }

    public function messages()
    {
        return [
            'testimoni.required' => 'Testimoni harus diisi.',
            'testimoni.max' => 'Testimoni maksimal 100 karakter.',
            'nilai.required' => 'Bintang harus dipilih.',
            'nilai.integer' => 'Pemberian bintang tidak valid.',
            'nilai.min' => 'Bintang minimal 1.',
            'nilai.max' => 'Bintang maksimal 5.',
        ];
    }

    public function save()
    {
        $this->validate();

        Testimoni::create([
            'user_id' => auth()->id(),
            'tryout_id' => $this->tryoutId,
            'testimoni' => $this->testimoni,
            'nilai' => $this->nilai,
        ]);
        $this->dispatch('testimoni-saved');
    }
}; ?>
<div x-data="{ successMessage: false }" x-cloak
    x-on-testimoni-saved.window="successMessage = true; setTimeout(() => successMessage = false, 3000)">
    <!-- Pesan Sukses -->
    <div x-show="successMessage" class="alert alert-success">
        Testimoni berhasil disimpan!
    </div>

    @if ($userTestimonial == null)
        <div class="card">
            <div class="card-body">
                <form wire:submit='save'>
                    <h6 class="mb-2 fw-bold text-lg mb-10">Kirim Testimonimu</h6>
                    <div class="form-group">
                        <textarea wire:model="testimoni" id="testimoni" maxlength="100" class="form-control h-120-px px-10"
                            placeholder="Tulis testimoni kamu disini..."></textarea>
                    </div>
                    <div class="row justify-content-end">
                        <span id="char_count" class="text-end">0/100</span>
                    </div>
                    @error('testimoni')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <label for="nilai" class="form-label mb-0">Beri bintang untuk tryout ini</label>
                    <div x-data="{ nilai: @entangle('nilai'), hoverRating: 0 }" class="input-group">
                        <span class="input-group-text border-0 bg-transparent p-0">
                            <!-- Bintang 1 -->
                            <i class="ri-star-fill text-2xl cursor-pointer" @mouseenter="hoverRating = 1"
                                @mouseleave="hoverRating = 0" :class="{ 'text-yellow': nilai >= 1 || hoverRating >= 1 }"
                                @click="nilai = 1; $wire.set('nilai', 1)"></i>

                            <!-- Bintang 2 -->
                            <i class="ri-star-fill text-2xl cursor-pointer" @mouseenter="hoverRating = 2"
                                @mouseleave="hoverRating = 0" :class="{ 'text-yellow': nilai >= 2 || hoverRating >= 2 }"
                                @click="nilai = 2; $wire.set('nilai', 2)"></i>

                            <!-- Bintang 3 -->
                            <i class="ri-star-fill text-2xl cursor-pointer" @mouseenter="hoverRating = 3"
                                @mouseleave="hoverRating = 0" :class="{ 'text-yellow': nilai >= 3 || hoverRating >= 3 }"
                                @click="nilai = 3; $wire.set('nilai', 3)"></i>

                            <!-- Bintang 4 -->
                            <i class="ri-star-fill text-2xl cursor-pointer" @mouseenter="hoverRating = 4"
                                @mouseleave="hoverRating = 0" :class="{ 'text-yellow': nilai >= 4 || hoverRating >= 4 }"
                                @click="nilai = 4; $wire.set('nilai', 4)"></i>

                            <!-- Bintang 5 -->
                            <i class="ri-star-fill text-2xl cursor-pointer" @mouseenter="hoverRating = 5"
                                @mouseleave="hoverRating = 0" :class="{ 'text-yellow': nilai >= 5 || hoverRating >= 5 }"
                                @click="nilai = 5; $wire.set('nilai', 5)"></i>
                        </span>
                    </div>
                    @error('nilai')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    @error('nilai')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-primary-600 px-20 mt-10">Kirim</button>
                </form>
            </div>
        </div>
    @endif
</div>
@push('script')
    <script>
        const testimoni = document.querySelector('#testimoni');

        testimoni.addEventListener('input', () => {
            const karakter = testimoni.value.length;
            document.querySelector('#char_count').textContent = (`(${karakter}/100)`);
        });
    </script>
@endpush
