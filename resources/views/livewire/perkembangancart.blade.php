<?php

use Livewire\Volt\Component;
use App\Models\BatchTryouts;
use App\Models\Tryouts;
use App\Models\UserTryouts;

new class extends Component {
    public $batches;
    public $selectedBatch = '';
    public $chartData = [];

    public function mount()
    {
        $this->batches = BatchTryouts::all();
        $this->fetchChartData();
    }

    public function fetchChartData()
    {
        $userId = auth()->id();

        $query = UserTryouts::select('nilai', 'tryout_id')->with('tryout')->where('user_id', $userId)->where('status', 'finished');

        if ($this->selectedBatch) {
            $query->whereHas('tryout', function ($q) {
                $q->where('batch_id', $this->selectedBatch);
            });
        }

        $data = $query
            ->get()
            ->groupBy('tryout_id')
            ->map(function ($group) {
                return $group->avg('nilai');
            });

        $tryoutNames = Tryouts::whereIn('id', $data->keys())->pluck('nama');

        $this->chartData = [
            'categories' => $tryoutNames,
            'values' => $data->values(),
        ];
    }

    public function updatedSelectedBatch()
    {
        $this->fetchChartData();
    }
}; ?>

<div x-data="chartComponent({ chartData: @js($chartData) })" class="col-xxl-12">
    <div class="card h-100">
        <div class="card-header border-bottom d-flex align-items-center flex-wrap gap-2 justify-content-between">
            <h6 class="fw-bold text-lg mb-0">Perkembangan</h6>
            <select wire:model.change="selectedBatch" @change="updateChart()"
                class="form-select form-select-sm w-auto bg-base border text-secondary-light rounded-pill">
                <option value="">Semua Batch</option>
                @foreach ($batches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="card-body">
            <div id="perkembanganChart" class="apexcharts-tooltip-style-1 yaxies-more"></div>
        </div>
    </div>
</div>

@push('script')
    <script>
        function chartComponent(data) {
            return {
                chartData: data.chartData,
                chart: null,

                init() {
                    this.createChart();
                },

                createChart() {
                    var options = {
                        series: [{
                            name: "Nilai",
                            data: this.chartData.values
                        }],
                        chart: {
                            type: "area",
                            height: 240,
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: "straight",
                            width: 3,
                            colors: ['#ffb945']
                        },
                        grid: {
                            show: true,
                            borderColor: "#DBD9D1FF",
                        },
                        fill: {
                            type: "gradient",
                            gradient: {
                                shade: "light",
                                type: "vertical",
                                shadeIntensity: 0.5,
                                gradientToColors: ["#00D4FF"],
                                opacityFrom: 0.6,
                                opacityTo: 0.3,
                                stops: [0, 100],
                            },
                        },
                        xaxis: {
                            categories: this.chartData.categories
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    fontSize: "12px"
                                }
                            }
                        }
                    };

                    this.chart = new ApexCharts(document.querySelector('#perkembanganChart'), options);
                    this.chart.render();
                },

                updateChart() {
                    Livewire.dispatch('updateChart', this.chartData);

                    Livewire.on('updateChart', (data) => {
                        this.chart.updateSeries([{
                            data: data.values
                        }]);
                        this.chart.updateOptions({
                            xaxis: {
                                categories: data.categories
                            }
                        });
                    });
                }
            }
        }
    </script>
@endpush
