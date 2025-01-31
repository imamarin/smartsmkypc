<div class="table-responsive">
    <table class="table display nowrap" id="example">
        <thead>
            <tr colspan=5>
                <th colspan="5" class="text-center">Data Kelas</th>
            </tr>
            <tr>
                <th style="width: 5%">#</th>
                <th>Tahun Ajaran</th>
                <th>Kelas</th>
                <th>Tingkat</th>
                <th>Jurusan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kelas as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->tahunajaran->awal_tahun_ajaran }}/{{ $item->tahunajaran->akhir_tahun_ajaran }}
                    ({{ $item->tahunajaran->semester == 'ganjil' ? 'Ganjil' : 'Genap' }})
                </td>
                <td>{{ $item->kelas }}</td>
                <td>{{ ($item->tingkat == 'X' ? '10' : $item->tingkat == 'XI') ? '11' : '12' }}
                </td>
                <td>{{ $item->jurusan->jurusan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
