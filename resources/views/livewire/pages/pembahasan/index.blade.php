<?php
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Cache;
use App\Helpers\PdfHelper;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    protected $listeners = ['handleKeyPress'];
    public $tryoutId;
    public $tryout;
    public $questions;
    public $currentQuestionIndex = 0;
    public $selectedAnswer = [];
    public $questionStatus = [];
    public $userTryout;
    public $totalBenar;
    public $totalSalah;
    public $totalRaguRagu;
    public $totalTidakDikerjakan;
    public $totalQuestions;
    public $isDoubtful = [];
    public $watermarkText;
    public $outputFilePath;

    public function mount($tryoutId)
    {
        $this->tryoutId = $tryoutId;
        $this->tryout = \App\Models\Tryouts::findOrFail($this->tryoutId);

        $this->userTryout = \App\Models\UserTryouts::firstOrCreate([
            'user_id' => auth()->id(),
            'tryout_id' => $this->tryoutId,
        ]);

        $cacheKey = "tryout_{$this->userTryout->id}";

        if (Cache::has($cacheKey)) {
            $cacheData = Cache::get($cacheKey);

            $this->questions = $cacheData['questions'];
            $this->selectedAnswer = $cacheData['selectedAnswer'];
            $this->questionStatus = $cacheData['questionStatus'];
            $this->isDoubtful = $cacheData['isDoubtful'];
            $this->totalQuestions = $cacheData['totalQuestions'];
        } else {
            $this->initializeQuestionsAndAnswers();
            $this->cacheData();
        }

        $this->updateTotals();
    }

    public function initializeQuestionsAndAnswers()
    {
        $this->questions = \App\Models\SoalTryout::whereIn('id', $this->userTryout->question_order)->get();
        $this->questions = $this->questions->sortBy(fn($question) => array_search($question->id, $this->userTryout->question_order))->values();

        $this->totalQuestions = $this->questions->count();

        $answers = \App\Models\UserAnswer::where('user_id', auth()->id())
            ->whereIn('soal_id', $this->questions->pluck('id'))
            ->get();

        foreach ($this->questions as $question) {
            $answer = $answers->where('soal_id', $question->id)->first();
            $this->selectedAnswer[$question->id] = $answer->jawaban ?? null;
            $this->isDoubtful[$question->id] = $answer && $answer->status === 'ragu-ragu';

            if ($answer) {
                $this->questionStatus[$question->id] = match (true) {
                    $answer->status === 'ragu-ragu' && $answer->jawaban !== $question->jawaban => 'salah',
                    $answer->status === 'ragu-ragu' => 'ragu-ragu',
                    $answer->jawaban === null => 'tidak dijawab',
                    $answer->jawaban === $question->jawaban => 'benar',
                    default => 'salah',
                };
            } else {
                $this->questionStatus[$question->id] = 'tidak dijawab';
            }
        }
    }

    public function cacheData()
    {
        $cacheKey = "tryout_{$this->userTryout->id}";
        Cache::put(
            $cacheKey,
            [
                'questions' => $this->questions,
                'selectedAnswer' => $this->selectedAnswer,
                'questionStatus' => $this->questionStatus,
                'isDoubtful' => $this->isDoubtful,
                'totalQuestions' => $this->totalQuestions,
            ],
            now()->addMinutes(120),
        );
    }

    public function jumpToQuestion($index)
    {
        $this->currentQuestionIndex = $index;
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < $this->totalQuestions - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function handleKeyPress($key)
    {
        if ($key === 'ArrowLeft') {
            $this->prevQuestion();
        } elseif ($key === 'ArrowRight') {
            $this->nextQuestion();
        }
    }

    public function prevQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function updateTotals()
    {
        $this->totalBenar = collect($this->questionStatus)->filter(fn($status) => $status === 'benar')->count();

        $this->totalSalah = collect($this->questionStatus)->filter(fn($status) => $status === 'salah')->count();

        $this->totalRaguRagu = collect($this->questionStatus)->filter(fn($status) => $status === 'ragu-ragu')->count();

        $this->totalTidakDikerjakan = collect($this->questionStatus)->filter(fn($status) => $status === 'tidak dijawab')->count();
    }

    public function downloadPembahasan()
    {
        // $files = $this->questions->map(fn($question) => Storage::path($question['file_pembahasan']))->toArray();
        // $this->outputFilePath = 'merged_watermarked.pdf';
        // PdfHelper::mergeAndAddWatermark($files, $outputFile, $this->watermarkText);
        try {
            if ($this->tryout->file_pembahasan === null) {
                return abort(404);
            }
            $outputFile = Storage::path($this->tryout->file_pembahasan);
            return response()->download($outputFile, 'pembahasan_' . $this->tryout->nama . '.pdf');
        } catch (\Exception $e) {
            return abort(404);
        }
    }
};
?>

