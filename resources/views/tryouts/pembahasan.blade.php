@extends('layouts.index')

@section('title', 'Try Out')
@section('style')
<style>
    #question-grid .dijawab {
        background-color: #CA0000FF !important;
        color: #FFFFFFFF !important;
    }

    #question-grid .belum_dijawab {
        background-color: #E6E6E6FF !important;
        color: #000000FF !important;
    }

    #question-grid .ragu-ragu {
        background-color: #FFD700FF !important;
        color: #000000FF !important;
    }

    #question-grid .active {
        background-color: #FFF19EFF;
        border: solid 2px #FFB300FF !important;
        color: #000000FF;
    }
</style>
@endsection
@section('content')
<div class="row mb-3">
    <div class="col-12 col-md-9 mb-3">
        <div id="question-grid-header" class="d-flex gap-3 rounded mb-3 p-2"
            style="background-color: rgb(239, 239, 239);">
            <button id="view_persoal" class="btn grid_ text-secondary btn-light">Tampilkan persoal</button>
            <button id="view_semua_soal" class="btn grid_.dijawab text-secondary">Tampilkan semua soal</button>
        </div>
        <div class="row">
            <div class="col-12" id="list_soal">

            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <button class="btn btn-primary w-100 mb-2 py-2"
            onclick="window.location.href='{{ route('tryouts.hasil.index', ['id' => $userTryout->tryout_id]) }}'">Kembali
            Ke Dashboard</button>
        <button class="btn btn-outline-primary w-100 mb-2 py-2" id="">Download Soal</button>
        <div class="card border-0 mb-2 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <small class="mb-0">Nilai Anda</small>
                    </div>
                    <div class="col-6 flex justify-content-end text-end fw-bold">
                        {{ $userTryout->nilai}}
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm mb-2">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <small class="mb-0" style="font-size: 12px;">Jumlah jawaban benar</small>
                    <p class="fw-bold mb-0"><span id="jumlah_benar"></span>/<span id="jumlah_soal"></span></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-3 card-grid">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div id="question-grid" class=""
                    style="display: grid; grid-template-columns: repeat(20, minmax(0, 1fr)); gap: 0.5rem;">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

