@extends('layouts.index')
@section('title', 'Hasil Tryout')
@section('content')
<div class="row align-items-center mb-3">
    <div class="col-12 col-md-6">
        <h2>{{ $userTryout->tryout->nama }}</h2>
    </div>
    <div class="col-12 col-md-6 text-md-end">
        <p>
            {{ \Carbon\Carbon::now()->locale('id_ID')->translatedFormat('l, j F Y') }}
        </p>
    </div>
</div>
<div class="row gap-lg-0">
    <div class="col-12 col-md-8 mb-3 px-0">
        <div class="row mx-0">
            <div class="col-12 mb-3 col-md-4">
                <div class="card h-100 border-0 tertiary-background">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <p class="" style="color: #B3B3B3FF;">Nilai anda</p>
                            <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                            <svg width="40px" height="40px" viewBox="0 0 24 24" fill="none" class="p-1 rounded-circle"
                                style="background-color: #3281CA3F;" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.8179 4.54512L13.6275 4.27845C12.8298 3.16176 11.1702 3.16176 10.3725 4.27845L10.1821 4.54512C9.76092 5.13471 9.05384 5.45043 8.33373 5.37041L7.48471 5.27608C6.21088 5.13454 5.13454 6.21088 5.27608 7.48471L5.37041 8.33373C5.45043 9.05384 5.13471 9.76092 4.54512 10.1821L4.27845 10.3725C3.16176 11.1702 3.16176 12.8298 4.27845 13.6275L4.54512 13.8179C5.13471 14.2391 5.45043 14.9462 5.37041 15.6663L5.27608 16.5153C5.13454 17.7891 6.21088 18.8655 7.48471 18.7239L8.33373 18.6296C9.05384 18.5496 9.76092 18.8653 10.1821 19.4549L10.3725 19.7215C11.1702 20.8382 12.8298 20.8382 13.6275 19.7215L13.8179 19.4549C14.2391 18.8653 14.9462 18.5496 15.6663 18.6296L16.5153 18.7239C17.7891 18.8655 18.8655 17.7891 18.7239 16.5153L18.6296 15.6663C18.5496 14.9462 18.8653 14.2391 19.4549 13.8179L19.7215 13.6275C20.8382 12.8298 20.8382 11.1702 19.7215 10.3725L19.4549 10.1821C18.8653 9.76092 18.5496 9.05384 18.6296 8.33373L18.7239 7.48471C18.8655 6.21088 17.7891 5.13454 16.5153 5.27608L15.6663 5.37041C14.9462 5.45043 14.2391 5.13471 13.8179 4.54512Z"
                                    stroke="#3280CAFF" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M9 12L10.8189 13.8189V13.8189C10.9189 13.9189 11.0811 13.9189 11.1811 13.8189V13.8189L15 10"
                                    stroke="#3280CAFF" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h3 class="fw-bold">{{ $userTryout->nilai }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3 col-md-4">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <p class="" style="color: #B3B3B3FF;">Ranking</p>
                            <div class="d-flex p-2 rounded-circle" style="background-color: #D9732041;">
                                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M8.67 14H4C2.9 14 2 14.9 2 16V22H8.67V14Z" stroke="#D97320FF"
                                            stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path
                                            d="M13.33 10H10.66C9.56003 10 8.66003 10.9 8.66003 12V22H15.33V12C15.33 10.9 14.44 10 13.33 10Z"
                                            stroke="#D97320FF" stroke-width="1" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M20 17H15.33V22H22V19C22 17.9 21.1 17 20 17Z" stroke="#D97320FF"
                                            stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path
                                            d="M12.52 2.07007L13.05 3.13006C13.12 3.28006 13.31 3.42006 13.47 3.44006L14.43 3.60007C15.04 3.70007 15.19 4.15005 14.75 4.58005L14 5.33005C13.87 5.46005 13.8 5.70006 13.84 5.87006L14.05 6.79007C14.22 7.52007 13.83 7.80007 13.19 7.42007L12.29 6.89007C12.13 6.79007 11.86 6.79007 11.7 6.89007L10.8 7.42007C10.16 7.80007 9.76998 7.52007 9.93998 6.79007L10.15 5.87006C10.19 5.70006 10.12 5.45005 9.98999 5.33005L9.24999 4.59006C8.80999 4.15006 8.94999 3.71005 9.56999 3.61005L10.53 3.45007C10.69 3.42007 10.88 3.28007 10.95 3.14007L11.48 2.08005C11.77 1.50005 12.23 1.50007 12.52 2.07007Z"
                                            stroke="#D97320FF" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                    </g>
                                </svg>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h3 class="fw-bold mb-0">{{ $userTryoutRank }}</h3>
                                <small class="text-muted mb-0">Dari total {{ $totalUser }} peserta</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3 col-md-4">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <p class="" style="color: #B3B3B3FF;">Status</p>
                            <svg width="40px" height="40px" viewBox="-7.68 -7.68 39.36 39.36" fill="none"
                                xmlns="http://www.w3.org/2000/svg" transform="rotate(0)" stroke="#35e361">
                                <g id="SVGRepo_bgCarrier" stroke-width="0">
                                    <rect x="-7.68" y="-7.68" width="39.36" height="39.36" rx="19.68" fill="#c7ffd4"
                                        strokewidth="0"></rect>
                                </g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M3 10C3 6.22876 3 4.34315 4.17157 3.17157C5.34315 2 7.22876 2 11 2H13C16.7712 2 18.6569 2 19.8284 3.17157C21 4.34315 21 6.22876 21 10V14C21 17.7712 21 19.6569 19.8284 20.8284C18.6569 22 16.7712 22 13 22H11C7.22876 22 5.34315 22 4.17157 20.8284C3 19.6569 3 17.7712 3 14V10Z"
                                        stroke="#35e361" stroke-width="0.9120000000000001"></path>
                                    <path d="M8 10H16" stroke="#35e361" stroke-width="0.9120000000000001"
                                        stroke-linecap="round"></path>
                                    <path d="M8 14H13" stroke="#35e361" stroke-width="0.9120000000000001"
                                        stroke-linecap="round"></path>
                                </g>
                            </svg>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h3 class="fw-bold">{{ $status_lulus }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 d-block d-md-none">
                <div class="card border-0 mb-3 fs-14">
                    <div class="card-body">
                        <div class="d-flex flex-column justify-content-between align-items-start">
                            <div class="card border w-100 mb-2">
                                <div
                                    class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <p>Jawaban Benar</p>
                                    <p class="fw-medium">120/150</p>
                                </div>
                            </div>
                            <div class="card border w-100 mb-2">
                                <div
                                    class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <p>Jawaban Ragu-ragu</p>
                                    <p class="fw-medium">8</p>
                                </div>
                            </div>
                            <div class="card border w-100 mb-2">
                                <div
                                    class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <p>Tidak Dijawab</p>
                                    <p class="fw-medium">12</p>
                                </div>
                            </div>
                            <div class="card border w-100 mb-2">
                                <div
                                    class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <p>Total Waktu</p>
                                    <p class="fw-medium">3 Jam 2 menit</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mx-0">
            <div class="col-12">
                <div class="card mb-3 overflow-y-auto" style="max-height: 500px;">
                    <div class="card-header bg-white border-0">
                        <p class="fw-medium d-flex align-items-end mb-0">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="me-1">
                                <path d="M3 3H6V21H3V3Z" fill="currentColor" />
                                <path d="M9 12H12V21H9V12Z" fill="currentColor" />
                                <path d="M15 8H18V21H15V8Z" fill="currentColor" />
                            </svg>
                            Leaderboard
                        </p>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered w-100 table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>User</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>🏆</td>
                                    <td>Rosyamdani</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>🥈</td>
                                    <td>Dani</td>
                                    <td>98</td>
                                </tr>
                                <tr>
                                    <td>🥉</td>
                                    <td>Rosy</td>
                                    <td>97</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0">Detail Jawaban</h4>
                        <select class="form-select ms-3" id="select_jawaban" , function() { $(this).val=='bidang' ? $()
                            } aria-label="Pilih Kategori" style="width: auto;">
                            <option value="bidang" selected>Per Bidang</option>
                            <option value="kompetensi">Per Kompetensi</option>
                        </select>
                    </div>
                    <div class="card-body">
                        <div class="mx-1 overflow-x-auto">
                            <table id="table_bidang"
                                class="table rounded table-bordered table-striped table-hover w-100"
                                style="background-color: #f5f5f5">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap">Bidang</th>
                                        <th class="text-nowrap">Jml Soal</th>
                                        <th class="text-nowrap">Benar</th>
                                        <th class="text-nowrap">Salah</th>
                                        <th class="text-nowrap">Tidak Dikerjakan</th>
                                    </tr>
                                </thead>
                                <tbody id="bidang_container">
                                    <!-- Rows will be dynamically generated here -->
                                </tbody>
                            </table>
                            <table id="table_kompetensi"
                                class="table d-none rounded table-bordered table-striped table-hover w-100"
                                style="background-color: #f5f5f5">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap">Kompetensi</th>
                                        <th class="text-nowrap">Jml Soal</th>
                                        <th class="text-nowrap">Benar</th>
                                        <th class="text-nowrap">Salah</th>
                                        <th class="text-nowrap">Tidak Dikerjakan</th>
                                    </tr>
                                </thead>
                                <tbody id="kompetensi_container">
                                    <!-- Rows will be dynamically generated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="col-12">
            <div class="card border-0 mb-3 d-none d-md-block">
                <div class="card-body fs-14">
                    <div class="d-flex flex-column justify-content-between align-items-start">
                        <div class="card border w-100 mb-2">
                            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <p>Jawaban Benar</p>
                                <p class="fw-medium">120/150</p>
                            </div>
                        </div>
                        <div class="card border w-100 mb-2">
                            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <p>Jawaban Ragu-ragu</p>
                                <p class="fw-medium">8</p>
                            </div>
                        </div>
                        <div class="card border w-100 mb-2">
                            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <p>Tidak Dijawab</p>
                                <p class="fw-medium">12</p>
                            </div>
                        </div>
                        <div class="card border w-100 mb-2">
                            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <p>Total Waktu</p>
                                <p class="fw-medium">3 Jam 2 menit</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-0 mb-3">
                <div class="card-header bg-white border-0">
                    <h4>Testimoni</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('testimoni.store')}}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $userTryout->tryout_id }}" name="tryout_id">
                        <div class="form-group mb-3">
                            <textarea name="testimoni" id="testimoni" class="form-control bg-light"
                                placeholder="Masukkan testimoni / kritik kamu" style="height: 100px;"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <div class="mb-5">
                                <h6 class="mb-0">Beri bintang untuk ujian ini</h6>
                                <div class="rating"> <input type="radio" name="rating" value="5" id="5"><label
                                        for="5">☆</label> <input type="radio" name="rating" value="4" id="4"><label
                                        for="4">☆</label>
                                    <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label> <input
                                        type="radio" name="rating" value="2" id="2"><label for="2">☆</label> <input
                                        type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                                </div>
                                <button type="submit" class="btn btn-primary">Kirim Testimoni</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-8 mb-3 mb-md-0">
                        <div id="chart_subtopik"></div>
                    </div>
                    <div class="col-12 col-md-4">
                        <h6>Estimasi pendalaman per-bidang</h6>
                        <div class="d-flex flex-column" id="estimasiPerbidang">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
            showLoading();

            // Ambil data dari server melalui AJAX
            $.ajax({
                url: "{{ route('tryout.getResult')}}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    tryout_id: "{{ $userTryout->tryout_id }}"
                },
                success: function(response) {
                    if (response.status === "success") {
                        const data = response.data;

                        // Render data pada #bidang_container
                        renderBidangData(data.bidang);

                        // Render data pada #kompetensi_container
                        renderKompetensiData(data.kompetensi);
                    } else {
                        Swal.fire("Gagal!", "Data gagal diambil", "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                    Swal.fire('Error!', `Terjadi kesalahan: ${error}`, 'error');
                },
                complete: function() {
                    hideLoading();
                }
            });

            function renderBidangData(bidangData) {
                $('#bidang_container').empty(); // Kosongkan container sebelum merender ulang

                $.each(bidangData, function(index, item) {
                    const row = `
                <tr>
                    <td class="fw-bold" style="color: #D76B00FF">${item.kategori}</td>
                    <td>${item.total_soal}</td>
                    <td>${item.benar}</td>
                    <td>${item.salah}</td>
                    <td>${item.tidak_dikerjakan}</td>
                </tr>
            `;
                    $('#bidang_container').append(row);
                });
            }

            // Fungsi untuk merender data kompetensi
            function renderKompetensiData(kompetensiData) {
                $('#kompetensi_container').empty(); // Kosongkan container sebelum merender ulang

                $.each(kompetensiData, function(index, item) {
                    const row = `
                <tr>
                    <td class="fw-bold" style="color: #D76B00FF">${item.kategori}</td>
                    <td>${item.total_soal}</td>
                    <td>${item.benar}</td>
                    <td>${item.salah}</td>
                    <td>${item.tidak_dikerjakan}</td>
                </tr>
                </div>
            `;
                    $('#kompetensi_container').append(row);
                });
            }

            $(document).on('change', '#select_jawaban', function() {
                if ($(this).val() == 'bidang') {
                    $('#table_bidang').removeClass('d-none');
                    $('#table_kompetensi').addClass('d-none');
                } else if ($(this).val() == 'kompetensi') {
                    $('#table_bidang').addClass('d-none');
                    $('#table_kompetensi').removeClass('d-none');
                }
            });
            $.ajax({
                url: "{{ route('tryouts.hasil.getChartSubTopik') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    tryout_id: "{{ $userTryout->tryout_id }}"
                },
                success: function(response) {
                    if (response.status === 'success') {
                        const bidangData = response.data.bidang;

                        estimasiPerbidang(bidangData);

                        var options = {
                            series: [{
                                name: 'Persentase Pendalaman Bidang',
                                data: bidangData.map(item => parseFloat(item.persen_benar))
                            }],
                            title: {
                                text: 'Pendalaman',
                                align: 'center',
                                style: {
                                    fontSize: '16px',
                                    fontWeight: 'bold',
                                    color: '#333'
                                }
                            },
                            chart: {
                                height: 350,
                                type: 'bar',
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 10,
                                    columnWidth: '50%',
                                    colors: {
                                        ranges: bidangData.map(item => {
                                            const persenBenar = parseFloat(item.persen_benar);
                                            if (persenBenar < 60) {
                                                return { from: persenBenar, to: persenBenar, color: '#FF6666' }; // Merah
                                            } else if (persenBenar >= 60 && persenBenar < 80) {
                                                return { from: persenBenar, to: persenBenar, color: '#FFA500' }; // Oranye
                                            } else {
                                                return { from: persenBenar, to: persenBenar, color: '#66CC66' }; // Hijau
                                            }
                                        })
                                    }
                                }
                            },
                            dataLabels: {
                                enabled: true,
                                formatter: function(val) {
                                    return val + "%"; // Tampilkan persentase dengan simbol %
                                }
                            },
                            xaxis: {
                                categories: bidangData.map(item => item.kategori), // Nama bidang
                                title: {
                                    text: 'Bidang'
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Persentase (%)'
                                },
                                max: 100 // Skala y-axis maksimum 100%
                            },
                            fill: {
                                type: 'solid', // Tidak ada gradient, gunakan warna solid
                            }
                        };

                        // Render chart
                        var chart = new ApexCharts(document.querySelector("#chart_subtopik"), options);
                        chart.render();
                    } else {
                        alert('Gagal mengambil data: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan saat mengambil data');
                }
            });

            function estimasiPerbidang(bidangData) {
                // Urutkan data berdasarkan persen_benar dari yang terendah
                bidangData.sort((a, b) => a.persen_benar - b.persen_benar);

                let html = '';

                bidangData.forEach(item => {
                    // Tentukan warna berdasarkan persen_benar
                    const warna = item.persen_benar < 60
                        ? '#FF6666' // Merah
                        : item.persen_benar < 80
                            ? '#FFA500' // Oranye
                            : '#66CC66'; // Hijau

                    // Tambahkan HTML
                    html += `
                        <span class="d-inline-block col-3" style="color: ${warna}; display: inline-block; width: 100%;">
                            ${item.kategori} : ${item.persen_benar}%
                        </span>
                    `;
                });

                // Masukkan HTML ke dalam elemen dengan ID estimasiPerbidang
                document.getElementById('estimasiPerbidang').innerHTML = html;
            }


        });
</script>
@endsection