@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Edit Honor Pegawai</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center" style="background-color: #494f4f ;">
                    <h4 class="card-title text-white">Edit Honor Pengawai</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('honorium-pegawai.kelola.update', Crypt::encrypt($honorDetail->id)) }}" method="POST">
                        @csrf
                        <h5 class="mb-3">Pilih Staf</h5>
                        <div class="mb-4">
                            <select class="form-select" aria-label="Nama Guru" name="nip">
                                @foreach ($staf as $item)
                                <option value="{{ $item->nip }}" {{ $item->nip == $honorDetail->nip ? 'selected' : ''  }}>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-secondary mb-4">
                                <div class="card-header fw-bold">Honor Guru</div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Jumlah Jam</label>
                                            <input type="number" class="form-control" name="jml_jam" placeholder="Masukan Jumlah Jam" value="{{ $honorDetail->jml_jam }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Bonus Kehadiran</label>
                                            <input type="number" class="form-control" name="bonus_hdr" placeholder="Masukan Bonus Kehadiran" value="{{ $honorDetail->bonus_hdr }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Yayasan</label>
                                            <input type="number" class="form-control" name="yayasan" placeholder="Masukan Jumlah Yayasan" value="{{ $honorDetail->yayasan }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tunjangan Masa Bakti</label>
                                            <input type="number" class="form-control" name="tun_jab_bak" placeholder="Masukan Tunjangan Masa Bakti" value="{{ $honorDetail->tun_jab_bak }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tunjangan Jabatan</label>
                                            <input type="number" class="form-control" name="tunjab" placeholder="Masukan Tunjangan Jabatan" value="{{ $honorDetail->tunjab }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Honor</label>
                                            <input type="number" class="form-control" name="honor" placeholder="Masukan Honor" value="{{ $honorDetail->honor }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Subsidi Non Sertifikasi</label>
                                            <input type="number" class="form-control" name="sub_non_ser" placeholder="Masukan Subsidi Non Sertifikasi" value="{{ $honorDetail->sub_non_ser }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jumlah Honor Guru</label>
                                            <input type="number" class="form-control" name="jml" placeholder="Masukan Jumlah Honor Guru" value="{{ $honorDetail->jml }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <!-- Potongan Honor -->
                            <div class="col-md-6">
                                <div class="card border-secondary mb-4">
                                    <div class="card-header fw-bold text-secondary">Potongan Honor</div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Tabungan</label>
                                                <input type="number" class="form-control" name="tabungan" placeholder="Masukan Jumlah Tabungan" value="{{ $honorDetail->tabungan }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Arisan</label>
                                                <input type="number" class="form-control" name="arisan" placeholder="Masukan Jumlah Arisan" value="{{ $honorDetail->arisan }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Qurban</label>
                                                <input type="number" class="form-control" name="qurban" placeholder="Masukan Jumlah Qurban" value="{{ $honorDetail->qurban }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kas 1</label>
                                                <input type="number" class="form-control" name="kas_1" placeholder="Masukan Jumlah Kas 1" value="{{ $honorDetail->kas_1 }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kas 2</label>
                                                <input type="number" class="form-control" name="kas_2" placeholder="Masukan Jumlah Kas 2"  value="{{ $honorDetail->kas_2 }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Lainnya</label>
                                                <input type="number" class="form-control" name="lainnya" placeholder="Masukan Jumlah Lainnya" value="{{ $honorDetail->lainnya }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <!-- Total Honor Guru -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Total Honor Guru</label>
                                <input type="number" class="form-control" name="jum_tal" placeholder="Masukan Total Honor Guru" value="{{ $honorDetail->jum_tal }}">
                            </div>
    
                            <button type="submit" class="btn btn-primary">SIMPAN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection