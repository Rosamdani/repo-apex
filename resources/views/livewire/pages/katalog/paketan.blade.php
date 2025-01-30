<?php

namespace App\Http\Livewire\Pages\Katalog;

use Livewire\Volt\Component;
use App\Models\PaketTryout;
use App\Models\Testimoni;
use App\Models\UserAccessTryouts;
use App\Models\UserTryouts;
use Filament\Notifications\Notification;
use Livewire\WithFileUploads;
use App\Models\UserAccessPaket;

new class extends Component {
    use WithFileUploads;
    public $paket;
    public $testimonials;
    public $isRequested = false;
    public $requestStatus;
    public $userTryout;

    public $image;

    public function mount($paketId)
    {
        $this->paket = PaketTryout::where('id', $paketId)
            ->select(['id', 'paket', 'image', 'harga', 'url'])
            ->with([
                'tryouts' => function ($query) {
                    $query->select(['id', 'nama', 'tanggal', 'waktu', 'status'])->with('batch:id,nama');
                },
            ])
            ->first();
        if ($this->paket == null) {
            return redirect()->route('katalog');
        }

        $this->userTryout = UserTryouts::where('user_id', auth()->id())
            ->where('id', $paketId)
            ->first();

        $this->testimonials = Testimoni::where('id', $paketId)->where('visibility', 'active')->get();

        $request = UserAccessPaket::select('status')
            ->where('user_id', auth()->id())
            ->where('paket_id', $paketId)
            ->first();
        $this->requestStatus = $request ? $request->status : null;
    }

    public function rules()
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'Bukti pembayaran harus diunggah.',
            'image.image' => 'Bukti pembayaran harus berupa gambar.',
            'image.mimes' => 'Bukti pembayaran harus dalam format JPEG, PNG, JPG, GIF, atau SVG.',
            'image.max' => 'Bukti pembayaran tidak boleh lebih besar dari 2 MB.',
        ];
    }

    public function konfirmasiAdmin()
    {
        $this->validate();

        $filePath = $this->image->store('bukti-transaksi', 'public');

        $userAccessPaket = UserAccessPaket::create([
            'user_id' => auth()->user()->id,
            'paket_id' => $this->paket->id,
            'status' => 'requested',
            'image' => $filePath,
            'catatan' => 'Menunggu konfirmasi',
        ]);

        auth()
            ->user()
            ->notify(new \App\Notifications\KonfirmasiAdminNotifications($userAccessPaket->id, 'paket'));

        session()->flash('message', 'Bukti pembayaran berhasil dikirim.');

        $this->isRequested = true;
    }
}; ?>

