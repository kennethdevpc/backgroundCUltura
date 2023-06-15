<table>
    <thead>
    <tr>
        <th style="width: 30px;text-align:center"><b>It</b></th>
        <th style="width: 350px;text-align:center"><b>Nombre</b></th>
        <th style="width: 150px;text-align:center"><b>Cedula</b></th>
        <th style="width: 200px;text-align:center"><b>Rol</b></th>
        @php
            $fechaInicio=strtotime($dateInicio);
            $fechaFin=strtotime($dateFin);
        @endphp
        @for ($i = $fechaInicio; $i <= $fechaFin; $i+=86400)
            <th style="width: 95px;text-align:center"><b>{{ date("Y-m-d", $i) }}</b></th>
        @endfor
    </tr>
    </thead>
    <tbody>
    @foreach($invoices as $key => $invoice)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $invoice->name }}</td>
            <td>{{ $invoice->profile->document_number }}</td>
            <td>{{ $invoice->roles[0]->name }}</td>
            @php
                $fechaInicio=strtotime($dateInicio);
                $fechaFin=strtotime($dateFin);
            @endphp
            @for ($i = $fechaInicio; $i <= $fechaFin; $i+=86400)
                @php
                    $conteo = 0;
                @endphp
                @foreach ($invoice->loginaccess as $login)
                    @if ($i == strtotime($login->date))
                        @php
                            $conteo++;
                        @endphp
                    @endif
                @endforeach
                <td>{{ $conteo }}</td>
            @endfor
        </tr>
    @endforeach
    </tbody>
</table>
