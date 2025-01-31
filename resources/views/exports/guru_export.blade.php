<div class="table-responsive">
    <table class="table display nowrap" id="example">
        <thead>
            <tr colspan=18>
                <th colspan=18>Data Guru</th>
            </tr>
            <tr>
                <th>No</th>
                <th>Kode Guru</th>
                <th>Nama</th>
                <th>Tempat, Tanggal Lahir</th>
                <th>Jenis Kelamin</th>
                <th>NUPTK</th>
                <th>NIP</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($guru as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->kode_guru }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">{{ $item->nama }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->tempat_lahir }},
                        {{ $item->tanggal_lahir }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->jenis_kelamin }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->nuptk ?? '-' }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->nip }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->alamat }}</td>
                    <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                        {{ $item->no_hp }}</td>
                    <td>
                        {{ $item->status == 1 ? 'Aktif' : 'Tidak Aktif' }}

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
