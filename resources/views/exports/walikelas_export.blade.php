
<table>
    <thead>
        <tr colspan=4>
            <th colspan=5 style="text-align: center;font-weight: bold;"><b>DATA WALIKELAS {{ $tahunajaran->awal_tahun_ajaran }}/{{ $tahunajaran->akhir_tahun_ajaran }}</b></th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th style="border: 1px solid black;font-weight: bold;">No</th>
            <th style="border: 1px solid black;font-weight: bold;">nip</th>
            <th style="border: 1px solid black;font-weight: bold;">Walikelas</th>
            <th style="border: 1px solid black;font-weight: bold;">Kelas</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($walikelas as $key => $subject)
            <tr>
                <td style="width: 50px;border: 1px solid black;">{{ $loop->iteration }} </td>
                <td style="width: 200px;mso-number-format: '\@';border: 1px solid black;">{{ $subject->walikelas[0]->staf->nip ?? '-' }}</td>
                <td style="width: 300px;border: 1px solid black;">{{ $subject->walikelas[0]->staf->nama ?? '-' }}</td>
                <td style="width: 100px;border: 1px solid black;">{{ $subject->kelas }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


