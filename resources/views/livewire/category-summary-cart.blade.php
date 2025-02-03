<?php

use Livewire\Volt\Component;

new class extends Component {
    public $tryoutId;
    public $bidang = [];

    public function mount($tryoutId)
    {
        $this->tryoutId = $tryoutId;

        $userId = Auth::user()->id;

        $bidangData = DB::table('soal_tryouts')
            ->select('soal_tryouts.bidang_id', 'bidang_tryouts.nama as kategori', DB::raw('COUNT(soal_tryouts.id) as total_soal'), DB::raw('COUNT(CASE WHEN user_answers.jawaban = soal_tryouts.jawaban THEN 1 END) as benar'), DB::raw('COUNT(CASE WHEN user_answers.jawaban = soal_tryouts.jawaban THEN 1 END) as benar'), DB::raw('COUNT(CASE WHEN user_answers.jawaban != soal_tryouts.jawaban AND user_answers.jawaban IS NOT NULL THEN 1 END) as salah'), DB::raw('COUNT(CASE WHEN user_answers.jawaban IS NULL THEN 1 END) as tidak_dikerjakan'))
            ->leftJoin('bidang_tryouts', 'soal_tryouts.bidang_id', '=', 'bidang_tryouts.id')
            ->leftJoin('user_answers', function ($join) use ($userId) {
                $join->on('soal_tryouts.id', '=', 'user_answers.soal_id')->where('user_answers.user_id', '=', $userId);
            })
            ->where('soal_tryouts.tryout_id', $this->tryoutId)
            ->groupBy('soal_tryouts.bidang_id', 'bidang_tryouts.nama')
            ->get();

        foreach ($bidangData as $item) {
            $totalSoal = $item->total_soal;
            $benar = $item->benar;
            $salah = $item->salah;
            $tidak_dikerjakan = $item->tidak_dikerjakan;

            $persenBenar = $totalSoal ? ($benar / $totalSoal) * 100 : 0;

            $this->bidang[] = [
                'bidang_id' => $item->bidang_id,
                'kategori' => $item->kategori,
                'total_soal' => $totalSoal,
                'benar' => $benar,
                'salah' => $salah,
                'tidak_dikerjakan' => $tidak_dikerjakan,
                'persen_benar' => round($persenBenar, 2),
            ];
        }
    }
}; ?>

<div class="card h-100">
    <div class="card-header pb-0">
        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
            <h6 class="mb-2 fw-bold text-lg mb-0">{{ __('Rangkuman Hasil Per Bidang') }}</h6>
            <div class="d-flex justify-content-end mb-0">
                <ul class="nav bordered-tab nav-pills mb-0" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="chart-view-tab" data-bs-toggle="pill"
                            data-bs-target="#chart-view" type="button" role="tab" aria-controls="chart-view"
                            aria-selected="true">{{ __('Grafik') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="table-view-tab" data-bs-toggle="pill" data-bs-target="#table-view"
                            type="button" role="tab" aria-controls="table-view" aria-selected="false"
                            tabindex="-1">{{ __('Tabel') }}</button>
                    </li>
                </ul>
            </div>

        </div>
    </div>
    <div class="card-body p-24">
        <div class="tab-content" id="viewTabContent">
            <div class="tab-pane fade show active" id="chart-view" role="tabpanel" aria-labelledby="chart-tab">
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div id="paymentStatusChart" class="margin-16-minus y-value-left"></div>
                    </div>
                    <div class="col-12 col-md-4">
                        <ul class="d-flex flex-column gap-12 max-h-400-px overflow-y-auto">
                            @foreach (collect($this->bidang)->sortByDesc('persen_benar') as $bidang)
                                <li>
                                    <a
                                        href="{{ route('tryouts.hasil.pembahasanByCategory', ['id' => $this->tryoutId, 'categoryId' => $bidang['bidang_id']]) }}">
                                        <span class="text-lg">{{ $bidang['kategori'] }}: <span
                                                class="text-{{ $bidang['persen_benar'] < 60 ? 'danger' : ($bidang['persen_benar'] < 80 ? 'warning' : 'success') }}-600 fw-semibold">{{ $bidang['persen_benar'] }}%</span>
                                        </span></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="viewTabContent">
            <div class="tab-pane fade show" id="table-view" role="tabpanel" aria-labelledby="table-tab">
                <div class="table-responsive scroll-sm max-h-266-px overflow-y-auto">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Bidang') }}</th>
                                <th scope="col">{{ __('Total Soal') }}</th>
                                <th scope="col">{{ __('Benar') }}</th>
                                <th scope="col">{{ __('Salah') }}</th>
                                <th scope="col">{{ __('Tidak Dikerjakan') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->bidang as $bidang)
                                <tr>
                                    <td><a href="{{ route('tryouts.hasil.pembahasanByCategory', ['id' => $this->tryoutId, 'categoryId' => $bidang['bidang_id']]) }}"
                                            class="text-primary-600 fw-bold">{{ $bidang['kategori'] }}</a>
                                    </td>
                                    <td>{{ $bidang['total_soal'] }}</td>
                                    <td>{{ $bidang['benar'] }}</td>
                                    <td>{{ $bidang['salah'] }}</td>
                                    <td>{{ $bidang['tidak_dikerjakan'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function() {
            const bidangData = @json($this->bidang);

            // ================================ Client Payment Status chart End ================================
            var options = {
                series: [{
                    name: "Persentase Hasil",
                    data: bidangData.map(item => item.persen_benar)
                }],
                legend: {
                    show: false
                },
                chart: {
                    type: "bar",
                    height: 260,
                    toolbar: {
                        show: true
                    },
                },
                grid: {
                    show: true,
                    borderColor: "#D1D5DB",
                    strokeDashArray: 4,
                    position: "back",
                },
                plotOptions: {
                    bar: {
                        distributed: true,
                        borderRadius: 4,
                        columnWidth: '10px', // Sesuaikan lebar kolom
                    },
                },
                dataLabels: {
                    enabled: false
                },
                states: {
                    hover: {
                        filter: {
                            type: "none"
                        }
                    }
                },
                stroke: {
                    show: true,
                    width: 0,
                    colors: ["transparent"]
                },
                xaxis: {
                    categories: bidangData.map(item => item.kategori), // Nama bidang
                    title: {
                        text: 'Bidang',
                    }
                },
                yaxis: {
                    max: 100, // Skala y-axis maksimum 100%
                    title: {
                        text: 'Persentase Hasil',
                    }
                },
                fill: {
                    type: 'solid', // Tidak ada gradient, gunakan warna solid
                },
                colors: bidangData.map(item => {
                    const persenBenar = parseFloat(item.persen_benar);
                    if (persenBenar < 60) {
                        return '#FF6666'; // Merah
                    } else if (persenBenar >= 60 && persenBenar < 80) {
                        return '#FFA500'; // Oranye
                    } else {
                        return '#66CC66'; // Hijau
                    }
                })

            };

            var chart = new ApexCharts(document.querySelector("#paymentStatusChart"), options);
            chart.render();

            // ================================ Client Payment Status chart End ================================
        });
    </script>
@endpush