<div class="row">
    <!-- Ujian kiri start -->
    <div class="col-xxl-9">


        <!-- Soal Ujian -->
        <div class="card radius-8 border-0 mb-3">
            <div class="card-body p-24">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-lg">Nomor
                        {{ array_search($questions[$currentQuestionIndex]->id, $userTryout->question_order) + 1 }}</h6>
                    <span
                        class="badge text-sm fw-semibold px-20 py-9 radius-4
                        {{ match ($questionStatus[$questions[$currentQuestionIndex]->id]) {
                            'benar' => 'text-success-600 bg-success-100',
                            'salah' => 'text-danger-600 bg-danger-100',
                            'ragi-ragu' => 'text-warning-600 bg-warning-100',
                            default => 'text-neutral-800 bg-neutral-300',
                        } }}">
                        {{ ucfirst($questionStatus[$questions[$currentQuestionIndex]->id]) }}
                    </span>
                </div>

                <!-- Isi Soal -->
                <p class="question-text fw-medium text-justify">{!! $questions[$currentQuestionIndex]->soal !!}</p>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <!-- Pilihan Jawaban -->
                    @foreach (['a', 'b', 'c', 'd', 'e'] as $option)
                        <li>
                            <div
                                class="form-check d-flex align-items-center gap-2 {{ $questions[$currentQuestionIndex]->jawaban === $option ? 'bg-success-200' : '' }} {{ $selectedAnswer[$questions[$currentQuestionIndex]->id] === $option ? ($questionStatus[$questions[$currentQuestionIndex]->id] === 'salah' ? ($isDoubtful[$questions[$currentQuestionIndex]->id] ? 'bg-warning-200' : 'bg-danger-200') : '') : 'bg-transparent' }}">
                                <input type="radio" name="pilihan_{{ $questions[$currentQuestionIndex]->id }}"
                                    disabled
                                    id="pilihan_{{ $questions[$currentQuestionIndex]->id }}_{{ $option }}"
                                    class="form-check-input" @checked($selectedAnswer[$questions[$currentQuestionIndex]->id] === $option)>
                                <label class="form-check-label fw-medium"
                                    style="{{ $questions[$currentQuestionIndex]->jawaban === $option ? 'color:black !important;' : '' }} {{ $selectedAnswer[$questions[$currentQuestionIndex]->id] === $option ? ($questionStatus[$questions[$currentQuestionIndex]->id] === 'salah' ? 'color:black !important;' : 'color:black !important;') : '' }}"
                                    for="pilihan_{{ $questions[$currentQuestionIndex]->id }}_{{ $option }}">
                                    {{ strtoupper($option) }}.
                                    {{ $questions[$currentQuestionIndex]['pilihan_' . $option] }}
                                </label>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <!-- Navigasi Soal -->
                <div class="d-flex justify-content-between flex-wrap mt-20 align-items-center">
                    <button class="btn btn-secondary btn-sm d-flex align-items-center" wire:click="prevQuestion"
                        {{ $currentQuestionIndex == 0 ? 'disabled' : '' }}>
                        <iconify-icon icon="eva:arrow-ios-forward-fill" class="icon text-xl"
                            style="transform: rotate(-180deg);"></iconify-icon>
                        Sebelumnya
                    </button>

                    <div x-data="{ modalPembahasan: false }">
                        <!-- Button to trigger modal -->
                        <button class="btn btn-outline-success"
                            x-on:click="console.log('Clicked!'); modalPembahasan = true">
                            Pembahasan
                        </button>

                        <!-- Modal Overlay -->
                        <div x-show="modalPembahasan" x-bind:class="{ 'show d-block': modalPembahasan }" x-cloak
                            class="modal fade" style=" background: rgba(0,0,0,0.5);" x-transition.opacity
                            @keydown.escape.window="modalPembahasan = false">

                            <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h5 class="modal-title">Pembahasan soal nomor
                                            {{ array_search($questions[$currentQuestionIndex]->id, $userTryout->question_order) + 1 }}
                                        </h5>
                                        <button type="button" class="btn-close" x-on:click="modalPembahasan = false"
                                            aria-label="Close"></button>
                                    </div>
                                    <!-- Modal Body -->
                                    <div
                                        class="modal-body max-h-612-px overflow-auto d-flex flex-column align-items-center">
                                        @forelse ($questions[$currentQuestionIndex]->image_pembahasan ?? [] as $image)
                                            <img src="{{ route('private.image', ['path' => $image]) }}"
                                                class="img-fluid mb-2" alt="Pembahasan Soal">
                                        @empty
                                            <p class="text-center">Tidak ada pembahasan</p>
                                        @endforelse
                                    </div>
                                    <!-- Modal Footer -->
                                    <div class="modal-footer">
                                        <p>Gunakan tombol <code>&lt;</code> dan <code>&gt;</code> untuk navigasi
                                            pembahasan</p>
                                        <button class="btn btn-secondary"
                                            x-on:click="modalPembahasan = false">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary-600 btn-sm d-flex align-items-center" wire:click="nextQuestion"
                        {{ $currentQuestionIndex == $totalQuestions - 1 ? 'disabled' : '' }}>
                        Selanjutnya <iconify-icon icon="eva:arrow-ios-forward-fill" class="icon text-xl"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>

    </div>
    <!-- Ujian kiri End -->

    <!-- Ujian kanan start -->
    <div class="col-xxl-3">
        {{-- Tambah informasi disini --}}

        <div class="card radius-8 border-0 mb-3">
            <div class="card-body p-24">
                <h6 class="fw-bold text-lg">Hasil</h6>
                <ul class="list-unstyled d-flex flex-column gap-2 mt-2 h-100">
                    <li class="d-flex align-items-center h-100">
                        <span
                            class="badge text-sm fw-semibold px-20 py-9 radius-4 text-success-600 bg-success-100 w-100">
                            {{ $totalBenar }} Benar
                        </span>
                    </li>
                    <li class="d-flex align-items-center h-100">
                        <span class="badge text-sm fw-semibold px-20 py-9 radius-4 text-danger-600 bg-danger-100 w-100">
                            {{ $totalSalah }} Salah
                        </span>
                    </li>
                    <li class="d-flex align-items-center h-100">
                        <span
                            class="badge text-sm fw-semibold px-20 py-9 radius-4 text-neutral-800 bg-neutral-300 w-100">
                            {{ $totalTidakDikerjakan }} Tidak Dikerjakan
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="d-flex justify-content-center w-100 mb-3">
            <a href="{{ route('index') }}" wire:navigate
                class="btn btn-outline-success-800 text-success-600 border-success-600 d-inline-flex align-items-center text-center gap-2 text-sm btn-sm px-8 py-13 w-100">
                <iconify-icon icon="mdi:arrow-left" class="icon text-xl"></iconify-icon> Kembali ke Dashboard
            </a>
        </div>
        <div class="d-flex justify-content-center w-100 mb-3">
            <a wire:click='downloadPembahasan' wire:navigate
                class="btn btn-outline-primary-800 text-primary-600 border-primary-600 d-inline-flex align-items-center text-center gap-2 text-sm btn-sm px-8 py-13 w-100">
                <span wire:loading.remove wire:target="downloadPembahasan">
                    Download Pembahasan
                </span>
                <span wire:loading wire:target="downloadPembahasan">
                    Sedang memproses...
                </span>
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="card radius-8 border-0 mb-3">
            <div class="card-body">
                <div class="grid justify-content-center grid-cols-1 g-2 mb-2">
                    @foreach ($questions as $index => $question)
                        @php
                            $buttonClass =
                                $index === $currentQuestionIndex
                                    ? 'bg-primary-200 text-primary-800'
                                    : match ($questionStatus[$question->id]) {
                                        'benar' => 'bg-success-400',
                                        'salah' => 'bg-danger-400',
                                        'ragu-ragu' => 'bg-warning-900',
                                        default => 'bg-neutral-200 text-neutral-800',
                                    };
                        @endphp
                        <button class="col btn mb-1 shadow-sm min-w-50-px {{ $buttonClass }}"
                            wire:click="jumpToQuestion({{ $index }})">
                            {{ $loop->iteration }}
                        </button>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center align-items-center gap-2 mt-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-8-px rounded-pill bg-success-400"></span>
                        <span class="text-sm fw-semibold text-neutral-800">Benar</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-8-px rounded-pill bg-danger-400"></span>
                        <span class="text-sm fw-semibold text-neutral-800">Salah</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-8-px rounded-pill bg-warning-900"></span>
                        <span class="text-sm fw-semibold text-neutral-800">Ragu-ragu</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-8-px rounded-pill bg-neutral-200"></span>
                        <span class="text-sm fw-semibold text-neutral-800">Tidak Dijawab</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-8-px rounded-pill bg-primary-200"></span>
                        <span class="text-sm fw-semibold text-neutral-800">Sedang dibuka</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@push('style')
    <style>
        .min-w-50-px {
            min-width: 50px;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@push('script')
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'ArrowLeft') {
                Livewire.dispatch('handleKeyPress', {
                    key: 'ArrowLeft'
                });
                console.log('ArrowLeft')
            } else if (event.key === 'ArrowRight') {
                Livewire.dispatch('handleKeyPress', {
                    key: 'ArrowRight'
                });
                console.log('ArrowRight')
            }

            if (document.activeElement && document.activeElement.matches('input[type="radio"]')) {
                event.preventDefault();
            }
        });
    </script>
    <script>
        document.addEventListener('livewire:init', function() {
            Livewire.on('fileDownloadReady', event => {
                const link = document.createElement('a');
                link.href = event.filePath;
                link.download = event.fileName;
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                setTimeout(() => document.body.removeChild(link), 0);
            });
        });
    </script>
@endpush
