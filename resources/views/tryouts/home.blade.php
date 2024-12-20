@extends('layouts.index')
@section('title', 'Home - ' . setting('general.app_name') ?? 'Laravel')
@section('content')
<div class="row align-items-start mb-4">
    <div class="col-12 col-md-6">
        <h3 class="mb-0 d-flex align-items-center">Halo, {{ auth()->user()->name }} <span class="fs-6">👋</span></h3>
        <small class="text-muted">Mulailah mengerjakan tryoutmu</small class="text-muted">
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <p>
            {{ \Carbon\Carbon::now()->locale('id_ID')->translatedFormat('l, j F Y') }}
        </p>
    </div>
</div>
<div class="row mb-4">
    <div class="col-12 col-md-8 order-2 order-md-1">
        <div class="d-flex justify-content-between mb-2">
            <p class="fw-medium">Tryout dalam progress</p>
            <a href="" class="link-secondary d-flex gap-2 align-items-center text-decoration-none">Lihat semua
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-chevron-right">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
        </div>
        <div class=" rounded mb-4 overflow-hidden p-0" style="max-height: fit-content;">
            <div class="container tryout-container w-100 bg-white">
                <!-- Kontainer untuk menampilkan tryout -->
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 d-flex mb-2 justify-content-between">
                <p class="fw-medium">Tryout belum dikerjakan</p>
                <a href="" class="link-secondary d-flex gap-2 align-items-center text-decoration-none">Lihat semua
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-chevron-right">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            </div>
            <div class="col-12 d-flex w-100 gap-3 overflow-auto tryout-container-not-started"
                style="scrollbar-width: none; white-space: nowrap;">
                @for ($i = 0; $i < 8; $i++) <div class="card border" style="min-width: 160px; flex-shrink: 0;">
                    <div class="placeholder-glow rounded" style="height: 200px">
                        <span class="placeholder w-100 h-100"></span>
                    </div>
            </div>

            @endfor
        </div>


    </div>
    <div class="card border bg-white">
        <div class="card-header bg-white">
            <p class="fw-medium">Grafik Perkembangan</p class="fw-medium">
        </div>
        <div class="card-body">
            <div id="chart">
            </div>
        </div>
    </div>
