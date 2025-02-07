<?php

namespace App\Http\Livewire\Pages\Katalog;

use Livewire\Volt\Component;
use App\Models\Tryouts;
use App\Models\Testimoni;
use App\Models\UserAccessTryouts;
use App\Models\UserTryouts;
use Filament\Notifications\Notification;
use Livewire\WithFileUploads;
use App\Models\UserAccessPaket;

new class extends Component {
    use WithFileUploads;
    public $tryout;
    public $testimonials;
    public $isRequested = false;
    public $requestStatus;
    public $userTryout;
    public $selectedPaketId;

    public $image;

    public function mount($tryoutId)
    {
        if (session()->has('paket_id')) {
            $this->selectedPaketId = session('paket_id');
            session()->forget('paket_id');
        }
        $this->tryout = Tryouts::where('id', $tryoutId)
            ->where('status', 'active')
            ->select(['id', 'nama', 'image', 'waktu', 'harga', 'batch_id', 'url', 'is_need_confirm', 'is_configurable', 'deskripsi'])
            ->with(['batch'])
            ->first();
        if ($this->tryout == null) {
            return redirect()->route('katalog');
        }

        $this->userTryout = UserTryouts::where('user_id', auth()->id())
            ->where('tryout_id', $tryoutId)
            ->first();

        $this->testimonials = Testimoni::where('tryout_id', $tryoutId)->where('visibility', 'active')->get();

        if ($this->tryout->tryoutHasPakets->count() > 0) {
            $paketId = request('paket_id') ?? $this->selectedPaketId;

            if ($paketId) {
                $request = UserAccessPaket::select('status')
                    ->where('user_id', auth()->id())
                    ->where('paket_id', $paketId) // Gunakan paket yang sedang dilihat
                    ->first();

                $this->requestStatus = $request ? $request->status : null;
            } else {
                // Paket ID tidak ditemukan, berikan nilai default
                $this->requestStatus = null;
            }
        } else {
            $request = UserAccessTryouts::select('status')
                ->where('user_id', auth()->id())
                ->where('tryout_id', $tryoutId)
                ->first();
            $this->requestStatus = $request ? $request->status : null;
        }
    }

    public function rules()
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function configurableText()
    {
        if ($this->tryout->is_configurable) {
            return 'Bukti Syarat Pendaftaran';
        }
        return 'Bukti Pembayaran';
    }

    public function messages()
    {
        return [
            'image.required' => 'Bukti ' . $this->configurableText() . ' harus diunggah.',
            'image.image' => 'Bukti ' . $this->configurableText() . ' harus berupa gambar.',
            'image.mimes' => 'Bukti ' . $this->configurableText() . ' harus dalam format JPEG, PNG, JPG, GIF, atau SVG.',
            'image.max' => 'Bukti ' . $this->configurableText() . ' tidak boleh lebih besar dari 2 MB.',
        ];
    }

    public function konfirmasiAdmin()
    {
        $this->validate();

        $filePath = $this->image->store('bukti-transaksi', 'public');

        $userAccessTryout = UserAccessTryouts::create([
            'user_id' => auth()->user()->id,
            'tryout_id' => $this->tryout->id,
            'status' => 'requested',
            'image' => $filePath,
            'catatan' => 'Menunggu konfirmasi',
        ]);

        auth()
            ->user()
            ->notify(new \App\Notifications\KonfirmasiAdminNotifications($userAccessTryout->id, 'tryout'));

        session()->flash('message', 'Bukti pembayaran berhasil dikirim.');

        $this->isRequested = true;
    }
}; ?>

