@extends('layouts.index')

@section('title', 'Try Out')
@section('content')
<div class="row justify-content-between mb-3">
    <div class="col-12 col-md-6 d-flex align-items-center gap-3 ">
        <h5>Tryout</h5>
        <select name="batch" class="form-select" id="batch_Select">
            @foreach ($batches as $batch)
            <option value="{{ $batch->id }}">
                {{ $batch->nama }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-md-6 text-end">
        <p>
            {{ \Carbon\Carbon::now()->locale('id_ID')->translatedFormat('l, j F Y') }}
        </p>
    </div>
</div>
<div class="row mb-3">
    <div class="col-12 col-md-8 order-2 order-md-1">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="fw-bold">Try Out Paket Hemat</h6>
            </div>
            <div class="card-body">
                <div class="d-flex gap-3 align-items-center">
                    <p class="mb-0">Try Out Belum Dikerjakan</p>
                    <div class="fw-bold alert alert-warning p-2 my-2 text-warning" style="font-size: 10px;"
                        role="alert">
                        <span id="text_belum_dikerjakan"></span> Tryout
                    </div>
                </div>
                <div class="mx-1">
                    <div class="row rounded p-2" style="background-color: #f5f5f5">
                        <div class="col-4">Tryout</div>
                        <div class="col-4">Tanggal</div>
                        <div class="col-4"></div>
                    </div>
                    <div style="max-height: 230px; overflow-y: auto; overflow-y: scroll; scrollbar-width:none;"
                        id="tryout_belum_dikerjakan">


                    </div>

                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex gap-3 align-items-center">
                    <p class="mb-0">Try Out Sudah Dikerjakan</p>
                    <div class="fw-bold alert alert-warning p-2 my-2 text-warning" style="font-size: 10px;"
                        role="alert">
                        <span id="text_dikerjakan"></span> Tryout
                    </div>
                </div>
                <div class="mx-1">
                    <div class="row rounded p-2" style="background-color: #f5f5f5">
                        <div class="col-4">Tryout</div>
                        <div class="col-4">Tanggal</div>
                        <div class="col-4"></div>
                    </div>
                    <div style="max-height: 230px; overflow-y: auto; overflow-y: scroll; scrollbar-width:none;"
                        id="tryout_sudah_dikerjakan">

                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 order-1 order-md-2 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="fw-bold">Jadwal Ujian</h6>
            </div>
            <div class="card-body">
                <div class="row text-center justify-content-evenly gap-1">
                    <div style="max-width: 70px;" class="col-3 card p-1 border-0 alert alert-warning text-warning">
                        <p id="days" class="m-1 fw-bold" style="font-size: 20px;">00</p>
                        <p class="m-0">Hari</p>
                    </div>
                    <div style="max-width: 70px;" class="col-3 card p-1 border-0 alert alert-light">
                        <p id="hours" class="m-1 fw-bold" style="font-size: 20px;">00</p>
                        <p class="m-0">Jam</p>
                    </div>
                    <div style="max-width: 70px;" class="col-3 card p-1 border-0 alert alert-light">
                        <p id="minutes" class="m-1 fw-bold" style="font-size: 20px;">00</p>
                        <p class="m-0">Menit</p>
                    </div>
                    <div style="max-width: 70px;" class="col-3 card p-1 border-0 alert alert-light">
                        <p id="seconds" class="m-1 fw-bold" style="font-size: 20px;">00</p>
                        <p class="m-0">Detik</p>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center bg-white border-0">
                <p id="batch_jadwal_ujian"></p>
            </div>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6>Grafik Perkembangan</h6>
            </div>
            <div class="card-body">
                <div id="chart">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
            $(document).on('change', '#batch_Select', function() {
                var batch = $(this).val();
                get_tryouts_data(batch);
            })

            var batch = $('#batch_Select').val();
            get_tryouts_data(batch);
        });

        function get_tryouts_data(batch) {
            showLoading();
            $.ajax({
                type: "POST",
                url: "{{ route('tryouts.getTryouts') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    batch: batch
                },
                success: function(response) {
                    if (response.status === 'success') {
                        startCountdown(response.batch_end_date);
                        $('#batch_jadwal_ujian').text(new Date(response.batch_end_date).toLocaleString(
                        'id-ID', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            weekday: 'long'
                        }));
                        $('#tryout_belum_dikerjakan').empty();
                        $('#tryout_sudah_dikerjakan').empty();

                        if (response.data.not_started && response.data.not_started.length > 0) {
                            $('#text_belum_dikerjakan').text(response.data.not_started.length);
                            response.data.not_started.forEach(function(tryout) {
                                $('#tryout_belum_dikerjakan').append(
                                    get_container_tryouts(tryout.nama, tryout.tanggal, tryout.id,
                                        tryout.status)
                                );
                            });
                        } else {
                            $('#text_belum_dikerjakan').text(0);
                            $('#tryout_belum_dikerjakan').append(`
                                    <div class="row p-2" style="font-size: 12px;">
                                        <div class="col-12 text-center">Belum ada tryout</div>
                                    </div>
                                `);
                        }

                        if ((response.data.finished && response.data.finished.length > 0) || (response.data.started && response.data.started.length > 0)) {
                            let total = 0;
                            if (response.data.started) {
                                total += response.data.started.length;
                            }
                            if (response.data.finished) {
                                total += response.data.finished.length;
                            }
                            $('#text_dikerjakan').text(total);
                            if(response.data.started){
                                response.data.started.forEach(function(tryout) {
                                    $('#tryout_sudah_dikerjakan').append(
                                        get_container_tryouts(tryout.nama, tryout.tanggal, tryout.id,
                                            tryout.status)
                                    );
                                });
                            }else if(response.data.finished){
                                response.data.finished.forEach(function(tryout) {
                                    $('#tryout_sudah_dikerjakan').append(
                                        get_container_tryouts(tryout.nama, tryout.tanggal, tryout.id,
                                            tryout.status)
                                    );
                                });
                            }
                        } else {
                            $('#text_dikerjakan').text(0);
                            $('#tryout_sudah_dikerjakan').append(`
                                    <div class="row p-2" style="font-size: 12px;">
                                        <div class="col-12 text-center">Belum ada tryout yang dikerjakan</div>
                                    </div>
                                `);
                        }

                    }
                },
                complete: function() {
                    hideLoading();
                }
            });
        }



        function get_container_tryouts(nama, tanggal, id, status) {
            // Berikan default jika tanggal null
            tanggal = tanggal ? tanggal : 'Tanggal tidak tersedia';

            let html = `
                <div class="row p-2 align-items-center" style="font-size: 12px">
                    <div class="col-4">${nama}</div>
                    <div class="col-4">${tanggal}</div>
                    <div class="col-4">
                        <a href="${status === 'finished' ? '/tryout/hasil/' + id : '/tryout/show/' + id}"
                        class="btn btn-sm btn-primary">
                            ${status === 'not_started' ? 'Kerjakan' :
                            (status === 'paused' ? 'Lanjutkan' : (status === 'finished' ? 'Hasil' : (status === 'started' ? 'Lanjutkan' : 'Kerjakan'))) }
                        </a>
                    </div>
                </div>
            `;
            return html;
        }


        function startCountdown(date) {
            const countDownDate = new Date(date).getTime();
            setInterval(function() {
                const now = new Date().getTime();
                const distance = countDownDate - now;

                // Hitung hari, jam, menit, dan detik
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Tampilkan hasil dalam elemen HTML dengan ID
                document.getElementById("days").innerHTML = days.toString().padStart(2, '0');
                document.getElementById("hours").innerHTML = hours.toString().padStart(2, '0');
                document.getElementById("minutes").innerHTML = minutes.toString().padStart(2, '0');
                document.getElementById("seconds").innerHTML = seconds.toString().padStart(2, '0');

                // Jika countdown selesai
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    document.getElementById("days").innerHTML = "00";
                    document.getElementById("hours").innerHTML = "00";
                    document.getElementById("minutes").innerHTML = "00";
                    document.getElementById("seconds").innerHTML = "00";

                    // Tambahkan tindakan jika waktu habis
                    alert("Countdown selesai!");
                }
            }, 1000);
        }

</script>
<script>
    var options = {
            chart: {
                height: 280,
                type: "area"
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: "Series 1",
                data: [45, 52, 38, 45, 19, 23, 2]
            }],
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: [
                    "01 Jan",
                    "02 Jan",
                    "03 Jan",
                    "04 Jan",
                    "05 Jan",
                    "06 Jan",
                    "07 Jan"
                ]
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);

        chart.render();
</script>
@endsection