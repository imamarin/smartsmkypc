
<table>
    <thead>
        <tr>
            <th colspan=4 style="text-align: center;font-weight: bold;"><b>DATA REKAP PRESENSI GURU</b></th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th style="border: 1px solid black;font-weight: bold;">No</th>
            <th style="border: 1px solid black;font-weight: bold;">NIP</th>
            <th style="border: 1px solid black;font-weight: bold;">Nama Staf</th>
            <th style="border: 1px solid black;font-weight: bold;">Persentase Kehadiran (%)</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($staf as $subject)
            <tr>
                <td style="width: 50px;border: 1px solid black;">{{ $loop->iteration }}</td>
                <td style="width: 100px;border: 1px solid black;">{{ $subject->nip }}</td>
                <td style="width: 200px;border: 1px solid black;">{{ $subject->nama }}</td>
                <td style="width: 200px;border: 1px solid black;">
                    @php
                    
                        $jmlPertemuan = $jumlahPertemuan[$subject->nip] ?? 0;
                        $totPertemuan = $totalPertemuan[$subject->nip] ?? 0;

                        if( $jmlPertemuan > 0 && $totPertemuan > 0 ){
                            $persentasi_hadir = round($jumlahPertemuan[$subject->nip] / $totalPertemuan[$subject->nip] * 100);
                        }else{
                            $persentasi_hadir = 0;
                        }
                    @endphp
                    {{ $persentasi_hadir }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