<div class="row">
    <div class="col-xl-8 mb-20">
        <img src="{{ $tryout->image ? asset('storage/' . $tryout->image) : asset('assets/images/product/product-default.jpg') }}"
            alt="{{ $tryout->nama }}" class="w-100 h-auto max-h-400-px rounded mb-20">
        <div class="card">
            <div class="card-body">
                @if ($tryout->deskripsi)
                    <h5 class="fw-semibold">Deskripsi</h5>
                    <p class="mt-10 text-secondary-light" style="text-align: justify;">
                        {!! $tryout->deskripsi !!}
                    </p>
                @endif
                <h5 class="my-20">Testimoni</h5>
                <ul class="d-flex flex-column gap-2">
                    @forelse ($testimonials as $testimoni)
                        <li class="border border-top-0 border-start-0 border-end-0">
                            <div class="p-2 row">
                                <div class="col-1">
                                    <img src="{{ $testimoni->user->image_url ? asset('storage/' . $testimoni->user->image_url) : asset('assets/images/avatar/profile-default.png') }}"
                                        class="rounded-circle h-32-px w-32-px">
                                </div>
                                <div class="col-11 d-flex justify-content-between">
                                    <div>
                                        <h6 class="fw-semibold text-md mb-0">{{ $testimoni->user->name }}</h6>
                                        <span
                                            class="text-sm text-secondary-light">{{ $testimoni->user->userAcademy->universitas ?? '-' }}</span>
                                        <p class="mt-10">"{{ $testimoni->testimoni }}"</p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($testimoni->nilai >= $i)
                                                <i class="ri-star-fill text-primary-600"></i>
                                            @else
                                                <i class="ri-star-line text-secondary-300"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li>
                            <p>Belum ada testimoni</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-4 mb-20">
        <div class="sticky-top z-1" style="top: 80px;">
            <div class="card p-3 gap-20">
                <h6 class="fw-bold text-primary-600">{{ $tryout->nama }}</h6>
                <ul class="list-unstyled d-flex flex-column">
                    <li
                        class="d-flex align-items-center flex-wrap justify-content-between text-sm text-secondary-light mb-1 border-start-0 border-end-0 border-bottom-0 border py-10">
                        <div class="d-flex align-items-center flex-nowrap gap-1">
                            <iconify-icon icon="mdi:clock-time-four"
                                class="text-primary-600 icon me-1 text-lg"></iconify-icon>
                            <span class="text-xl">Durasi:</span>
                        </div>
                        <span class="text-xl">{{ $tryout->waktu }} Menit</span>
                    </li>
                    <li
                        class="d-flex align-items-center flex-wrap justify-content-between text-sm text-secondary-light mb-1 border-start-0 border-end-0 border-bottom-0 border py-10">
                        <div class="d-flex align-items-center flex-nowrap gap-1">
                            <iconify-icon icon="mdi:currency-usd"
                                class="text-primary-600 icon me-1 text-xl"></iconify-icon>
                            <span class="text-xl">Harga:</span>
                        </div>
                        @if ($tryout->harga && $tryout->harga > 0)
                            <span class="text-xl">Rp. {{ number_format($tryout->harga, 0, ',', '.') }}</span>
                        @else
                            <span class="text-xl">Gratis</span>
                        @endif
                    </li>
                    <li
                        class="d-flex align-items-center flex-wrap justify-content-between text-sm text-secondary-light mb-1 border-start-0 border-end-0 border-bottom-0 border py-10">
                        <div class="d-flex align-items-center flex-nowrap gap-1">
                            <iconify-icon icon="mdi:calendar" class="text-primary-600 icon me-1 text-xl"></iconify-icon>
                            <span class="text-xl">Batch:</span>
                        </div>
                        <span class="text-xl">{{ $tryout->batch->nama }}</span>
                    </li>
                    <li
                        class="d-flex align-items-center flex-wrap justify-content-between text-sm text-secondary-light mb-1 border-start-0 border-end-0 border-bottom-0 border py-10">
                        <div class="d-flex align-items-center flex-nowrap gap-1">
                            <iconify-icon icon="ph:list-numbers-light"
                                class="text-primary-600 icon me-1 text-xl"></iconify-icon>
                            <span class="text-xl">Jumlah soal:</span>
                        </div>
                        <span class="text-xl">{{ $tryout->questions->count() }}</span>
                    </li>
                    <li
                        class="d-flex align-items-center flex-wrap justify-content-between text-sm text-secondary-light mb-1 border-start-0 border-end-0 border py-10">
                        <div class="d-flex align-items-center flex-nowrap gap-1">
                            <iconify-icon icon="mdi:account" class="text-primary-600 icon me-1 text-xl"></iconify-icon>
                            <span class="text-xl">Peserta:</span>
                        </div>
                        <span class="text-xl">{{ $tryout->userTryouts->count() }}</span>
                    </li>
                </ul>
                @if ($requestStatus === 'accepted' || !$tryout->is_need_confirm)
                    @if ($userTryout?->status?->value == 'finished')
                        <a href="{{ route('tryouts.hasil.index', $tryout->id) }}" wire:navigate
                            class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 mt-16">
                            Hasil
                        </a>
                        <a href="{{ route('tryouts.hasil.pembahasan', $tryout->id) }}" wire:navigate
                            class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">
                            Pembahasan
                        </a>
                    @elseif ($userTryout?->status?->value == 'started' || $userTryout?->status?->value == 'paused')
                        <a href="{{ route('tryouts.show', ['id' => $tryout->id]) }}" wire:navigate
                            class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">
                            Lanjutkan
                        </a>
                    @else
                        <a href="{{ route('tryouts.show', ['id' => $tryout->id]) }}" wire:navigate
                            class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 mt-16">
                            Mulai
                        </a>
                    @endif
                @elseif ($requestStatus === 'denied')
                    <div class="d-flex flex-column">
                        <p class="mb-1 text-primary">Status: <span class="text-danger">Ditolak!</span></p>
                        <p class="mb-0 text-secondary">Akses ditolak</p>
                    </div>
                @elseif ($requestStatus === 'requested' || $isRequested)
                    <div class="d-flex flex-column">
                        <p class="mb-1 text-primary">Status: <span class="text-warning">Sudah dibeli</span></p>
                        <p class="mb-0 text-secondary">Menunggu konfirmasi admin...</p>
                    </div>
                @else
                    <a href="{{ $tryout->url }}" target="_blank"
                        class="btn btn-primary-600 radius-8 px-12 py-6 mt-16">
                        Join Grup WA
                    </a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#konfirmasiAdminModal"
                        class="btn border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">
                        Daftar Sekarang
                    </a>
                @endif

            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="konfirmasiAdminModal" tabindex="-1"
        aria-labelledby="konfirmasiAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="konfirmasiAdminModalLabel">Konfirmasi Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    <p>{{ $tryout->nama }}?</p>
                    <div class="mb-3">
                        <label for="file-upload-name">Upload {{ $this->configurableText() }} </label>
                        <input type="file" class="form-control w-auto mt-24 form-control-lg" id="file-upload-name"
                            wire:model="image" accept="image/*">
                        <ul id="uploaded-img-names"></ul>
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" wire:click="konfirmasiAdmin"
                        wire:loading.attr="disabled">
                        <span wire:loading wire:target="konfirmasiAdmin">Memproses...</span>
                        <span wire:loading.remove wire:target="konfirmasiAdmin">Kirim Bukti Pembayaran</span>
                    </button>
                </div>
            </div>
        </div>
    </div>


