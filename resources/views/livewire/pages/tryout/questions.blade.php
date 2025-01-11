<?php
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Cache;

new class extends Component {
    protected $listeners = ['saveTime', 'handleKeyPress', 'pauseTryout', 'endTryout'];
    public $tryoutId;
    public $tryout;
    public $userTryout;
    public $questions;
    public $currentQuestionIndex = 0;
    public $selectedAnswer = [];
    public $questionStatus = [];
    public $isDoubtful = [];
    public $totalQuestions;
    public $timeLeft;

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
            $this->timeLeft = $cacheData['timeLeft'];
            $this->totalQuestions = $this->questions->count();
        } else {
            if (!$this->userTryout->question_order) {
                $questions = \App\Models\SoalTryout::select(['id', 'soal', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'tryout_id'])
                    ->where('tryout_id', $tryoutId)
                    ->pluck('id')
                    ->toArray();
                shuffle($questions);

                $this->userTryout->question_order = $questions;
                $this->userTryout->save();
            }

            $this->questions = \App\Models\SoalTryout::whereIn('id', $this->userTryout->question_order)->get();
            $this->questions = $this->questions
                ->sortBy(function ($question) {
                    return array_search($question->id, $this->userTryout->question_order);
                })
                ->values();

            $this->totalQuestions = $this->questions->count();
            $this->timeLeft = $this->userTryout->waktu ?? $this->tryout->waktu;

            $answers = \App\Models\UserAnswer::where('user_id', auth()->id())->whereIn('soal_id', $this->questions->pluck('id'))->get();

            foreach ($this->questions as $question) {
                $answer = $answers->where('soal_id', $question->id)->first();

                $this->selectedAnswer[$question->id] = $answer->jawaban ?? null;
                $this->isDoubtful[$question->id] = $answer && $answer->status === 'ragu-ragu';
                $this->questionStatus[$question->id] = $answer ? ($answer->status === 'ragu-ragu' ? 'ragu-ragu' : ($answer->jawaban ? 'sudah dijawab' : 'belum dijawab')) : 'belum dijawab';
            }

            $this->cacheData();
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
                'timeLeft' => $this->timeLeft,
            ],
            now()->addMinutes(60),
        );
    }

    public function jumpToQuestion($index)
    {
        if ($index >= 0 && $index < $this->totalQuestions) {
            $this->currentQuestionIndex = $index;
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < $this->totalQuestions - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function prevQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
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

    public function selectAnswer($soalId, $jawaban)
    {
        $this->selectedAnswer[$soalId] = $jawaban;
        $this->questionStatus[$soalId] = 'sudah dijawab';
        $this->isDoubtful[$soalId] = false;

        $this->cacheData();
    }

    public function toggleDoubtful($soalId)
    {
        $this->isDoubtful[$soalId] = !$this->isDoubtful[$soalId];
        $this->questionStatus[$soalId] = $this->isDoubtful[$soalId] ? 'ragu-ragu' : 'sudah dijawab';
        $this->cacheData();
    }

    #[On('saveTime')]
    public function saveTime($remainingMinutes)
    {
        $cacheKey = "tryout_{$this->userTryout->id}";
        $cacheData = Cache::get($cacheKey);
        $cacheData['timeLeft'] = $remainingMinutes;
        Cache::put($cacheKey, $cacheData, now()->addMinutes(60));
    }

    #[On('pause')]
    public function pauseTryout()
    {
        $this->saveToDatabase();
        $this->userTryout->update(['status' => 'paused']);
        return redirect()->route('index')->with('success', 'Tryout dijeda!');
    }

    #[On('end')]
    public function endTryout()
    {
        $this->saveToDatabase();
        $this->userTryout->update(['status' => 'finished']);
        return redirect()->route('index')->with('success', 'Tryout selesai!');
    }

    public function saveToDatabase()
    {
        $cacheKey = "tryout_{$this->userTryout->id}";
        $cacheData = Cache::get($cacheKey);

        foreach ($cacheData['questions'] as $question) {
            \App\Models\UserAnswer::updateOrCreate(['user_id' => auth()->id(), 'soal_id' => $question->id], ['jawaban' => $cacheData['selectedAnswer'][$question->id] ?? null, 'status' => $cacheData['questionStatus'][$question->id] === 'sudah dijawab' ? 'dijawab' : ($cacheData['questionStatus'][$question->id] === 'ragu-ragu' ? 'ragu-ragu' : 'tidak_dijawab')]);
        }

        $remainingMinutes = $cacheData['timeLeft'];
        $this->userTryout->update(['waktu' => $remainingMinutes]);

        Cache::forget($cacheKey);
    }
};
?>

<div class="row">
    <!-- Ujian kiri start -->
    <div class="col-xxl-9">
        <!-- Header Soal -->
        <div class="card radius-8 border-0 mb-3">
            <div class="card-body p-24">
                <p class="text-2xl mb-0">{{ $tryout->nama }}</p>
            </div>
        </div>

        <!-- Countdown Mobile -->
        <div class="card d-xxl-none radius-8 border-0 mb-3">
            <div class="card-body p-24">
                <h6 class="fw-bold text-lg">Sisa Waktu</h6>
                <div class="d-flex justify-content-center fw-bold" style="font-size: 80px;" x-data="{ countdown: @js($timeLeft) * 60 }"
                    x-init="setInterval(() => countdown--, 1000)">
                    <span class="text-primary-600"
                        x-text="`${Math.floor(countdown / 3600)}:${String(Math.floor(countdown / 60) % 60).padStart(2, '0')}:${String(countdown % 60).padStart(2, '0')}`">
                    </span>
                </div>
            </div>
        </div>
        <!-- End Countdown Mobile -->

        <!-- Soal Ujian -->
        <div class="card radius-8 border-0 mb-3">
            <div class="card-body p-24">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-lg">Nomor
                        {{ array_search($questions[$currentQuestionIndex]->id, $userTryout->question_order) + 1 }}</h6>
                    <span
                        class="badge text-sm fw-semibold px-20 py-9 radius-4
                        {{ match ($questionStatus[$questions[$currentQuestionIndex]->id]) {
                            'sudah dijawab' => 'text-primary-600 bg-primary-100',
                            'ragu-ragu' => 'text-warning-600 bg-warning-100',
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
                            <div class="form-check d-flex align-items-center gap-2">
                                <input type="radio" name="pilihan_{{ $questions[$currentQuestionIndex]->id }}"
                                    id="pilihan_{{ $questions[$currentQuestionIndex]->id }}_{{ $option }}"
                                    wire:click="selectAnswer('{{ $questions[$currentQuestionIndex]->id }}', '{{ $option }}')"
                                    class="form-check-input" @checked($selectedAnswer[$questions[$currentQuestionIndex]->id] === $option)>
                                <label class="form-check-label fw-medium text-secondary-light"
                                    for="pilihan_{{ $questions[$currentQuestionIndex]->id }}_{{ $option }}">
                                    {{ strtoupper($option) }}.
                                    {{ $questions[$currentQuestionIndex]['pilihan_' . $option] }}
                                </label>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <!-- Navigasi Soal -->
                <div class="d-flex justify-content-between mt-20 align-items-center">
                    <button class="btn btn-secondary btn-sm d-flex align-items-center" wire:click="prevQuestion"
                        {{ $currentQuestionIndex == 0 ? 'disabled' : '' }}>
                        <iconify-icon icon="eva:arrow-ios-forward-fill" class="icon text-xl"
                            style="transform: rotate(-180deg);"></iconify-icon>
                        Sebelumnya
                    </button>
                    <div class="form-check d-flex align-items-center gap-2">
                        <input type="checkbox" id="raguRagu_{{ $questions[$currentQuestionIndex]->id }}"
                            wire:click="toggleDoubtful('{{ $questions[$currentQuestionIndex]->id }}')"
                            class="form-check-input" @if ($questionStatus[$questions[$currentQuestionIndex]->id] === 'belum dijawab') disabled @endif
                            @checked($isDoubtful[$questions[$currentQuestionIndex]->id])>
                        <label for="raguRagu_{{ $questions[$currentQuestionIndex]->id }}"
                            class="form-check-label fw-medium text-secondary-light">
                            Ragu-ragu
                        </label>
                    </div>

                    @if ($currentQuestionIndex == $totalQuestions - 1)
                        <button class="btn btn-success btn-sm d-flex align-items-center"
                            @click="actionType = 'end'; Swal.fire({
                                title: 'Konfirmasi',
                                text: 'Apakah Anda yakin ingin menyelesaikan tryout ini? Pastikan anda telah menjawab semua soal sebelum menyelesaikan.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $wire.dispatchSelf('endTryout');
                                }
                            })">
                            Selesai <iconify-icon icon="eva:checkmark-fill" class="icon text-xl"></iconify-icon>
                        </button>
                    @else
                        <button class="btn btn-primary-600 btn-sm d-flex align-items-center" wire:click="nextQuestion">
                            Selanjutnya <iconify-icon icon="eva:arrow-ios-forward-fill"
                                class="icon text-xl"></iconify-icon>
                        </button>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <!-- Ujian kiri End -->

    <!-- Ujian kanan start -->
    <div class="col-xxl-3">
        <!-- Countdown Desktop -->
        <div class="card d-none d-xxl-block radius-8 border-0 mb-3">
            <div class="card-body p-24">
                <h6 class="fw-bold text-lg">Sisa Waktu</h6>
                <div class="d-flex justify-content-center fw-bold" style="font-size: 80px;" x-data="{
                    countdown: @js($timeLeft) * 60,
                    saveTime() { $wire.saveTime(Math.floor(this.countdown / 60)); }
                }"
                    x-init="setInterval(() => {
                        countdown--;
                        if (countdown >= 0 && countdown % 60 === 0) saveTime();
                        if (countdown <= 0) { // Ubah dari === menjadi <=
                            clearInterval(this.interval); // Hentikan timer
                            Swal.fire({
                                title: 'Waktu Habis!',
                                text: 'Tryout telah selesai otomatis. Hasil Anda akan disimpan.',
                                icon: 'info',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false,
                            }).then(() => {
                                $wire.dispatchSelf('endTryout'); // Kirim event untuk menyimpan hasil
                            });
                        }
                    }, 1000)">
                    <span class="text-primary-600"
                        x-text="`${Math.floor(countdown / 3600)}:${String(Math.floor(countdown / 60) % 60).padStart(2, '0')}:${String(countdown % 60).padStart(2, '0')}`">
                    </span>
                </div>
            </div>
        </div>

        <!-- Button -->
        <div x-data="{ actionType: null }" class="mb-10">
            <!-- Tombol Jeda -->
            <button class="btn col btn-warning"
                @click="actionType = 'pause'; Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menjeda tryout ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatchSelf('pauseTryout');
                }
            })">
                Jeda
            </button>

            <!-- Tombol Selesai -->
            <button class="btn col btn-success"
                @click="actionType = 'end'; Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menyelesaikan tryout ini? Pastikan anda telah menjawab semua soal sebelum menyelesaikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatchSelf('endTryout');
                }
            })">
                Selesai
            </button>
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
                                        'sudah dijawab' => 'bg-primary-600',
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
                        <span class="w-12-px h-8-px rounded-pill bg-primary-600"></span>
                        <span class="text-sm fw-semibold text-neutral-800">Sudah dijawab</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-8-px rounded-pill bg-warning-900"></span>
                        <span class="text-sm fw-semibold text-neutral-800">Ragu-ragu</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-8-px rounded-pill bg-neutral-200"></span>
                        <span class="text-sm fw-semibold text-neutral-800">Belum dijawab</span>
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
@endpush
@push('script')
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'ArrowLeft') {
                Livewire.dispatch('handleKeyPress', {
                    key: 'ArrowLeft'
                });
            } else if (event.key === 'ArrowRight') {
                Livewire.dispatch('handleKeyPress', {
                    key: 'ArrowRight'
                });
            }

            if (document.activeElement && document.activeElement.matches('input[type="radio"]')) {
                event.preventDefault();
            }
        });
    </script>
@endpush
