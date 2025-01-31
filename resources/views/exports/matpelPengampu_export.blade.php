<div class="table-responsive">
    <table class="table display nowrap" id="example">
        <thead>
            <tr colspan="3">
                <th colspan="3" class="text-center">Data Mata Pelajaran</th>
            </tr>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>Tahun Ajaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matpelpengampu as $subject)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $subject->matpel->matpel }}</td>
                <td>{{ $subject->tahunajaran->awal_tahun_ajaran }}/{{ $subject->tahunajaran->akhir_tahun_ajaran }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
