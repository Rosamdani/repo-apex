@extends('layouts.index')
@section('title', 'My Tryout')
@section('content')
<div class="row mb-4 justify-content-between align-items-center">
    <div class="col-12 col-md-6">
        <h3 class="mb-0 d-flex align-items-center">My Tryout</h3>

    </div>
    <div class="col-12 col-md-6 text-md-end">
        <p>
            {{ \Carbon\Carbon::now()->locale('id_ID')->translatedFormat('l, j F Y') }}
        </p>
    </div>
</div>
<div class="row mb-4 gap-2 tryout-wrapper">
    <div class="col-12">
        <h5 class="fw-medium">Lanjutkan tryoutmu</h5>
    </div>
    <div class="col-12 d-flex overflow-x-auto w-100 gap-3 tryout-container" style="scrollbar-width: none;">

    </div>
</div>
<div class="row mb-4">
    <div class="col-12 mb-3 order-2 order-md-1">
        <select name="batch_select" class="bg-transparent border-0 fs-5 fw-medium outline-none ring-0" id="batch_id">
            <option value="semua">Semua Batch
            </option>
            @foreach ($batches as $batch)
            <option value="{{ $batch->id }}">{{ $batch->nama }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-md-6 order-3 order-md-2">
        <ul class="d-flex gap-3 ps-0">
            <li class="list-unstyled">
                <button id="semua" class="btn btn-sm primary-button rounded-pill text-nowrap active">Semua</button>
            </li>
            <li class="list-unstyled">
                <button id="belum_dikerjakan" class="btn btn-sm primary-button rounded-pill text-nowrap">Belum
                    Dikerjakan</button>
            </li>
            <li class="list-unstyled">
                <button id="dalam_progress" class="btn btn-sm primary-button rounded-pill text-nowrap">Dalam
                    Progress</button>
            </li>
            <li class="list-unstyled">
                <button id="selesai" class="btn btn-sm primary-button rounded-pill text-nowrap">Selesai</button>
            </li>
        </ul>
    </div>
    <div class="col-12 order-1 order-md-3 mb-3 mb-md-0 col-md-6 d-flex justify-content-md-end">
        <ul class="d-flex w-100 gap-3 ps-0 justify-content-between justify-content-md-end">
            <li class="list-unstyled">
                <div class="form-group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="me-2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" class="bg-transparent border-0 outline-none ring-0 focus-visible"
                        style="max-width: 100px;" placeholder="Cari...">
                </div>
            </li>
            <li class="list-unstyled border-start border-secondary"></li>
            <li class="list-unstyled">
                <div class="form-group d-flex gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Ikon Filter -->
                        <polygon points="3 4 21 4 14 12 14 20 10 20 10 12 3 4"></polygon>
                        <!-- Ikon Add (Plus) -->
                        <line x1="19" y1="13" x2="19" y2="19"></line>
                        <line x1="16" y1="16" x2="22" y2="16"></line>
                    </svg>
                    <div class="dropdown">
                        <button class="btn btn-sm bg-transparent dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Filter
                        </button>
                        <ul class="dropdown-menu p-2" style="min-width: fit-content;"
                            aria-labelledby="dropdownMenuButton1">
                            <li>

                                <div class="mb-2">
                                    <label for="filter_urutan" class="form-label text-nowrap">Urutkan
                                        berdasarkan</label>
                                    <select name="filter_urutan" id="filter_urutan" class="form-select">
                                        <option value="nama">Nama</option>
                                        <option value="tanggal">Tanggal</option>
                                        <option value="progress">Progress</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="filter_status" class="form-label">Mode</label>
                                    <select name="filter_status" id="filter_status" class="form-select">
                                        <option value="asc">Ascending</option>
                                        <option value="desc">Descending</option>
                                    </select>
                                </div>
                                <button type="button" id="apply-filter"
                                    class="btn btn-sm primary-button">Terapkan</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <li class="list-unstyled border-start border-secondary"></li>
            <li class="list-unstyled d-flex gap-2">
                <button id="grid-button" class="btn active btn-sm primary-button" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Ikon Grid -->
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </button>
                <button id="list-button" class="btn btn-sm primary-button" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Ikon List -->
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3" y2="6"></line>
                        <line x1="3" y1="12" x2="3" y2="12"></line>
                        <line x1="3" y1="18" x2="3" y2="18"></line>
                    </svg>
                </button>
            </li>
        </ul>
    </div>
</div>
<div class="row mb-4 tryout-content">
    <div class="content mb-4 col-md-3 belum_dikerjakan" data-batch-id="{{$batches[0]->id}}">
        <div class="card">
            <div class="card-body d-flex flex-column gap-3">
                <img src="" alt="" class="border rounded image-tryout">
                <div class="d-flex flex-column gap-2">
                    <small class="text-muted fs-12">Batch I 2024</small>
                    <p class="fw-medium">Try Out FDI 1 Batch I 2024</p>
                    <div class="w-100 d-flex gap-1 mb-1 overflow-x-auto" style="scrollbar-width: none;">
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">INTERNA</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">PEDIATRI</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">OBGYN</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">MATA</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-end action_group gap-2">
                        <div class="d-block gap-2">
                            <small class="text-muted fs-12">Progress</small>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="d-block">
                            <button class="btn primary-button btn-sm rounded-pill">Kerjakan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content mb-4 col-md-3 belum_dikerjakan" data-batch-id="{{$batches[0]->id}}">
        <div class="card">
            <div class="card-body d-flex flex-column gap-3">
                <img src="" alt="" class="border rounded image-tryout">
                <div class="d-flex flex-column gap-2">
                    <small class="text-muted fs-12">Batch I 2024</small>
                    <p class="fw-medium">Try Out FDI 1 Batch I 2024</p>
                    <div class="w-100 d-flex gap-1 mb-1 overflow-x-auto" style="scrollbar-width: none;">
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">INTERNA</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">PEDIATRI</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">OBGYN</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">MATA</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-end action_group gap-2">
                        <div class="d-block gap-2">
                            <small class="text-muted fs-12">Progress</small>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="d-block">
                            <button class="btn primary-button btn-sm rounded-pill">Kerjakan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content mb-4 col-md-3 belum_dikerjakan" data-batch-id="25235-dsgfs3wr-vwer42-53dds342">
        <div class="card">
            <div class="card-body d-flex flex-column gap-3">
                <img src="" alt="" class="border rounded image-tryout">
                <div class="d-flex flex-column gap-2">
                    <small class="text-muted fs-12">Batch I 2024</small>
                    <p class="fw-medium">Try Out FDI 1 Batch I 2024</p>
                    <div class="w-100 d-flex gap-1 mb-1 overflow-x-auto" style="scrollbar-width: none;">
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">INTERNA</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">PEDIATRI</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">OBGYN</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">MATA</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-end action_group gap-2">
                        <div class="d-block gap-2">
                            <small class="text-muted fs-12">Progress</small>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="d-block">
                            <button class="btn primary-button btn-sm rounded-pill">Kerjakan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content mb-4 col-md-3 belum_dikerjakan" data-batch-id="{{$batches[0]->id}}">
        <div class="card">
            <div class="card-body d-flex flex-column gap-3">
                <img src="" alt="" class="border rounded image-tryout">
                <div class="d-flex flex-column gap-2">
                    <small class="text-muted fs-12">Batch I 2024</small>
                    <p class="fw-medium">Try Out FDI 1 Batch I 2024</p>
                    <div class="w-100 d-flex gap-1 mb-1 overflow-x-auto" style="scrollbar-width: none;">
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">INTERNA</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">PEDIATRI</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">OBGYN</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">MATA</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-end action_group gap-2">
                        <div class="d-block gap-2">
                            <small class="text-muted fs-12">Progress</small>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="d-block">
                            <button class="btn primary-button btn-sm rounded-pill">Kerjakan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content mb-4 col-md-3 belum_dikerjakan" data-batch-id="{{$batches[0]->id}}">
        <div class="card">
            <div class="card-body d-flex flex-column gap-3">
                <img src="" alt="" class="border rounded image-tryout">
                <div class="d-flex flex-column gap-2">
                    <small class="text-muted fs-12">Batch I 2024</small>
                    <p class="fw-medium">Try Out FDI 1 Batch I 2024</p>
                    <div class="w-100 d-flex gap-1 mb-1 overflow-x-auto" style="scrollbar-width: none;">
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">INTERNA</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">PEDIATRI</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">OBGYN</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">MATA</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-end action_group gap-2">
                        <div class="d-block gap-2">
                            <small class="text-muted fs-12">Progress</small>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="d-block">
                            <button class="btn primary-button btn-sm rounded-pill">Kerjakan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content mb-4 col-md-3 dalam_progress" data-batch-id="{{$batches[0]->id}}">
        <div class="card">
            <div class="card-body d-flex flex-column gap-3">
                <img src="" alt="" class="border rounded image-tryout">
                <div class="d-flex flex-column gap-2">
                    <small class="text-muted fs-12">Batch I 2024</small>
                    <p class="fw-medium">Try Out FDI 1 Batch I 2024</p>
                    <div class="w-100 d-flex gap-1 mb-1 overflow-x-auto" style="scrollbar-width: none;">
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">INTERNA</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">PEDIATRI</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">OBGYN</span>
                        <span class="badge bg-secondary-subtle me-2 fs-10 text-secondary rounded-pill">MATA</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-end action_group gap-2">
                        <div class="d-block gap-2">
                            <small class="text-muted fs-12">Progress</small>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="d-block">
                            <button class="btn primary-button btn-sm rounded-pill">Kerjakan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
        function getTryoutsData(batchId) {
            return $.ajax({
                type: "POST",
                url: "{{ route('tryouts.getTryouts') }}",
                data: { _token: "{{ csrf_token() }}", batch: batchId },
                success: function (response) {
                    if (response.status === 'success') {
                        if(response.data.tryouts.length > 0){
                            updateTryoutsContainer(response.data.tryouts, response.data.batch_end_date);
                        }else{
                            $('.tryout-wrapper').addClass('d-none');
                        }
                    }
                },
                error: function (err){
                    console.log(err)
                }
            });
        }

        function updateTryoutsContainer(data, endDate) {
            const container = $('.tryout-container');
            container.empty();

            if (data.length > 0) {
                data.forEach(item => {
                    container.append(getContainerTryouts(item));
                });
            } else {
                container.html(`
                <div class="card border bg-white w-100"><div class="card-body d-flex justify-content-center align-items-center">Anda belum mengerjakan tryout!</div></div>
                `);
            }
        }

        function getContainerTryouts({ nama, tanggal, id, jumlah_soal, progress, status_pengerjaan, image, nama_batch }) {
            return `
                <div class="card border bg-white" style="flex-shrink: 0;">
                    <div class="card-body d-flex gap-3">
                        <div class="image position-relative border rounded">
                            <div class="position-absolute top-0 left-0 bg-black text-white m-1 p-1 bg-opacity-50 rounded-1 fs-10"
                                style="max-width: fit-content; --bs-bg-opacity: .7;">
                                ${jumlah_soal} Soal
                            </div>
                            <img src="{{ asset('/storage/${image}')}}" alt="Alter"
                                class="image-tryout rounded bg-cover bg-no-repeat">
                        </div>
                        <div class="d-flex flex-column position-relative">
                            <p class="fs-12 text-secondary">${nama_batch}</p>
                            <h6 class="fw-bold">${nama}</h6>
                            <div class="d-flex gap-2 flex-wrap justify-content-between align-items-center mt-2">
                                <div class="d-block w-75">
                                    <p class="fs-12 text-secondary">Progress: <span class="text-black">${progress}%</span></p>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: ${progress}%"
                                            aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="button">
                                    ${status_pengerjaan === 'finished'
                                        ? `<button onclick="window.location.href='/tryout/hasil/${id}'" class="btn primary-button btn-sm">Lihat Hasil</button>`
                                        : `<button onclick="window.location.href='/tryout/show/${id}'" class="btn primary-button btn-sm">Lanjutkan</button>`
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Buat trigger untuk mengubah bentuk konten
        $('#list-button').click(function(){
            $(this).addClass('active').siblings().removeClass('active');
            $('.tryout-content .content').removeClass('col-md-3').addClass('col-12');
            $('.tryout-content .content .card-body').removeClass('flex-column').addClass('flex-row');
            $('.tryout-content .content .card-body .action_group').removeClass('justify-content-between');
        });

        $('#grid-button').click(function(){
            $(this).addClass('active').siblings().removeClass('active');
            $('.tryout-content .content').removeClass('col-12').addClass('col-md-3');
            $('.tryout-content .content .card-body').removeClass('flex-row').addClass('flex-column');
            $('.tryout-content .content .card-body .action_group').addClass('justify-content-between');
        });

        let selectedBatch = 'semua';
        $(document).on('change', '#batch_id', function() {
            selectedBatch = $(this).val();
            console.log(`Batch yang dipilih: ${selectedBatch}`);
            if (selectedBatch !== 'semua') {
                $('.tryout-content .content').hide().filter(`[data-batch-id="${selectedBatch}"]`).show();
            } else {
                $('.tryout-content .content').show();
            }
        });

        $('#semua, #belum_dikerjakan, #dalam_progress, #selesai').click(function() {
            $('.tryout-content .content').hide();
            if ($(this).attr('id') !== 'semua') {
                $(`.tryout-content .content.${$(this).attr('id')}`).each(function() {
                    // Pastikan filter hanya berlaku untuk batch yang sesuai
                    if (selectedBatch === 'semua' || $(this).data('batch-id') === selectedBatch) {
                        $(this).show();
                    }
                });
            } else {
                $('.tryout-content .content').each(function() {
                    // Tampilkan semua item hanya dalam batch yang sesuai
                    if (selectedBatch === 'semua' || $(this).data('batch-id') === selectedBatch) {
                        $(this).show();
                    }
                });
            }
            $(this).addClass('active').parent().siblings().children().removeClass('active');
        });

        getTryoutsData('semua');
    });
</script>
@endsection