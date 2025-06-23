<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tagihan Keuangan</title>
    <style>
		table.tagihan{
			border-right: #000000 solid 1px;
			border-bottom: #000000 solid 1px;
		}

		table.tagihan tr td,
		table.tagihan tr th {
			border: #000000 solid 1px;
			border-right: none;
			border-bottom: none;
			padding: 8px;
		}

		table.tagihan tr td,
		table.tagihan tr th {
			border: #000000 solid 1px;
			border-right: none;
			border-bottom: none;
		}

		body {
			font-size: 12px;
		}
        @media print {
            @page {
                size: A4 portrait;
            }
        }
    </style>
</head>
<body>
    <center><h2>TAGIHAN KEUANGAN SISWA</h2></center>
    <table>
        <tr>
            <td style="width: 100px">NISN</td>
            <td>: {{ $siswa->siswa->nisn }}</td>
        </tr>
        <tr>
            <td>NAMA SISWA</td>
            <td>: {{ $siswa->siswa->nama }}</td>
        </tr>
        <tr>
            <td>KELAS</td>
            <td>: {{ $siswa->kelas->kelas }}</td>
        </tr>
    </table>

    <br>
    <table class="tagihan" style="width: 100%">
        <tr>
            <th>NO</th>
            <th>NAMA KEUANGAN</th>
            <th>BIAYA</th>
            <th>SISA TAGIHAN</th>
            <th>KETERANGAN</th>
        </tr>
        @foreach ($keuangan as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->keuangan }}</td>
                <td>{{ $item->biaya }}</td>
                <td>{{ $item->sisa_tagihan }}</td>
                <td>{{ $item->keterangan }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
<script>
    window.print();
</script>