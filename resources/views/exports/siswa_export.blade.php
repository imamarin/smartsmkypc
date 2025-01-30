<div class="table-responsive">
    <table class="table display nowrap">
        <thead>
            <tr colspan=18>
                <th colspan=18>Data Siswa</th>
            </tr>
            <tr>
                <th>No</th>
                <th>NIS/NISN</th>
                <th>Nama</th>
                <th>Tempat, Tanggal Lahir</th>
                <th>Jenis Kelamin</th>
                <th>NIK</th>
                <th>Asal Sekolah</th>
                <th>Nama Ayah</th>
                <th>Nama Ibu</th>
                <th>Pekerjaan Ayah</th>
                <th>Pekerjaan Ibu</th>
                <th>Alamat ortu</th>
                <th>Alamat Siswa</th>
                <th>No HP Ortu</th>
                <th>No HP Siswa</th>
                <th>Diterima Tanggal</th>
                <th>Status</th>
                <th>Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswa as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->nis }}/{{ $item->nisn }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">{{ $item->nama }}
                    </td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->tempat_lahir }},
                        {{ $item->tanggal_lahir }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->jenis_kelamin }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">{{ $item->nik }}
                    </td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->asal_sekolah }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->nama_ayah }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->nama_ibu }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->pekerjaan_ayah }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->pekerjaan_ibu }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->alamat_ortu }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->alamat_siswa }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->no_hp_siswa ?? '-' }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->no_hp_ortu ?? '-' }}</td>
                    <td>{{ $item->diterima_tanggal }}</td>
                    <td>
                        {{ $item->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                    </td>
                    <td>{{ $item->kelas }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
