<table>
    <thead>
        <tr>
            <th>NIK</th>
            <th>Nama</th>
            <th>NOPASIEN</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $log)
            <tr>
                <td>{{ $log->regpas->pasien->NOKTP ?? 'N/A' }}</td>
                <td>{{ $log->regpas->pasien->NAMAPASIEN ?? 'N/A' }}</td>
                <td>{{ $log->regpas->pasien->NOPASIEN ?? 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