</div>
<div class="col-12 col-md-4 order-1 order-md-2">
    <div class="form-group mb-4 w-100">
        <label for="batch-select" class="form-label fw-medium">Pilih Batch</label>
        <select class="form-select border w-100" id="batch-select">
            @foreach ($batches as $batch)
            <option value="{{ $batch->id }}">{{ $batch->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="card py-1 border rounded mb-4">
        <div class="card-header pb-0 bg-transparent border-0">
            <p class="fw-medium d-flex align-items-end mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>

                Jadwal Ujian
            </p>
        </div>
        <div class="card-body py-0">
            <div class="row text-center justify-content-evenly">
                <!-- Days -->
                <div class="col-3 p-2">
                    <div class="card border-0 shadow-sm" style="max-width: 80px; border-radius: 12px;">
                        <div class="card-body p-2">
                            <p id="days" class="fw-bold mb-0" style="font-size: 18px;">00</p>
                            <p class="small mb-0">Hari</p>
                        </div>
                    </div>
                </div>
                <!-- Hours -->
                <div class="col-3 p-2">
                    <div class="card border-0 shadow-sm" style="max-width: 80px; border-radius: 12px;">
                        <div class="card-body p-2">
                            <p id="hours" class="fw-bold mb-0" style="font-size: 18px;">00</p>
                            <p class="small mb-0">Jam</p>
                        </div>
                    </div>
                </div>
                <!-- Minutes -->
                <div class="col-3 p-2">
                    <div class="card border-0 shadow-sm" style="max-width: 80px; border-radius: 12px;">
                        <div class="card-body p-2">
                            <p id="minutes" class="fw-bold mb-0" style="font-size: 18px;">00</p>
                            <p class="small mb-0">Menit</p>
                        </div>
                    </div>
                </div>
                <!-- Seconds -->
                <div class="col-3 p-2">
                    <div class="card border-0 shadow-sm" style="max-width: 80px; border-radius: 12px;">
                        <div class="card-body p-2">
                            <p id="seconds" class="fw-bold mb-0" style="font-size: 18px;">00</p>
                            <p class="small mb-0">Detik</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 pt-0">
            <p id="batch_jadwal_ujian" class=""></p>
        </div>
    </div>
    <div class="card mb-4 border">
        <div class="card-header border-0 bg-white">
            <p class="fw-medium d-flex align-items-end mb-0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="me-1">
                    <path d="M3 3H6V21H3V3Z" fill="currentColor" />
                    <path d="M9 12H12V21H9V12Z" fill="currentColor" />
                    <path d="M15 8H18V21H15V8Z" fill="currentColor" />
                </svg>
                Leaderboard
            </p>
            <small class="fs-10 text-muted">Menampilkan user user terbaik di setiap tryout</small>
        </div>
        <div class="card-body py-0 overflow-x-auto">
            <table class="w-100">
                <tbody id="leaderboard">
                    @for ($i = 0; $i < 3; $i++)<tr class="border-bottom">
                        <td class="py-1">
                            <div class="placeholder-glow w-100">
                                <div class="placeholder col-12"></div>
                            </div>
                        </td>
                        <td class="py-1">
                            <div class="placeholder-glow w-100">
                                <div class="placeholder col-12"></div>
                            </div>
                        </td>
                        <td class="py-1">
                            <div class="placeholder-glow w-100">
                                <div class="placeholder col-12"></div>
                            </div>
                        </td>
                        </tr>
                        @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
        // Inisialisasi
        const batchSelect = $('#batch-select');


        // Fungsi utama untuk memuat data berdasarkan batch
        function loadBatchData(batchId) {
            if (!batchId) return;

            Promise.all([
                getTryoutsData(batchId),
                loadChartData(batchId),
                getNotStartedTryouts(batchId),
                loadLeaderboard(batchId)
            ]).catch(error => console.error('Error loading batch data:', error));
        }

        // Event listener untuk perubahan batch
        batchSelect.on('change', function () {
            loadBatchData($(this).val());
        });

        // Load data awal saat halaman dimuat
        loadBatchData(batchSelect.val());
    });

    // Fungsi Ajax untuk tryouts data
    function getTryoutsData(batchId) {
        return $.ajax({
            type: "POST",
            url: "{{ route('tryouts.getTryouts') }}",
            data: { _token: "{{ csrf_token() }}", batch: batchId },
            success: function (response) {
                if (response.status === 'success') {
                    updateTryoutsContainer(response.data.tryouts, response.data.batch_end_date);
                }
            },
            error: handleAjaxError
        });
    }

    function updateTryoutsContainer(data, endDate) {
        startCountdown(endDate);
        const container = $('.tryout-container');
        container.empty();
        data = data[0];
        console.log(data);
        if (data.length > 0) {
            Object.values(data).forEach(item => {
                container.append(
                    getContainerTryouts(item) // Kirim seluruh `item` sebagai objek
                );
            });
        } else {
            container.html(`
                <div class="card border bg-white w-100">
                    <div class="card-body d-flex justify-content-center align-items-center">
                        Anda belum mengerjakan tryout!
                    </div>
                </div>
            `);
        }
    }


    function startCountdown(date) {
            const countDownDate = new Date(date).getTime();
            $('#batch_jadwal_ujian').text(new Date(date).toLocaleString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                weekday: 'long'
            }))
            setInterval(function() {
            const now = new Date().getTime();
            const distance = countDownDate - now;

            // Hitung hari, jam, menit, dan detik
            const days = Math.max(Math.floor(distance / (1000 * 60 * 60 * 24)), 0);
            const hours = Math.max(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)), 0);
            const minutes = Math.max(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)), 0);
            const seconds = Math.max(Math.floor((distance % (1000 * 60)) / 1000), 0);

            // Tampilkan hasil dalam elemen HTML dengan ID
            document.getElementById("days").innerHTML = days.toString().padStart(2, '0');
            document.getElementById("hours").innerHTML = hours.toString().padStart(2, '0');
            document.getElementById("minutes").innerHTML = minutes.toString().padStart(2, '0');
            document.getElementById("seconds").innerHTML = seconds.toString().padStart(2, '0');

            // Jika countdown selesai
            if (distance <= 0) {
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

    function getContainerTryouts({ nama, tanggal, id, jumlah_soal, progress, status_pengerjaan, image, nama_batch }) {
        return `
            <div class="row align-items-center border rounded p-2 flex-nowrap">
                <div class="col-auto">
                    <img src="{{ asset('storage/${image}') }}" class="rounded p-0 border" style="width: 100px; height: 60px;" alt="">
                </div>
                <div class="col text-truncate">
                    <p class="mb-1 fs-10 text-muted text-truncate">${nama_batch}</p>
                    <p class="mb-0 fs-14 fw-medium text-truncate">${nama}</p>
                </div>
                <div class="col-auto d-none d-md-block">
                    <p class="mb-1 fs-10 text-muted">Jumlah Soal</p>
                    <p class="mb-0 fs-14 fw-medium">${jumlah_soal} soal</p>
                </div>
                <div class="col text-truncate">
                    <p class="mb-1 fs-10 text-muted">Progress</p>
                    <div class="d-flex align-items-center">
                        <span class="fs-14 fw-medium">${status_pengerjaan === 'finished' ? 'Selesai'  : (progress + '%')}</span>
                    </div>
                </div>
                <div class="col-auto d-none d-md-block">
                    <p class="mb-1 fs-10 text-muted">Tanggal</p>
                    <p class="mb-0 fs-14 fw-medium text-truncate">${tanggal || 'Tanggal tidak tersedia'}</p>
                </div>
                <div class="col-auto">
                    ${status_pengerjaan === 'finished'
                        ? `<button onclick="window.location.href='/tryout/hasil/${id}'" class="btn primary-button btn-sm">Lihat Hasil</button>`
                        : `<button onclick="window.location.href='/tryout/show/${id}'" class="btn primary-button btn-sm">Lanjutkan</button>`
                    }
                </div>
            </div>
        `;
    }

    // Fungsi Ajax untuk leaderboard
    function loadLeaderboard(batchId) {
        return $.ajax({
            type: "POST",
            url: "{{ route('tryout.getLeaderboard') }}",
            data: { _token: "{{ csrf_token() }}", batch_id: batchId },
            success: function (response) {
                if (response.status === 'success') {
                    updateLeaderboard(response.data);
                }
            },
            error: handleAjaxError
        });
    }

    function updateLeaderboard(data) {
        const tbody = $('tbody#leaderboard');
        tbody.empty();
        if (data.length > 0) {
            tbody.append(`
                <tr class="border-bottom">
                    <td class="p-1 fs-14">User</td>
                    <td class="p-1 fs-14">Score</td>
                    <td class="p-1 fs-14">Tryout</td>
                </tr>
            `);
            data.forEach(item => {
                tbody.append(`
                    <tr>
                        <td class="p-1 py-2 fs-12 fw-medium">${item.user_nama}</td>
                        <td class="p-1 fs-12 px-2">
                            <span class="badge rounded-pill bg-success">${item.nilai}</span>
                        </td>
                        <td class="p-1 fs-12">${item.tryout_nama}</td>
                    </tr>
                `);
            });
        } else {
            tbody.append('<tr><td colspan="3" class="text-center">No data available</td></tr>');
        }
    }

    // Fungsi Ajax untuk not started tryouts
    function getNotStartedTryouts(batchId) {
        return $.ajax({
            type: "POST",
            url: "{{ route('tryout.getNotStartedTryouts') }}",
            data: { _token: "{{ csrf_token() }}", batch: batchId },
            success: function (response) {
                updateNotStartedTryouts(response.data);
            },
            error: handleAjaxError
        });
    }

    function updateNotStartedTryouts(data) {
        const container = $('.tryout-container-not-started');
        container.empty();
        if (data.length > 0) {
            data.forEach(item => {
                container.append(getNotStartedContainer(item));
            });
        } else {
            container.html(`<div class="card border bg-white w-100"><div class="card-body d-flex justify-content-center align-items-center">Belum ada tryout tersedia!</div></div>`);
        }
    }

    function getNotStartedContainer({ image, nama, jumlah_soal, id, bidang }) {
        return `
            <div class="card border" style="max-width: 200px; flex-shrink: 0;">
                <div class="card-body p-1 pb-2 d-flex flex-column gap-2 align-items-start">
                    <img src="{{ asset('storage/${image}') }}" class="rounded border" style="width: 100%; height: 90px;" alt="Placeholder Image">
                    <p class="px-2 fs-12 text-muted fw-bold">${jumlah_soal} <span class="fw-normal">Soal</p>
                    <p class="fw-bold fs-14 px-2 text-wrap">${nama}</p>
                    <div class="w-100 px-2 mb-1 overflow-x-auto" style="scrollbar-width: none;">
                        ${bidang.map(b => `<span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">${b}</span>`).join('')}
                    </div>
                    <button onclick="window.location.href='/tryout/show/${id}'" class="btn mx-2 primary-button btn-sm p-1">Kerjakan</button>
                </div>
            </div>
        `;
    }

    // Fungsi Ajax untuk data chart
    function loadChartData(batchId) {
        return $.ajax({
            url: "{{ route('tryout.getUserTryoutData') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", batch_id: batchId },
            success: function (data) {
                updateChart(data);
            },
            error: handleAjaxError
        });
    }

    function updateChart(data) {
        const options = {
            chart: { height: 280, type: "area" },
            dataLabels: { enabled: false },
            series: [{ name: "User Tryout Scores", data: data.values }],
            xaxis: { categories: data.labels },
            fill: {
                type: "gradient",
                gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.9, stops: [0, 90, 100] }
            },
            colors: ["{{ setting('general.primary_color') }}"]
        };
        new ApexCharts(document.querySelector("#chart"), options).render();
    }

    // Fungsi error handler
    function handleAjaxError(xhr, status, error) {
        console.error('Ajax Error:', status, error);
    }
</script>
@endsection