</div>
@push('script')
    <script>
        document.getElementById("file-upload-name").addEventListener("change", function(event) {
            var fileInput = event.target;
            var fileList = fileInput.files;
            var ul = document.getElementById("uploaded-img-names");

            // Add show-uploaded-img-name class to the ul element if not already added
            ul.classList.add("show-uploaded-img-name");

            // Append each uploaded file name as a list item with Font Awesome and Iconify icons
            for (var i = 0; i < fileList.length; i++) {
                var li = document.createElement("li");
                li.classList.add("uploaded-image-name-list", "text-primary-600", "fw-semibold", "d-flex",
                    "align-items-center", "gap-2");

                // Create the Link Iconify icon element
                var iconifyIcon = document.createElement("iconify-icon");
                iconifyIcon.setAttribute("icon", "ph:link-break-light");
                iconifyIcon.classList.add("text-xl", "text-secondary-light");

                // Create the Cross Iconify icon element
                var crossIconifyIcon = document.createElement("iconify-icon");
                crossIconifyIcon.setAttribute("icon", "radix-icons:cross-2");
                crossIconifyIcon.classList.add("remove-image", "text-xl", "text-secondary-light",
                    "text-hover-danger-600");

                // Add event listener to remove the image on click
                crossIconifyIcon.addEventListener("click", (function(liToRemove) {
                    return function() {
                        ul.removeChild(liToRemove); // Remove the corresponding list item
                    };
                })(li)); // Pass the current list item as a parameter to the closure

                // Append both icons to the list item
                li.appendChild(iconifyIcon);

                // Append the file name text to the list item
                li.appendChild(document.createTextNode(" " + fileList[i].name));

                li.appendChild(crossIconifyIcon);

                // Append the list item to the unordered list
                ul.appendChild(li);
            }
        });
    </script>
@endpush
