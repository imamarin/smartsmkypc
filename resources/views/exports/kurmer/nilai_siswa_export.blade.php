
<table>
    <thead>
        <tr colspan=4>
            <th colspan=4 style="text-align: center"><b>NILAI SISWA KELAS {{ $nilairaport->kelas->kelas }}</b></th>
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
            <th style="border: 1px solid black;">Nilai Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($siswa as $key => $subject)
            <tr>
                <td style="width: 50px;border: 1px solid black;">{{ $loop->iteration }} </td>
                <td style="width: 100px;mso-number-format: '\@';border: 1px solid black;">{{ $subject->nisn }}</td>
                <td style="width: 200px;border: 1px solid black;">{{ $subject->nama }}</td>
                <td style="width: 100px;border: 1px solid black;">
                    {{ $nilai_pengetahuan[$subject->nisn] ?? '' }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


