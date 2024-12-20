<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $tryout->nama }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
</head>

<body>

    <div class="wrapper">
        <div class="row mb-3">
            <div class="col-12 col-md-9">
                <div class="card h-100">
                    <div class="card-body">
                        <p class="mb-1 primary-text">Tryout</p>
                        <h6>{{ $tryout->nama }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-3 d-none d-md-block">
                <div class="card">
                    <div class="card-body">
                        <p class="mb-1 primary-text">Sisa Waktu</p>
                        <div class="row mx-2 text-center justify-content-between">
                            <div class="col-3 align-items-center primary-background rounded">
                                <h3 id="hours" class="mb-1">00</h3>
                                <p class="mb-1" style="font-size: 10px">Jam</p>
                            </div>
                            <div class="col-3 align-items-center primary-background rounded">
                                <h3 id="minutes" class="mb-1">00</h3>
                                <p class="mb-1" style="font-size: 10px">Menit</p>
                            </div>
                            <div class="col-3 align-items-center primary-background rounded">
                                <h3 id="seconds" class="mb-1">00</h3>
                                <p class="mb-1" style="font-size: 10px">Detik</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 col-md-9">
                <div class="card h-100" id="card_soal">

                </div>
            </div>
            <div class="col-3 d-none d-md-flex flex-column gap-3">
                <div class="card">
                    <div class="card-body">
                        <p class="mb-1 primary-text">Daftar Soal</p>
                        <div class="grid-soal">
                            @foreach($nomor as $i)
                            <div class="soal rounded d-flex align-items-center justify-content-center"
                                data-nomor="{{ $i['nomor'] }}" style="cursor: pointer;">
                                <p class="mb-0 fw-bold">{{ $i['nomor'] }}</p>
                                <input type="hidden" name="id_soal" value="{{ $i['id'] }}">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <button class="btn alternative-background alternative-text rounded-pill">Akhiri Ujian</button>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>


        <script>
            function initializeCountdown() {
                    const tryoutId = '{{ $tryout->id }}';

                    // Ambil endTime dari server
                    fetchEndTimeFromServer(tryoutId).then((endTimeFromServer) => {
                        let endTime = endTimeFromServer ? new Date(endTimeFromServer) : calculateEndTime();

                        if (!endTimeFromServer) {
                            sendEndTimeToServer(tryoutId, endTime);
                        }

                        startCountdown(endTime);
                    });
                }

                function calculateEndTime() {
                    const durationInMinutes = parseInt('{{ $tryout->waktu }}');
                    return new Date(Date.now() + durationInMinutes * 60 * 1000); // Hitung endTime
                }

                function fetchEndTimeFromServer(tryoutId) {
                    return fetch("{{ route('tryouts.getEndTime') }}?id_tryout=" + tryoutId, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.end_time === null) {
                            return null; // Handle the case where end_time is null
                        }
                        return data.end_time; // Kembalikan waktu dalam format string
                    })
                    .catch((error) => {
                        console.error("Error fetching end time:", error);
                        return null; // Jika gagal mengambil data, kembalikan null
                    });
                }

                function sendEndTimeToServer(tryoutId, endTime) {
                    fetch("{{ route('tryouts.saveEndTime') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            id_tryout: tryoutId,
                            end_time: endTime.toISOString(), // Kirim waktu dalam format ISO
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.status === 'success') {
                            console.log("End time saved successfully");
                        } else {
                            console.error("Error saving end time");
                        }
                    })
                    .catch((error) => {
                        console.error("Error sending end time to server:", error);
                    });
                }

                function startCountdown(endTime) {
                    const x = setInterval(() => {
                        const now = Date.now();
                        const remainingTime = endTime.getTime() - now;

                        if (remainingTime > 0) {
                            const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

                            document.getElementById("hours").innerHTML = hours.toString().padStart(2, '0');
                            document.getElementById("minutes").innerHTML = minutes.toString().padStart(2, '0');
                            document.getElementById("seconds").innerHTML = seconds.toString().padStart(2, '0');
                        } else {
                            clearInterval(x);
                            document.getElementById("hours").innerHTML = "00";
                            document.getElementById("minutes").innerHTML = "00";
                            document.getElementById("seconds").innerHTML = "00";
                        }
                    }, 1000);
                }

                $(document).ready(() => {
                    initializeCountdown();
                });
        </script>



        <script>
            // Variabel global
            $(document).ready(function () {
            // Cek apakah ada ID soal terakhir di localStorage
            var lastQuestionId = localStorage.getItem('lastQuestionId');
            if (lastQuestionId) {
                // Jika ada, ambil soal tersebut
                getQuestion(lastQuestionId);
            } else {
                var firstQuestionId = $('.soal[data-nomor="1"]').find('input[name="id_soal"]').val();

                getQuestion(firstQuestionId);
            }

            // Event listener untuk klik soal
            $(document).on('click', '.soal', function () {
                var id_soal = $(this).find('input[name="id_soal"]').val();

                // Simpan ID soal terakhir ke localStorage
                localStorage.setItem('lastQuestionId', id_soal);

                // Ambil soal berdasarkan ID
                getQuestion(id_soal);
            });
        });


        // Fungsi untuk mengambil soal
        function getQuestion(id_soal) {
            $('#card_soal').empty();
            $('#card_soal').html('<div class="d-flex justify-content-center align-items-center" style="height: 100%;"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            $.ajax({
                url: '{{ route("soal") }}',
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id_soal': id_soal
                },
                success: function (response) {
                    if(response.status == 'success'){
                        jawaban_user = response.jawaban_user.jawaban;
                        response = response.soal;
                    $('.soal').not('.soal[data-nomor="' + response.nomor + '"]').removeClass('alternative-background');
                    $('.soal[data-nomor="' + response.nomor + '"]').addClass('alternative-background');
                    let card_soal_tryouts = `
                        <div class="card-body">
                            <p class="mb-1 primary-text">Nomor Soal <span id="nomor_tryout">${response.nomor}</span></p>
                            <p id="soal_tryout">${response.soal}</p>
                            <div class="row mb-3">
                                <div class="d-flex flex-column gap-2">
                                    <div class="col-12 d-flex">
                                        <div class="form-check d-flex ps-0 w-100">
                                            <input class="form-check-input visually-hidden" type="radio" name="jawaban1"
                                                id="a" value="a">
                                            <label class="${jawaban_user == 'a' ? 'answer choices' : 'answer'} d-flex align-items-start gap-2 px-3 py-2 rounded w-100" style="cursor: pointer;" onclick="setAnswer('a')"
                                                for="a"><span class="fw-bold">A</span><span id="pilihan_a">${response.pilihan_a}</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex">
                                        <div class="form-check d-flex ps-0 w-100">
                                            <input class="form-check-input visually-hidden" type="radio" name="jawaban1"
                                                id="b" value="b">
                                            <label class="${jawaban_user == 'b' ? 'answer choices' : 'answer'} d-flex align-items-start gap-2 px-3 py-2 rounded w-100" style="cursor: pointer;" onclick="setAnswer('b')"
                                                for="b"><span class="fw-bold">B</span><span id="pilihan_b">${response.pilihan_b}</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex">
                                        <div class="form-check d-flex ps-0 w-100">
                                            <input class="form-check-input visually-hidden" type="radio" name="jawaban1"
                                                id="c" value="c">
                                            <label class="${jawaban_user == 'c' ? 'answer choices' : 'answer'} d-flex align-items-start gap-2 px-3 py-2 rounded w-100" style="cursor: pointer;" onclick="setAnswer('c')"
                                                for="c"><span class="fw-bold">C</span><span id="pilihan_c">${response.pilihan_c}</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex">
                                        <div class="form-check d-flex ps-0 w-100">
                                            <input class="form-check-input visually-hidden" type="radio" name="jawaban1"
                                                id="d" value="d">
                                            <label class="${jawaban_user == 'd' ? 'answer choices' : 'answer'} d-flex align-items-start gap-2 px-3 py-2 rounded w-100" style="cursor: pointer;" onclick="setAnswer('d')"
                                                for="d"><span class="fw-bold">D</span><span id="pilihan_d">${response.pilihan_d}</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex">
                                        <div class="form-check d-flex ps-0 w-100">
                                            <input class="form-check-input visually-hidden" type="radio" name="jawaban1"
                                                id="e" value="e">
                                            <label class="${jawaban_user == 'e' ? 'answer choices' : 'answer'} d-flex align-items-start gap-2 px-3 py-2 rounded w-100" style="cursor: pointer;" onclick="setAnswer('e')"
                                                for="e"><span class="fw-bold">E</span><span id="pilihan_e">${response.pilihan_e}</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-lg-between">
                                <button ${response.nomor == 1 ? 'disabled' : ''} class="btn primary-button rounded-pill">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-chevron-left">
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>
                                    Sebelumnya</button>
                                <button class="btn primary-button rounded-pill">Selanjutnya
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-chevron-right">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        `;
                    $('#card_soal').html(card_soal_tryouts);
                }else if(response.status === 'error'){
                    $('#card_soal').html(response.message)
                }
                },
                error: function () {
                    alert('Gagal memuat soal. Silakan coba lagi.');
                }
            });
        }

        function setAnswer(jawaban) {
            let id_soal = localStorage.getItem('lastQuestionId');
            document.querySelectorAll('.answer').forEach(label => {
                label.classList.remove('choices');
            });


            // Tambahkan class 'choices' ke label yang diklik
            const selectedLabel = document.querySelector(`label[for="${jawaban}"]`);
            if (selectedLabel) {
                selectedLabel.classList.add('choices');
            }
            $.ajax({
                url: `{{ route('tryouts.setAnswer') }}`,
                method: 'POST',
                data: {
                    id_soal: id_soal,
                    jawaban: jawaban,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('.soal').each(function() {
                        if ($(this).data('nomor').toString() === $('#nomor_tryout').text()) {
                            console.log($(this).data('nomor'));
                            $(this).removeClass('alternate-background').addClass('primary-background');
                        }
                    });
                },
                error: function () {
                    alert('Gagal memasukkan jawaban. Silakan coba lagi.');
                }
            });
        }
        </script>
</body>

</html>
