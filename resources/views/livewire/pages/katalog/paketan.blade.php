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
use Illuminate\Support\Facades\Auth;

new class extends Component {
    use WithFileUploads;
    public $paket;
    public $testimonials;
    public $isRequested = false;
    public $requestStatus;
    public $userTryout;
    public $userTryoutStatus;
    public $user_id;
    public $image;
    public $extras = [];

    public function mount($paketId)
    {
        $userId = auth()->id();

        $this->user_id = $userId;

        // Ambil data paket dengan relasi tryouts dan userTryouts
        $this->paket = PaketTryout::where('id', $paketId)
            ->select(['id', 'paket', 'image', 'harga', 'url', 'is_need_confirm', 'deskripsi'])
            ->with([
                'tryouts' => function ($query) use ($userId) {
                    $query->select(['id', 'nama', 'tanggal', 'waktu', 'status', 'image'])->with([
                        'batch:id,nama',
                        'userTryouts' => function ($subQuery) use ($userId) {
                            $subQuery->where('user_id', $userId)->select(['id', 'user_id', 'tryout_id', 'status']);
                        },
                    ]);
                },
            ])
            ->first();

        session()->put('paket_id', $paketId);
        $this->extras = $this->paket->extras;

        // Redirect jika paket tidak ditemukan
        if (!$this->paket) {
            return redirect()->route('katalog')->with('error', 'Paket tidak ditemukan.');
        }

        // Ambil testimoni yang aktif
        $this->testimonials = Testimoni::where('tryout_id', $paketId)->where('visibility', 'active')->get();

        // Cek status akses pengguna
        $request = UserAccessPaket::select('status')->where('user_id', $userId)->where('paket_id', $paketId)->first();

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
            'image.required' => 'Bukti harus diunggah.',
            'image.image' => 'Bukti harus berupa gambar.',
            'image.mimes' => 'Bukti harus dalam format JPEG, PNG, JPG, GIF, atau SVG.',
            'image.max' => 'Bukti tidak boleh lebih besar dari 2 MB.',
        ];
    }

    public function konfirmasiAdmin()
    {
        $this->validate();

        try {
            // Simpan file bukti pembayaran
            $filePath = $this->image->store('bukti-transaksi', 'public');

            // Buat record UserAccessPaket
            $userAccessPaket = UserAccessPaket::create([
                'user_id' => auth()->id(),
                'paket_id' => $this->paket->id,
                'status' => 'requested',
                'image' => $filePath,
                'catatan' => 'Menunggu konfirmasi',
            ]);

            // Kirim notifikasi ke admin
            auth()
                ->user()
                ->notify(new \App\Notifications\KonfirmasiAdminNotifications($userAccessPaket->id, 'paket'));

            // Set status dan tampilkan pesan sukses
            $this->isRequested = true;
            session()->flash('message', 'Bukti berhasil dikirim.');
            // Refresh halaman
            $this->requestStatus = true;
        } catch (\Exception $e) {
            // Tangani error
            session()->flash('error', 'Terjadi kesalahan saat mengirim bukti');
        }
    }
}; ?>
<div class="row">
    <div class="col-xl-8 mb-20">
        @if ($paket)
            <img src="{{ $paket->image ? asset('storage/' . $paket->image) : asset('assets/images/product/product-default.jpg') }}"
                alt="{{ $paket->paket }}" class="w-100 h-auto max-h-400-px rounded mb-20">
            @if ($paket->deskripsi)
                <div class="card" style="margin: 20px 0;">
                    <div class="card-body">
                        <h5 class="fw-semibold">Deskripsi</h5>
                        <p class="mt-10 text-secondary-light" style="text-align: justify;">
                            {!! $paket->deskripsi !!}
                        </p>

                    </div>
                </div>
            @endif
            <div class="row g-3">
                <h5 class="col-12">Tryout</h5>
                @forelse ($paket->tryouts as $item)
                    @if ($item->status === 'active')
                        <div class="col-xxl-3 col-md-4 col-sm-6">
                            <div class="nft-card h-100 bg-base radius-16 overflow-hidden d-flex flex-column">
                                <div class="radius-16 overflow-hidden">
                                    <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/images/product/product-default.jpg') }}"
                                        alt="" class="w-100 h-100 max-h-194-px object-fit-cover">
                                </div>
                                <div class="p-10 d-flex flex-column justify-content-between flex-grow-1">
                                    <div>
                                        <span
                                            class="text-sm fw-semibold text-primary-600">{{ $item->batch?->nama }}</span>
                                        <a href="{{ route('katalog.detail', ['id' => $item->id]) }}"
                                            class="text-xl fw-bold text-primary-light">{{ $item->nama }}</a>
                                        <div
                                            class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                            <span class="text-sm text-secondary-light fw-medium">Durasi:
                                                <span
                                                    class="text-sm fw-semibold text-primary-600">{{ $item->waktu }}</span>
                                            </span>
                                        </div>
                                        <div
                                            class="mt-10 d-flex align-items-center justify-content-between gap-8 flex-wrap">
                                            <span class="text-sm text-secondary-light fw-medium">Jumlah Soal:
                                                <span
                                                    class="text-sm fw-semibold text-primary-600">{{ $item->questions->count() }}</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center flex-column align-items-stretch gap-8 mt-10">
                                        @if ($requestStatus === 'accepted')
                                            @if ($item->userTryouts->count() > 0)
                                                @if ($item->userTryouts->where('user_id', $user_id)->first()?->status->value == 'finished')
                                                    <a href="{{ route('tryouts.hasil.index', $item->id) }}"
                                                        wire:navigate
                                                        class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Hasil</a>
                                                    <a href="{{ route('tryouts.hasil.pembahasan', $item->id) }}"
                                                        wire:navigate
                                                        class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Pembahasan</a>
                                                @elseif (
                                                    $item->userTryouts->where('user_id', $user_id)->first()?->status->value == 'started' ||
                                                        $item->userTryouts->first()->status == 'paused')
                                                    <a href="{{ route('tryouts.show', ['id' => $item->id]) }}"
                                                        wire:navigate
                                                        class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Lanjutkan</a>
                                                @endif
                                            @else
                                                <a href="{{ route('katalog.detail', ['id' => $item->id]) }}"
                                                    class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Detail</a>
                                                <a href="{{ route('tryouts.show', ['id' => $item->id]) }}"
                                                    wire:navigate
                                                    class="btn rounded-pill btn-primary-600 radius-8 px-12 py-6 flex-grow-1">Mulai
                                                    Kerjakan</a>
                                            @endif
                                        @else
                                            <a href="{{ route('katalog.detail', ['id' => $item->id]) }}"
                                                class="btn rounded-pill border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">Detail</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-12">
                        <p class="text-center text-secondary">Tidak ada tryout yang tersedia.</p>
                    </div>
                @endforelse
            </div>
        @else
            <div class="col-12">
                <p class="text-center text-danger">Paket tidak ditemukan.</p>
            </div>
        @endif
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
                @if ($requestStatus === 'accepted')
                    @if (count($extras) > 0)
                        @foreach ($extras as $extra)
                            @if (in_array('detail_paket_after_confirm', $extra['display_on']) && $extra['type'] === 'button')
                                <a href="{{ $extra['data'] }}" class="btn btn-primary-600 radius-8 px-12 py-6 mt-16">
                                    {{ $extra['title'] }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                    <div class="d-flex flex-column">
                        <p class="mb-1 text-primary">Status: <span class="text-warning">Akses diterima</span></p>
                    </div>
                @elseif ($requestStatus === 'requested' || $isRequested)
                    <div class="d-flex flex-column">
                        <p class="mb-1 text-primary">Status: <span class="text-warning">Sudah dibeli</span></p>
                        <p class="mb-0 text-secondary">Menunggu konfirmasi admin...</p>
                    </div>
                @else
                    @if ($paket->is_need_confirm)
                        @if (count($extras) > 0)
                            @foreach ($extras as $extra)
                                @if (in_array('detail_paket_before_confirm', $extra['display_on']) && $extra['type'] === 'button')
                                    <a href="{{ $extra['data'] }}"
                                        class="btn btn-primary-600 radius-8 px-12 py-6 mt-16">
                                        {{ $extra['title'] }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                        <a href="#" data-bs-toggle="modal" data-bs-target="#beliSekarang"
                            class="btn btn-primary-600 radius-8 px-12 py-6 mt-16">
                            Beli Sekarang
                        </a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#konfirmasiAdminModal"
                            class="btn border text-neutral-500 border-neutral-500 radius-8 px-12 py-6 bg-hover-neutral-500 text-hover-white flex-grow-1">
                            Konfirmasi Admin
                        </a>
                    @else
                    @endif
                @endif

            </div>
        </div>
    </div>

    @if (!$isRequested)
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
                            <label for="file-upload-name">Upload Bukti </label>
                            <input type="file" class="form-control w-auto mt-24 form-control-lg"
                                id="file-upload-name" wire:model="image" accept="image/*">
                            <ul id="uploaded-img-names"></ul>
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="konfirmasiAdmin"
                            data-bs-dismiss="modal" wire:loading.attr="disabled">
                            <span wire:loading wire:target="konfirmasiAdmin">Memproses...</span>
                            <span wire:loading.remove wire:target="konfirmasiAdmin">Kirim Bukti Pembayaran</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div wire:ignore.self class="modal fade modal-lg" id="beliSekarang" tabindex="-1"
        aria-labelledby="beliSekarangLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="beliSekarangLabel">Cara pembelian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ol class="list-decimal pl-6 space-y-4">
                        <li>
                            <p class="font-semibold">Lakukan Transfer Pembayaran</p>
                            <p>Kirim pembayaran ke rekening berikut:</p>
                            <div class="mt-2 bg-gray-100 p-4 rounded-lg">
                                <p>Bank: <strong class="font-semibold">BCA</strong></p>
                                <p>No. Rekening: <strong class="font-semibold">3090701168</strong></p>
                                <p>Atas Nama: <strong class="font-semibold">APEX MEDIKA EDUKASI CV
                                    </strong></p>
                                <p>Keterangan transfer: <strong>Apex Academy</strong> </p>
                            </div>
                        </li>

                        <li>
                            <p class="font-semibold">Konfirmasi ke Admin</p>
                            <p>Setelah transfer, klik tombol <strong>konfirmasi admin</strong></p>

                        </li>

                        <li>
                            <p class="font-semibold">Upload Bukti Pembayaran</p>
                            <p>Lampirkan bukti transfer melalui konfirmasi</p>
                        </li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


</div>
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById("file-upload-name");
            const ul = document.getElementById("uploaded-img-names");

            if (fileInput && ul) {
                fileInput.addEventListener("change", function(event) {
                    const fileList = event.target.files;

                    // Reset ul content
                    ul.innerHTML = '';

                    // Add each file name to the list
                    for (let i = 0; i < fileList.length; i++) {
                        const li = document.createElement("li");
                        li.classList.add("uploaded-image-name-list", "text-primary-600", "fw-semibold",
                            "d-flex", "align-items-center", "gap-2");

                        // Add file name and remove icon
                        li.innerHTML = `
                    <iconify-icon icon="ph:link-break-light" class="text-xl text-secondary-light"></iconify-icon>
                    ${fileList[i].name}
                    <iconify-icon icon="radix-icons:cross-2" class="remove-image text-xl text-secondary-light text-hover-danger-600"></iconify-icon>
                `;

                        // Add remove functionality
                        li.querySelector('.remove-image').addEventListener('click', () => {
                            ul.removeChild(li);
                        });

                        ul.appendChild(li);
                    }
                });
            }
        });
    </script>
@endpush
