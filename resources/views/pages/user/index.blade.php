@extends('layouts.master')
@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pengurusan Pengguna</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Senarai Pengguna</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('user.trash') }}">
                <button type="button" class="btn btn-primary mt-2 mt-lg-0">Senarai Rekod Dipadam</button>
            </a>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">Senarai Pengguna</h6>
    <hr />
    <div class="card">
        <div class="card-body">
            <div class="d-lg-flex align-items-center mb-4 gap-3">
                <div class="position_id-relative">
                    <form action="{{ route('user.search') }}" method="GET">
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
                <div class="ms-auto">
                    <a href="{{ route('user.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                        <i class="bx bxs-plus-square"></i> Tambah Pengguna
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>No. Staf</th>
                            <th>Kampus</th>
                            <th>Peranan</th>
                            <th>Status</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($userList) > 0)
                            @foreach ($userList as $user)
                                <tr>
                                    <td>{{ ($userList->currentPage() - 1) * $userList->perPage() + $loop->iteration }}</td>
                                    <td>{{ ucfirst($user->name) }}</td>
                                    <td>{{ $user->staff_id }}</td>
                                    <td>{{ $user->campus->name ?? null }}</td>
                                    <td>
                                        @if ($user->roles->count() === 1)
                                            {{ ucwords(str_replace('-', ' ', $user->roles->first()->name)) }}
                                        @else
                                            <ul>
                                                @foreach ($user->roles as $role)
                                                    <li>{{ ucwords(str_replace('-', ' ', $role->name)) }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->publish_status == 'Aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-info btn-sm"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kemaskini">
                                            <i class="bx bxs-edit"></i>
                                        </a>
                                        <a href="{{ route('user.show', $user->id) }}" class="btn btn-primary btn-sm"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Papar">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a type="button" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-title="Padam">
                                            <span class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $user->id }}"><i
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
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="mr-2 mx-1">Jumlah rekod per halaman</span>
                    <form action="{{ route('user.search') }}" method="GET" id="perPageForm"
                        class="d-flex align-items-center">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="perPage" id="perPage" class="form-select form-select-sm"
                            onchange="document.getElementById('perPageForm').submit()">
                            <option value="10" {{ Request::get('perPage') == '10' ? 'selected' : '' }}>10</option>
                            <option value="20" {{ Request::get('perPage') == '20' ? 'selected' : '' }}>20</option>
                            <option value="30" {{ Request::get('perPage') == '30' ? 'selected' : '' }}>30</option>
                        </select>
                    </form>
                </div>

                <div class="d-flex justify-content-end align-items-center">
                    <span class="mx-2 mt-2 small text-muted">
                        Menunjukkan {{ $userList->firstItem() ?? '0' }} hingga {{ $userList->lastItem() ?? '0' }} daripada
                        {{ $userList->total() }} rekod
                    </span>
                    <div class="pagination-wrapper">
                        {{ $userList->appends(['search' => request('search'), 'perPage' => $perPage])->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @foreach ($userList as $user)
        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Pengesahan Padam Rekod</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @isset($user)
                            Adakah anda pasti ingin memadam rekod <span style="font-weight: 600;">
                                {{ ucfirst($user->name) }}</span>?
                        @else
                            Tiada rekod
                        @endisset
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        @isset($user)
                            <form class="d-inline" method="POST" action="{{ route('user.destroy', $user->id) }}">
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