<div class="row">
    <div class="col-xl-8 mb-20">
        <img src="{{ $paket->image ? asset('storage/' . $paket->image) : asset('assets/images/product/product-default.jpg') }}"
            alt="{{ $paket->paket }}" class="w-100 h-auto max-h-400-px rounded mb-20">
        <div class="row g-3">
            @foreach ($paket->tryouts as $item)
                @if ($item->status === 'active')
                    <div class="col-xxl-3 col-md-4 col-sm-6">
                        <div class="nft-card h-100 bg-base radius-16 overflow-hidden d-flex flex-column">
                            <div class="radius-16 overflow-hidden">
                                <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/images/product/product-default.jpg') }}"
                                    alt="" class="w-100 h-100 max-h-194-px object-fit-cover">
                            </div>
                            <div class="p-10 d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <span class="text-sm fw-semibold text-primary-600">{{ $item->batch?->nama }}</span>
                                    <a href="{{ route('katalog.detail', ['id' => $item->id]) }}"
                                        class="text-xl fw-bold text-primary-light">{{ $item->nama }}</a>
                                    <div
                                        class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                        <span class="text-sm text-secondary-light fw-medium">Harga:
                                            @if ($item->harga && $item->harga > 0)
                                                <span class="text-sm fw-semibold text-primary-600">Rp
                                                    {{ number_format($item->harga, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-sm fw-semibold text-primary-600">
                                                    Gratis
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center flex-column align-items-stretch gap-8 mt-10"
                                    style="margin-top: auto;">
                                    @dd($item->status)
                                    @if ($item->status == 'finished')
                                        <a href="{{ route('tryouts.hasil.index', $item->id) }}" wire:navigate
                                            class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Hasil</a>
                                        <a href="{{ route('tryouts.hasil.pembahasan', $item->id) }}" wire:navigate
                                            class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Pembahasan</a>
                                    @elseif ($item->status == 'started' || $item->status == 'paused')
                                        <a href="{{ route('tryouts.show', ['id' => $item->id]) }}" wire:navigate
                                            class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Lanjutkan</a>
                                    @else
                                        @if ($item->harga && $item->harga > 0)
                                            <a href="{{ route('katalog.detail', ['id' => $item->id]) }}"
                                                class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Detail</a>
                                            <a href="{{ $item->url ?? '#' }}"
                                                class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Beli
                                                Sekarang</a>
                                        @else
                                            <a href="{{ route('katalog.detail', ['id' => $item->id]) }}"
                                                class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Detail</a>
                                            <a href="{{ route('tryouts.show', ['id' => $item->id]) }}" wire:navigate
                                                class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Mulai
                                                Kerjakan</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="col-xl-4 mb-20">
        <div class="sticky-top z-1" style="top: 80px;">
            <div class="card p-3 gap-20">
                <h6 class="fw-bold text-primary-600">{{ $paket->paket }}</h6>
                <ul class="list-unstyled d-flex flex-column">
                    <li
                        class="d-flex align-items-center flex-wrap justify-content-between text-sm text-secondary-light mb-1 border-start-0 border-end-0 border-bottom-0 border py-10">
                        <div class="d-flex align-items-center flex-nowrap gap-1">
                            <iconify-icon icon="mdi:clock-time-four"
                                class="text-primary-600 icon me-1 text-lg"></iconify-icon>
                            <span class="text-xl">Total Tryout:</span>
                        </div>
                        <span class="text-xl">{{ $paket->tryouts->count() }} </span>
                    </li>
                    <li
                        class="d-flex align-items-center flex-wrap justify-content-between text-sm text-secondary-light mb-1 border-start-0 border-end-0 border-bottom-0 border py-10">
                        <div class="d-flex align-items-center flex-nowrap gap-1">
                            <iconify-icon icon="mdi:currency-usd"
                                class="text-primary-600 icon me-1 text-xl"></iconify-icon>
                            <span class="text-xl">Harga:</span>
                        </div>
                        @if ($paket->harga && $paket->harga > 0)
                            <span class="text-xl">Rp. {{ number_format($paket->harga, 0, ',', '.') }}</span>
                        @else
                            <span class="text-xl">Gratis</span>
                        @endif
                    </li>
                </ul>
                @if ($requestStatus === 'accepted' || $paket->harga == null || $paket->harga == 0)
                    <div class="d-flex flex-column">
                        <p class="mb-1 text-primary">Status: <span class="text-warning">Akses diterima</span></p>
                    </div>
                @elseif ($requestStatus === 'requested')
                    <div class="d-flex flex-column">
                        <p class="mb-1 text-primary">Status: <span class="text-warning">Sudah dibeli</span></p>
                        <p class="mb-0 text-secondary">Menunggu konfirmasi admin...</p>
                    </div>
                @else
                    <a href="{{ $paket->url }}" target="_blank"
                        class="btn btn-primary-600 radius-8 px-12 py-6 mt-16">
                        Beli Sekarang
                    </a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#konfirmasiAdminModal"
                        class="btn border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">
                        Konfirmasi Admin
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
                    <p>{{ $paket->paket }}</p>
                    <div class="mb-3">
                        <label for="file-upload-name">Upload Bukti Pembayaran </label>
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