<script>
    $(document).ready(function() {
                let questions = [];
                let currentQuestionIndex = 0;

                async function getPembahasan() {
                    showLoading();
                    let id_tryout = '{{ $userTryout->tryout_id }}';
                    const response = await $.ajax({
                        type: "POST",
                        url: "{{ route('tryout.getPembahasan') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id_tryout: id_tryout
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                questions = response.data;
                            }
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });

                }

                function displayQuestion(index) {
                    console.log(questions)
                    const question = questions[index];
                    $("#list_soal").html(`
                    <div class="card">
                    <div class="card-header p-2 bg-white d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold">Nomor <span id="nomor_soal">${question.nomor}</span></h6>
                        <div id="status_soal-${index}" class="alert ${
                            question.status_jawaban  === 'benar'
                                ? 'alert-info'
                                : 'alert-danger'
                        } py-1 px-2 my-2" role="alert">
                            ${question.status_jawaban === 'benar'
                                ? 'Benar'
                                : 'Salah'}
                        </div>


                    </div>
                    <div class="card-body p-2 bg-white">
                        <small style="font-size: 14px;" id="soal_tryout">${question.soal}</small>
                        <div class="form-check ${question.jawaban === 'a' ? 'bg-primary-subtle' : ''}  ${question.user_answer?.jawaban === 'a' && question.status_jawaban === 'salah' ? 'bg-danger-subtle' : ''}">
                            <input class="form-check-input setAnswer" disabled data-id="${question.id}" data-pilihan="a" data-index="${index}" ${question.user_answer?.jawaban != null && question.user_answer?.jawaban == 'a' ? 'checked' : ''} type="radio" name="answer-${index}-" id="answer-${index}-A" value="a">
                            <label class="form-check-label" data-pilihan="a" data-id="${question.id}" data-index="${index}"  style="font-size: 14px;" for="answer-${index}-A">
                                A. ${question.pilihan_a}
                            </label>
                        </div>
                        <div class="form-check ${question.jawaban === 'b' ? 'bg-primary-subtle' : ''}  ${question.user_answer?.jawaban === 'b' && question.status_jawaban === 'salah' ? 'bg-danger-subtle' : ''}">
                            <input class="form-check-input setAnswer" disabled data-id="${question.id}" data-pilihan="b" data-index="${index}" ${question.user_answer?.jawaban != null && question.user_answer?.jawaban == 'b' ? 'checked' : ''} type="radio" name="answer-${index}-" id="answer-${index}-B" value="b">
                            <label class="form-check-label" data-pilihan="b" data-id="${question.id}" data-index="${index}"  style="font-size: 14px;" for="answer-${index}-B">
                                B. ${question.pilihan_b}
                            </label>
                        </div>
                        <div class="form-check ${question.jawaban === 'c' ? 'bg-primary-subtle' : ''}  ${question.user_answer?.jawaban === 'c' && question.status_jawaban === 'salah' ? 'bg-danger-subtle' : ''}">
                            <input class="form-check-input setAnswer" disabled data-id="${question.id}" data-pilihan="c" data-index="${index}" ${question.user_answer?.jawaban != null && question.user_answer?.jawaban == 'c' ? 'checked' : ''} type="radio" name="answer-${index}-" id="answer-${index}-C" value="c">
                            <label class="form-check-label" data-pilihan="c" data-id="${question.id}" data-index="${index}"  style="font-size: 14px;" for="answer-${index}-C">
                                C. ${question.pilihan_c}
                            </label>
                        </div>
                        <div class="form-check ${question.jawaban === 'd' ? 'bg-primary-subtle' : ''}  ${question.user_answer?.jawaban === 'd' && question.status_jawaban === 'salah' ? 'bg-danger-subtle' : ''}">
                            <input class="form-check-input setAnswer" disabled data-id="${question.id}" data-pilihan="d" data-index="${index}" ${question.user_answer?.jawaban != null && question.user_answer?.jawaban == 'd' ? 'checked' : ''} type="radio" name="answer-${index}-" id="answer-${index}-D" value="d">
                            <label class="form-check-label" data-pilihan="d" data-id="${question.id}" data-index="${index}"  style="font-size: 14px;" for="answer-${index}-D">
                                D. ${question.pilihan_d}
                            </label>
                        </div>
                        <div class="form-check ${question.jawaban === 'e' ? 'bg-primary-subtle' : ''}  ${question.user_answer?.jawaban === 'e' && question.status_jawaban === 'salah' ? 'bg-danger-subtle' : ''}">
                            <input class="form-check-input setAnswer" disabled data-id="${question.id}" data-pilihan="e" data-index="${index}" ${question.user_answer?.jawaban != null && question.user_answer?.jawaban == 'e' ? 'checked' : ''} type="radio" name="answer-${index}-" id="answer-${index}-E" value="e">
                            <label class="form-check-label" data-pilihan="e" data-id="${question.id}" data-index="${index}"  style="font-size: 14px;" for="answer-${index}-E">
                                E. ${question.pilihan_e}
                            </label>
                        </div>
                    </div>
                    <div class="card-footer p-2 border-0 bg-white px-4">
                    <div class="row justify-content-between mb-3">
                        <div class="col-6">
                            <button id="prev-btn" class="btn btn-primary" ${index === 0 ? 'disabled' : ''}>Sebelumnya</button>
                        </div>
                        <div id="next-btn" class="col-6 d-flex justify-content-end">
                        ${index == questions.length - 1 ? '<button class="btn btn-primary btn_selesai">Selesai</button>' : '<button class="btn btn-primary">Selanjutnya</button>'}
                        </div>
                    </div>
                </div>
                </div>
                `);

                    // Update tombol navigasi
                    $("#prev-btn").prop("disabled", index === 0);
                    $("#next-btn").prop("disabled", index === questions.length - 1);

                    $(".question-number").removeClass("active");
                    $(`#question-number-${index}`).addClass("active");


                }




                function populateQuestionGrid() {
                    $("#question-grid").html(
                        questions.map((question, index) => `
                        <div id="question-number-${index}" style="cursor: pointer;" class="border question-number ${question.status_jawaban === 'benar' ? 'bg-primary-subtle'  : 'bg-danger-subtle'}  rounded p-2 text-center">
                            ${question.nomor}
                        </div>
                    `).join("")
                    );
                }

                $(document).on('click', "#prev-btn", function() {
                    if (currentQuestionIndex > 0) {
                        currentQuestionIndex--;
                        displayQuestion(currentQuestionIndex);
                    }
                });

                $(document).on('click', "#next-btn", function() {
                    if (currentQuestionIndex < questions.length - 1) {
                        currentQuestionIndex++;
                        displayQuestion(currentQuestionIndex);
                    }
                });


                $(document).on('click', ".question-number", function() {
                    const index = $(this).text() - 1;
                    currentQuestionIndex = index;
                    displayQuestion(currentQuestionIndex);
                });

                $(document).on('click', '#view_persoal', function () {
                    $(this).addClass('btn-light');
                    let containerSoal = $('#list_soal');
                    $('#view_semua_soal').removeClass('btn-light');
                    $('.card-grid').removeClass('d-none');
                    containerSoal.empty();
                    populateQuestionGrid();
                    displayQuestion(currentQuestionIndex);
                })

                $(document).on('click', '#view_semua_soal', function() {
                    $(this).addClass('btn-light');
                    let containerSoal = $('#list_soal');
                    $('#view_persoal').removeClass('btn-light');
                    $('.card-grid').addClass('d-none');
                    containerSoal.empty();
                    let html = ``;
                    $.each(questions, function (index, question) { 
                        html += `
                            <div class="card mb-3" style="overflow: hidden;">
                                <div class="card-header p-2 bg-white d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold">Nomor <span id="nomor_soal">${question.nomor}</span></h6>
                                    <div id="status_soal-${index}" class="alert ${
                                        question.status_jawaban  === 'benar'
                                            ? 'alert-info'
                                            : 'alert-danger'
                                    } py-1 px-2 my-2" role="alert">
                                        ${question.status_jawaban === 'benar'
                                            ? 'Benar'
                                            : 'Salah'}
                                    </div>


                                </div>
                                <div class="card-body p-2 bg-white">
                                    <small style="font-size: 14px;" id="soal_tryout">${question.soal}</small>
                                    <div class="form-check ${question.jawaban === 'a' ? 'bg-primary-subtle' : ''}  ${question.user_answer?.jawaban === 'a' && question.status_jawaban === 'salah' ? 'bg-danger-subtle' : ''}">
                                        <input class="form-check-input setAnswer" disabled data-id="${question.id}" data-pilihan="a" data-index="${index}" ${question.user_answer?.jawaban != null && question.user_answer?.jawaban == 'a' ? 'checked' : ''} type="radio" name="answer-${index}-" id="answer-${index}-A" value="a">
                                        <label class="form-check-label" data-pilihan="a" data-id="${question.id}" data-index="${index}"  style="font-size: 14px;" for="answer-${index}-A">
                                            A. ${question.pilihan_a}
                                        </label>
                                    </div>
                                    <div class="form-check ${question.jawaban === 'b' ? 'bg-primary-subtle' : ''}  ${question.user_answer?.jawaban === 'b' && question.status_jawaban === 'salah' ? 'bg-danger-subtle' : ''}">
                                        <input class="form-check-input setAnswer" disabled data-id="${question.id}" data-pilihan="b" data-index="${index}" ${question.user_answer?.jawaban != null && question.user_answer?.jawaban == 'b' ? 'checked' : ''} type="radio" name="answer-${index}-" id="answer-${index}-B" value="b">
                                        <label class="form-check-label" data-pilihan="b" data-id="${question.id}" data-index="${index}"  style="font-size: 14px;" for="answer-${index}-B">
                                            B. ${question.pilihan_b}
                                        </label>
                                    </div>
                                    <div class="form-check ${question.jawaban === 'c' ? 'bg-primary-subtle' : ''}  ${question.user_answer?.jawaban === 'c' && question.status_jawaban === 'salah' ? 'bg-danger-subtle' : ''}">
                                        <input class="form-check-input setAnswer" disabled data-id="${question.id}" data-pilihan="c" data-index="${index}" ${question.user_answer?.jawaban != null && question.user_answer?.jawaban == 'c' ? 'checked' : ''} type="radio" name="answer-${index}-" id="answer-${index}-C" value="c">
                                        <label class="form-check-label" data-pilihan="c" data-id="${question.id}" data-index="${index}"  style="font-size: 14px;" for="answer-${index}-C">
                                            C. ${question.pilihan_c}
                                        </label>
                                    </div>
                                    <div class="form-check ${question.jawaban === 'd' ? 'bg-primary-subtle' : ''}  ${question.user_answer?.jawaban === 'd' && question.status_jawaban === 'salah' ? 'bg-danger-subtle' : ''}">
                                        <input class="form-check-input setAnswer" disabled data-id="${question.id}" data-pilihan="d" data-index="${index}" ${question.user_answer?.jawaban != null && question.user_answer?.jawaban == 'd' ? 'checked' : ''} type="radio" name="answer-${index}-" id="answer-${index}-D" value="d">
                                        <label class="form-check-label" data-pilihan="d" data-id="${question.id}" data-index="${index}"  style="font-size: 14px;" for="answer-${index}-D">
                                            D. ${question.pilihan_d}
                                        </label>
                                    </div>
                                    <div class="form-check ${question.jawaban === 'e' ? 'bg-primary-subtle' : ''}  ${question.user_answer?.jawaban === 'e' && question.status_jawaban === 'salah' ? 'bg-danger-subtle' : ''}">
                                        <input class="form-check-input setAnswer" disabled data-id="${question.id}" data-pilihan="e" data-index="${index}" ${question.user_answer?.jawaban != null && question.user_answer?.jawaban == 'e' ? 'checked' : ''} type="radio" name="answer-${index}-" id="answer-${index}-E" value="e">
                                        <label class="form-check-label" data-pilihan="e" data-id="${question.id}" data-index="${index}"  style="font-size: 14px;" for="answer-${index}-E">
                                            E. ${question.pilihan_e}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    containerSoal.html(html);
                });


                getPembahasan().then(() => {
                    $('#jumlah_soal').text(questions.length);
                    $('#jumlah_benar').text(questions.filter(question => question.status_jawaban ===
                        'benar').length);
                    populateQuestionGrid();
                    displayQuestion(currentQuestionIndex);
                });

            });




</script>
@endsection