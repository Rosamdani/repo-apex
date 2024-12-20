<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>

<body>
    <div class="mx-1">
        <table>
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Jml Soal</th>
                    <th>Benar</th>
                    <th>Salah</th>
                    <th>Tidak Dikerjakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bidang as $bidangData)
                <tr>
                    <td scope="row" style="color: #D76B00FF">{{ $bidangData->kategori }}</td>
                    <td>{{ $bidangData->total_soal }}</td>
                    <td>{{ $bidangData->benar }}</td>
                    <td>{{ $bidangData->salah }}</td>
                    <td>{{ $bidangData->tidak_dikerjakan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>