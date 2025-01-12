<?php

use Livewire\Volt\Component;
use Livewire\Features\SupportAttributes\AttributeCollection;
use App\Models\UserTryouts;
new class extends Component {
    public UserTryouts $userTryout;
    public $jumlahBenar = 0;
    public $jumlahSalah = 0;
    public $jumlahRaguRagu = 0;
    public $jumlahTidakDikerjakan = 0;
    public $totalSoal = 0;

    public function mount($userTryout)
    {
        $this->userTryout = $userTryout;

        // Memuat relasi yang diperlukan
        $this->userTryout->load('tryout.questions', 'user.answers.soal');

        // Mengambil semua soal dari tryout
        $soalTryouts = $this->userTryout->tryout->questions;

        // Menghitung total soal
        $this->totalSoal = $soalTryouts->count();

        // Mengelompokkan jawaban pengguna berdasarkan soal_id
        $userAnswers = $this->userTryout->user->answers
            ->whereIn('soal_id', $soalTryouts->pluck('id')) // Pastikan hanya jawaban untuk soal pada tryout ini
            ->keyBy('soal_id'); // Mengelompokkan berdasarkan soal_id untuk akses cepat

        // Inisialisasi hitungan
        $jumlahBenar = 0;
        $jumlahSalah = 0;
        $jumlahRaguRagu = 0;
        $jumlahTidakDikerjakan = 0;

        foreach ($soalTryouts as $soal) {
            $answer = $userAnswers[$soal->id] ?? null;

            if (!$answer || $answer->status === 'tidak_dijawab') {
                // Tidak ada jawaban (tidak dikerjakan)
                $jumlahTidakDikerjakan++;
            } elseif ($answer->status === 'ragu') {
                // Status ragu-ragu
                $jumlahRaguRagu++;
            } elseif ($answer->jawaban === $soal->jawaban) {
                // Jawaban benar
                $jumlahBenar++;
            } else {
                // Jawaban salah
                $jumlahSalah++;
            }
        }

        // Update hasil hitungan
        $this->jumlahBenar = $jumlahBenar;
        $this->jumlahSalah = $jumlahSalah;
        $this->jumlahRaguRagu = $jumlahRaguRagu;
        $this->jumlahTidakDikerjakan = $jumlahTidakDikerjakan;
    }
};
?>

<div class="card radius-8 border-0">
    <div class="card-header border-bottom d-flex align-items-center flex-wrap gap-2 justify-content-between">
        <h6 class="mb-2 fw-bold text-lg">Ringkasan Hasil</h6>
        <a href="{{ route('tryouts.hasil.pembahasan', $this->userTryout->tryout_id) }}"
            class="btn btn-outline-primary-600 d-inline-flex align-items-center gap-2 text-sm btn-sm px-8 py-6">
            <iconify-icon icon="mdi:eye" class="icon text-xl"></iconify-icon> Lihat Pembahasan
        </a>
    </div>
    <div class="card-body p-24">

        <div class="position-relative">
            <div id="statisticsDonutChart" class="mt-36 flex-grow-1 apexcharts-tooltip-z-none title-style circle-none">
            </div>
            <div class="text-center position-absolute top-50 start-50 translate-middle">
                <span class="text-secondary-light">Total Soal</span>
                <h6 class="">{{ $this->totalSoal }}</h6>
            </div>
        </div>

        <ul class="row gy-4 mt-3">
            <li class="col-6 d-flex flex-column align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="w-12-px h-8-px rounded-pill bg-success-600"></span>
                    <span class="text-secondary-light text-sm fw-normal">Benar</span>
                </div>
                <h6 class="text-primary-light fw-bold mb-0">{{ $this->jumlahBenar }}</h6>
            </li>
            <li class="col-6 d-flex flex-column align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="w-12-px h-8-px rounded-pill bg-danger-600"></span>
                    <span class="text-secondary-light text-sm fw-normal">Salah </span>
                </div>
                <h6 class="text-primary-light fw-bold mb-0">{{ $this->jumlahSalah }}</h6>
            </li>
            <li class="col-6 d-flex flex-column align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="w-12-px h-8-px rounded-pill bg-warning-600"></span>
                    <span class="text-secondary-light text-sm fw-normal">Ragu-ragu </span>
                </div>
                <h6 class="text-primary-light fw-bold mb-0">{{ $this->jumlahRaguRagu }}</h6>
            </li>
            <li class="col-6 d-flex flex-column align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="w-12-px h-8-px rounded-pill bg-neutral-600"></span>
                    <span class="text-secondary-light text-sm fw-normal">Tidak dijawab </span>
                </div>
                <h6 class="text-primary-light fw-bold mb-0">{{ $this->jumlahTidakDikerjakan }}</h6>
            </li>
        </ul>

    </div>
</div>
@push('script')
    <script>
        // ================================ User Activities Donut chart End ================================
        var options = {
            series: [{{ $this->jumlahBenar }}, {{ $this->jumlahSalah }}, {{ $this->jumlahRaguRagu }},
                {{ $this->jumlahTidakDikerjakan }}
            ],
            colors: ["#45B369", "#DC2626", "#FF9F29", "#4B5563"],
            labels: ["Benar", "Salah", "Ragu-ragu", "Tidak dijawab"],
            legend: {
                show: false
            },
            chart: {
                type: "donut",
                height: 260,
                sparkline: {
                    enabled: true // Remove whitespace
                },
                margin: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                },
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                }
            },
            stroke: {
                width: 0,
            },
            dataLabels: {
                enabled: false
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: "bottom"
                    }
                }
            }],
        };

        var chart = new ApexCharts(document.querySelector("#statisticsDonutChart"), options);
        chart.render();
        // ================================ User Activities Donut chart End ================================
    </script>
@endpush
