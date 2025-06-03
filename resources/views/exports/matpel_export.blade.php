
<table>
    <thead>
        <tr colspan=4>
            <th colspan=5 style="text-align: center;font-weight: bold;"><b>DATA MATA PELAJARAN</b></th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th style="border: 1px solid black;font-weight: bold;">No</th>
            <th style="border: 1px solid black;font-weight: bold;">Kode Matpel</th>
            <th style="border: 1px solid black;font-weight: bold;">Mata Pelajaran</th>
            <th style="border: 1px solid black;font-weight: bold;">Kelompok</th>
            <th style="border: 1px solid black;font-weight: bold;">Gabungan Mata Pelajaran</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($matpel as $key => $subject)
            <tr>
                <td style="width: 50px;border: 1px solid black;">{{ $loop->iteration }} </td>
                <td style="width: 100px;border: 1px solid black;">{{ $subject->kode_matpel }}</td>
                <td style="width: 300px;border: 1px solid black;">{{ $subject->matpel }}</td>
                <td style="width: 200px;border: 1px solid black;">{{ $subject->kelompok }}</td>
                <td style="width: 300px;border: 1px solid black;">{{ $subject->parent->matpel ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


