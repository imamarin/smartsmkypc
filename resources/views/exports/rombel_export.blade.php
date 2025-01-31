<div class="table-responsive">
    <table class="table display nowrap" id="example">
        <thead>
            <tr colspan="5">
                <th colspan="5">Data Rombel</th>
            </tr>
            <tr>
                <th>No</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Jumlah Siswa</th>
                <th>Walikelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kelas as $key => $item)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $item->kelas }}</td>
                <td>{{ $item->jurusan->jurusan }}</td>
                <td>{{ $item->rombel->count() }}</td>
                <td>-</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
