@extends('layouts.master')
@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Staf</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Senarai Staf</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('staff.trash') }}">
            <button type="button" class="btn btn-primary mt-2 mt-lg-0">Senarai Rekod Dipadam</button>
        </a>
    </div>
</div>
<!--end breadcrumb-->
<h6 class="mb-0 text-uppercase">Senarai Staf</h6>
<hr />
@if (session('success'))
    <div class="alert alert-success mt-2">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger mt-2">
        {{ session('error') }}
    </div>
@endif
<div class="card">
    <div class="card-body">
    <div class="d-lg-flex align-items-center mb-4 gap-3">
    <div class="position-relative">
        <form action="{{ route('staff.search') }}" method="GET">
            <div class="input-group">
                <input type="text" class="form-control search-input" placeholder="Carian..." name="search">
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary search-button">
                        <i class="bx bx-search"></i>
                    </button>
                </span>
            </div>
        </form>
    </div>
    <div class="ms-auto d-flex gap-2 align-items-center">
        <!-- Import Button and Form -->
        <form action="{{ route('staff.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
            {{ csrf_field() }}
            <div class="form-group mb-0">
                <input type="file" name="file" class="form-control form-control-sm" required>
            </div>
            <button type="submit" class="btn btn-info ms-2">Import</button>
        </form>

         <!-- Tambah Button -->
         <a href="{{ route('staff.create') }}" class="btn btn-primary">
            Tambah Staf
        </a>

    </div>
</div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>No. Pekerja</th>
                        <th>Emel</th>
                        <th>Kehadiran</th>
                        <th>Kategori Staf</th>
                        <th>Unit/Bahagian</th>
                        <th>Kampus</th>
                        <th>Keahlian Kelab</th>
                        <th>Bukti Bayaran</th>
                        <th>Status</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($staffList) > 0)
                    @foreach ($staffList as $staff)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ ucfirst($staff->name) }}</td>
                        <td>{{ $staff->no_pekerja }}</td>
                        <td>{{ $staff->email }}</td>
                        <td>{{ $staff->attendance }}</td>
                        <td>{{ $staff->category }}</td>
                        <td>{{ $staff->department }}</td>
                        <td>{{ $staff->campus }}</td>
                        <td>{{ $staff->club }}</td>
                        <td>{{ $staff->payment }}</td>
                        <td>
                            @if ($staff->status == 'Pending')
                            <span class="badge bg-warning">Pending</span>
                            @else
                            <span class="badge bg-success">Booked</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-info btn-sm"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kemaskini">
                                <i class="bx bxs-edit"></i>
                            </a>
                            <a href="{{ route('staff.show', $staff->id) }}" class="btn btn-primary btn-sm"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Papar">
                                <i class="bx bx-show"></i>
                            </a>
                            <a type="button" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                data-bs-title="Padam">
                                <span class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $staff->id }}"><i
                                        class="bx bx-trash"></i></span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <td colspan="4">Tiada rekod</td>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex justify-content-between">
            <div class="d-flex align-items-center">
                <span class="mr-2 mx-1">Jumlah rekod per halaman</span>
                <form action="{{ route('staff') }}" method="GET" id="perPageForm">
                    <select name="perPage" id="perPage" class="form-select"
                        onchange="document.getElementById('perPageForm').submit()">
                        <option value="10" {{ Request::get('perPage') == '10' ? 'selected' : '' }}>10</option>
                        <option value="20" {{ Request::get('perPage') == '20' ? 'selected' : '' }}>20</option>
                        <option value="30" {{ Request::get('perPage') == '30' ? 'selected' : '' }}>30</option>
                    </select>
                </form>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <div class="mx-1 mt-2">{{ $staffList->firstItem() }} – {{ $staffList->lastItem() }} dari
                    {{ $staffList->total() }} rekod
                </div>
                <div>{{ $staffList->links() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@foreach ($staffList as $staff)
<div class="modal fade" id="deleteModal{{ $staff->id }}" tabindex="-1" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Pengesahan Padam Rekod</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @isset($staff)
                Adakah anda pasti ingin memadam rekod <span style="font-weight: 600;">
                    {{ ucfirst($staff->name) }}</span>?
                @else
                Tiada rekod
                @endisset
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                @isset($staff)
                <form class="d-inline" method="POST" action="{{ route('staff.destroy', $staff->id) }}">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-danger">Padam</button>
                </form>
                @endisset
            </div>
        </div>
    </div>
</div>
@endforeach
<!--end page wrapper -->
@endsection