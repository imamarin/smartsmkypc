<div class="table-responsive">
    <table class="table display nowrap" id="example">
        <thead>
            <tr>
                <th style="width: 5%">NO</th>
                <th>Tahun Ajaran</th>
                <th>Jurusan</th>
                <th>Kompetensi</th>
                <th>Program Keahlian</th>
                <th>Bidang Keahlian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jurusan as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->tahunajaran->awal_tahun_ajaran }}/{{ $item->tahunajaran->akhir_tahun_ajaran }}
                        ({{ $item->tahunajaran->semester == 'ganjil' ? 'Ganjil' : 'Genap' }})
                    </td>
                    <td>{{ $item->jurusan }}</td>
                    <td>{{ $item->kompetensi }}</td>
                    <td>{{ $item->program_keahlian }}</td>
                    </td>
                    <td>{{ $item->bidang_keahlian }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
