<div class="table-responsive">
    <table class="table display nowrap" id="example">
        <thead>
            <tr colspan="4">
                <th colspan="4" align="center"><b>SISWA KELAS {{ strtoupper($kelas) }}</b></th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th style="border: 1px solid black;">No</th>
                <th style="border: 1px solid black;">Nisn</th>
                <th style="border: 1px solid black;">Nama Siswa</th>
                <th style="border: 1px solid black;">JK</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rombel as $key => $item)
            <tr>
                <td style="width: 50px;border: 1px solid black;">{{ $loop->iteration }} </td>
                <td style="width: 100px;mso-number-format: '\@';border: 1px solid black;">{{ $item->siswa->nisn }}</td>
                <td style="width: 220px;border: 1px solid black;">{{ $item->siswa->nama }}</td>
                <td style="width: 50px;border: 1px solid black;">
                    {{ $item->siswa->jenis_kelamin }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>



