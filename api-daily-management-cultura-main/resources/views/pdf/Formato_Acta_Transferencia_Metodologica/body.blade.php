<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex,nofollow" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0;" />
    <style type="text/css">
        body {
            font-family: Verdana;
            margin-top: 150px;
        }

        .even {
            background: #fbf8f0;
        }

        .odd {
            background: #fefcf9;
        }

        .page
        {
            page-break-after: always;
            page-break-inside: avoid;
        }

        .ocultarcolumnas{
            visibility:hidden;
        }

        table , td, th {
            border: 1px solid #595959;
            border-collapse: collapse;
        }
        td, th {
            padding: 3px;
            width: 30px;
            height: 25px;
        }
        th {
            background: #f0e6cc;
        }
        .even {
            background: #fbf8f0;
        }
        .odd {
            background: #fefcf9;
        }
        p{
            font-size:16px;
        }
    </style>
</head>

<body class="img1">
    <div class="">
        <tr>
            <table width="100%" style="">
                <thead>
                    <tr>
                        <td class="ocultarcolumnas" style="width: 2%">1</td>
                        <td class="ocultarcolumnas" style="width: 2%">2</td>
                        <td class="ocultarcolumnas" style="width: 2%">3</td>
                        <td class="ocultarcolumnas" style="width: 2%">4</td>
                        <td class="ocultarcolumnas" style="width: 2%">5</td>
                        <td class="ocultarcolumnas" style="width: 2%">6</td>
                        <td class="ocultarcolumnas" style="width: 2%">7</td>
                        <td class="ocultarcolumnas" style="width: 2%">8</td>
                        <td class="ocultarcolumnas" style="width: 2%">9</td>
                        <td class="ocultarcolumnas" style="width: 2%">10</td>
                        <td class="ocultarcolumnas" style="width: 2%">11</td>
                        <td class="ocultarcolumnas" style="width: 2%">12</td>
                        <td class="ocultarcolumnas" style="width: 2%">13</td>
                        <td class="ocultarcolumnas" style="width: 2%">14</td>
                        <td class="ocultarcolumnas" style="width: 2%">15</td>
                        <td class="ocultarcolumnas" style="width: 2%">16</td>
                        <td class="ocultarcolumnas" style="width: 2%">17</td>
                        <td class="ocultarcolumnas" style="width: 2%">18</td>
                        <td class="ocultarcolumnas" style="width: 2%">19</td>
                        <td class="ocultarcolumnas" style="width: 2%">20</td>
                        <td class="ocultarcolumnas" style="width: 2%">21</td>
                        <td class="ocultarcolumnas" style="width: 2%">22</td>
                        <td class="ocultarcolumnas" style="width: 2%">23</td>
                        <td class="ocultarcolumnas" style="width: 2%">24</td>
                        <td class="ocultarcolumnas" style="width: 2%">25</td>
                        <td class="ocultarcolumnas" style="width: 2%">26</td>
                        <td class="ocultarcolumnas" style="width: 2%">27</td>
                        <td class="ocultarcolumnas" style="width: 2%">28</td>
                        <td class="ocultarcolumnas" style="width: 2%">29</td>
                        <td class="ocultarcolumnas" style="width: 2%">30</td>
                        <td class="ocultarcolumnas" style="width: 2%">31</td>
                        <td class="ocultarcolumnas" style="width: 2%">32</td>
                        <td class="ocultarcolumnas" style="width: 2%">33</td>
                        <td class="ocultarcolumnas" style="width: 2%">34</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="34" style="background: #164E63">
                            <h3 style="text-align: center; color:#ffffff;">ACTA DE TRANSFERENCIA METODOLÓGICA </h3>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="34" style="background: #164E63">
                            <h3 style="text-align: center; color:#ffffff;">{{ $titulos['Titulo1'] }}</h3>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="17" rowspan="2">
                            <p style="text-align: left;margin-top: -1%;margin-bottom:0%">
                                <b>1.1 Gestor cultural:</b> {{ $data->user->name }}
                            </p>
                        </td>
                        <td colspan="17" rowspan="2">
                            <p style="text-align: left;margin-top: -1%;margin-bottom:0%">
                                <b>1.7 Transferencia N°:</b>
                            </p>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="10" rowspan="6">
                            <p style="text-align: left;margin-top: -1%;margin-bottom:0%">
                                <b>1.2 Número de monitores culturales:</b> {{ $data->assistants->count() }}
                            </p>
                        </td>
                        <td colspan="12" rowspan="2">
                            <p style="text-align: left;margin-top: -1%;margin-bottom:0%">
                                <b>1.3 Lugar:</b> {{ $data->place }}
                            </p>
                        </td>
                        <td colspan="12" rowspan="2">
                            <p style="text-align: left;margin-top: -1%;margin-bottom:0%">
                                <b>1.8 Fecha:</b> {{ $data->activity_date }}
                            </p>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="12" rowspan="2">
                            <p style="text-align: left;margin-top: -1%;margin-bottom:0%">
                                <b>1.4 Hora inicio:</b> {{ $data->start_time }}
                            </p>
                        </td>
                        <td colspan="12" rowspan="2">
                            <p style="text-align: left;margin-top: -1%;margin-bottom:0%">
                                <b>1.5 Hora final:</b> {{ $data->final_hour }}
                            </p>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="12" rowspan="2">
                            <p style="text-align: left;margin-top: -1%;margin-bottom:0%">
                                <b>1.6 Experticia artística:</b> {{ $data->expertise->name }}
                            </p>
                        </td>
                        <td colspan="12" rowspan="2">
                            <p style="text-align: left;margin-top: -1%;margin-bottom:0%">
                                <b>1.9 NAC:</b> {{ $data->nac->name }}
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" style="">
                <thead>
                    <tr>
                        <td class="ocultarcolumnas" style="width: 2%">1</td>
                        <td class="ocultarcolumnas" style="width: 2%">2</td>
                        <td class="ocultarcolumnas" style="width: 2%">3</td>
                        <td class="ocultarcolumnas" style="width: 2%">4</td>
                        <td class="ocultarcolumnas" style="width: 2%">5</td>
                        <td class="ocultarcolumnas" style="width: 2%">6</td>
                        <td class="ocultarcolumnas" style="width: 2%">7</td>
                        <td class="ocultarcolumnas" style="width: 2%">8</td>
                        <td class="ocultarcolumnas" style="width: 2%">9</td>
                        <td class="ocultarcolumnas" style="width: 2%">10</td>
                        <td class="ocultarcolumnas" style="width: 2%">11</td>
                        <td class="ocultarcolumnas" style="width: 2%">12</td>
                        <td class="ocultarcolumnas" style="width: 2%">13</td>
                        <td class="ocultarcolumnas" style="width: 2%">14</td>
                        <td class="ocultarcolumnas" style="width: 2%">15</td>
                        <td class="ocultarcolumnas" style="width: 2%">16</td>
                        <td class="ocultarcolumnas" style="width: 2%">17</td>
                        <td class="ocultarcolumnas" style="width: 2%">18</td>
                        <td class="ocultarcolumnas" style="width: 2%">19</td>
                        <td class="ocultarcolumnas" style="width: 2%">20</td>
                        <td class="ocultarcolumnas" style="width: 2%">21</td>
                        <td class="ocultarcolumnas" style="width: 2%">22</td>
                        <td class="ocultarcolumnas" style="width: 2%">23</td>
                        <td class="ocultarcolumnas" style="width: 2%">24</td>
                        <td class="ocultarcolumnas" style="width: 2%">25</td>
                        <td class="ocultarcolumnas" style="width: 2%">26</td>
                        <td class="ocultarcolumnas" style="width: 2%">27</td>
                        <td class="ocultarcolumnas" style="width: 2%">28</td>
                        <td class="ocultarcolumnas" style="width: 2%">29</td>
                        <td class="ocultarcolumnas" style="width: 2%">30</td>
                        <td class="ocultarcolumnas" style="width: 2%">31</td>
                        <td class="ocultarcolumnas" style="width: 2%">32</td>
                        <td class="ocultarcolumnas" style="width: 2%">33</td>
                        <td class="ocultarcolumnas" style="width: 2%">34</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="34" style="background: #164E63">
                            <h3 style="text-align: center; color:#ffffff;">{{ $titulos['Titulo2'] }}</h3>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="13" rowspan="2">
                            <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                <b>2.1 ¿Se cumplió el objetivo de la transferencia metodológica?</b>
                            </p>
                        </td>
                        <td colspan="21" rowspan="2">
                            <p style="text-align: justify;margin-top: 0%;margin-bottom:0%">
                                {{ $data->goals_met }}
                            </p>
                            <p style="text-align: justify;margin-top: 0%;margin-bottom:0%">
                                Explicación:
                            </p>
                            <p style="text-align: justify;margin-top: 0%;margin-bottom:0%">
                                {{ $data->explanation }}
                            </p>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="13" rowspan="12">
                            <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                <b>2.2 Haga una breve descripción de cada tema: conceptual, técnico práctico y pedagógico.</b>
                            </p>
                        </td>
                        <td colspan="21" rowspan="2">
                            <p style="text-align: justify;margin-top: 0%;margin-bottom:0%">
                                2.2.1 Pedagógico
                            </p>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="21" rowspan="2">
                            <p style="text-align: justify;margin-top: 0%;margin-bottom:0%">
                                {{ $data->pedagogical_comments }}
                            </p>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="21" rowspan="2">
                            <p style="text-align: justify;margin-top: 0%;margin-bottom:0%">
                                2.2.2 Técnico práctico
                            </p>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="21" rowspan="2">
                            <p style="text-align: justify;margin-top: 0%;margin-bottom:0%">
                                {{ $data->technical_practical_comments }}
                            </p>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="21" rowspan="2">
                            <p style="text-align: justify;margin-top: 0%;margin-bottom:0%">
                                2.2.3 Metodólogico
                            </p>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="21" rowspan="2">
                            <p style="text-align: justify;margin-top: 0%;margin-bottom:0%">
                                {{ $data->methodological_comments }}
                            </p>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="13" rowspan="2">
                            <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                <b>2.3 Otros y observaciones</b>
                            </p>
                        </td>
                        <td colspan="21" rowspan="2">
                            <p style="text-align: justify;margin-top: 0%;margin-bottom:0%">
                                {{ $data->others_observations }}
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" style="" class="page">
                <thead>
                    <tr>
                        <td class="ocultarcolumnas" style="width: 2%">1</td>
                        <td class="ocultarcolumnas" style="width: 2%">2</td>
                        <td class="ocultarcolumnas" style="width: 2%">3</td>
                        <td class="ocultarcolumnas" style="width: 2%">4</td>
                        <td class="ocultarcolumnas" style="width: 2%">5</td>
                        <td class="ocultarcolumnas" style="width: 2%">6</td>
                        <td class="ocultarcolumnas" style="width: 2%">7</td>
                        <td class="ocultarcolumnas" style="width: 2%">8</td>
                        <td class="ocultarcolumnas" style="width: 2%">9</td>
                        <td class="ocultarcolumnas" style="width: 2%">10</td>
                        <td class="ocultarcolumnas" style="width: 2%">11</td>
                        <td class="ocultarcolumnas" style="width: 2%">12</td>
                        <td class="ocultarcolumnas" style="width: 2%">13</td>
                        <td class="ocultarcolumnas" style="width: 2%">14</td>
                        <td class="ocultarcolumnas" style="width: 2%">15</td>
                        <td class="ocultarcolumnas" style="width: 2%">16</td>
                        <td class="ocultarcolumnas" style="width: 2%">17</td>
                        <td class="ocultarcolumnas" style="width: 2%">18</td>
                        <td class="ocultarcolumnas" style="width: 2%">19</td>
                        <td class="ocultarcolumnas" style="width: 2%">20</td>
                        <td class="ocultarcolumnas" style="width: 2%">21</td>
                        <td class="ocultarcolumnas" style="width: 2%">22</td>
                        <td class="ocultarcolumnas" style="width: 2%">23</td>
                        <td class="ocultarcolumnas" style="width: 2%">24</td>
                        <td class="ocultarcolumnas" style="width: 2%">25</td>
                        <td class="ocultarcolumnas" style="width: 2%">26</td>
                        <td class="ocultarcolumnas" style="width: 2%">27</td>
                        <td class="ocultarcolumnas" style="width: 2%">28</td>
                        <td class="ocultarcolumnas" style="width: 2%">29</td>
                        <td class="ocultarcolumnas" style="width: 2%">30</td>
                        <td class="ocultarcolumnas" style="width: 2%">31</td>
                        <td class="ocultarcolumnas" style="width: 2%">32</td>
                        <td class="ocultarcolumnas" style="width: 2%">33</td>
                        <td class="ocultarcolumnas" style="width: 2%">34</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="34" style="background: #164E63">
                            <h3 style="text-align: center; color:#ffffff;">{{ $titulos['Titulo3'] }}</h3>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="17" rowspan="2">
                            <p style="text-align: left;margin-top: -1%;margin-bottom:0%">
                                <b>3.1 Foto que evidencia el desarrollo de la transferencia metodológica realizada</b>
                            </p>
                        </td>
                        <td colspan="17" rowspan="2">
                            <p style="text-align: left;margin-top: -1%;margin-bottom:0%">
                                <b>3.2 Foto que evidencie el listado de asistencia con las personas conectadas en la transferencia virtual</b>
                            </p>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="17" rowspan="2">
                            <center>
                                @php
                                    $im1 = imagecreatefromwebp(storage_path().'/app/public/'. $data->place_file1);
                                    imagejpeg($im1, storage_path().'/app/public/'.substr( $data->place_file1,-5).'.jpeg', 100);
                                @endphp
                                <img style="width:60%;height: 300px;" src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path().'/app/public/'.substr($data->place_file1,-5).'.jpeg'))}}">
                            </center>
                        </td>
                        <td colspan="17" rowspan="2">
                            <center>
                                @php
                                    $im2 = imagecreatefromwebp(storage_path().'/app/public/'. $data->place_file2);
                                    imagejpeg($im2, storage_path().'/app/public/'.substr( $data->place_file2,-5).'.jpeg', 100);
                                @endphp
                                <img style="width:60%;height: 300px;" src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path().'/app/public/'.substr($data->place_file2,-5).'.jpeg'))}}">
                            </center>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" style="">
                <thead>
                    <tr>
                        <td class="ocultarcolumnas" style="width: 2%">1</td>
                        <td class="ocultarcolumnas" style="width: 2%">2</td>
                        <td class="ocultarcolumnas" style="width: 2%">3</td>
                        <td class="ocultarcolumnas" style="width: 2%">4</td>
                        <td class="ocultarcolumnas" style="width: 2%">5</td>
                        <td class="ocultarcolumnas" style="width: 2%">6</td>
                        <td class="ocultarcolumnas" style="width: 2%">7</td>
                        <td class="ocultarcolumnas" style="width: 2%">8</td>
                        <td class="ocultarcolumnas" style="width: 2%">9</td>
                        <td class="ocultarcolumnas" style="width: 2%">10</td>
                        <td class="ocultarcolumnas" style="width: 2%">11</td>
                        <td class="ocultarcolumnas" style="width: 2%">12</td>
                        <td class="ocultarcolumnas" style="width: 2%">13</td>
                        <td class="ocultarcolumnas" style="width: 2%">14</td>
                        <td class="ocultarcolumnas" style="width: 2%">15</td>
                        <td class="ocultarcolumnas" style="width: 2%">16</td>
                        <td class="ocultarcolumnas" style="width: 2%">17</td>
                        <td class="ocultarcolumnas" style="width: 2%">18</td>
                        <td class="ocultarcolumnas" style="width: 2%">19</td>
                        <td class="ocultarcolumnas" style="width: 2%">20</td>
                        <td class="ocultarcolumnas" style="width: 2%">21</td>
                        <td class="ocultarcolumnas" style="width: 2%">22</td>
                        <td class="ocultarcolumnas" style="width: 2%">23</td>
                        <td class="ocultarcolumnas" style="width: 2%">24</td>
                        <td class="ocultarcolumnas" style="width: 2%">25</td>
                        <td class="ocultarcolumnas" style="width: 2%">26</td>
                        <td class="ocultarcolumnas" style="width: 2%">27</td>
                        <td class="ocultarcolumnas" style="width: 2%">28</td>
                        <td class="ocultarcolumnas" style="width: 2%">29</td>
                        <td class="ocultarcolumnas" style="width: 2%">30</td>
                        <td class="ocultarcolumnas" style="width: 2%">31</td>
                        <td class="ocultarcolumnas" style="width: 2%">32</td>
                        <td class="ocultarcolumnas" style="width: 2%">33</td>
                        <td class="ocultarcolumnas" style="width: 2%">34</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="34" style="background: #164E63">
                            <h3 style="text-align: center; color:#ffffff;">{{ $titulos['Titulo4'] }}</h3>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" rowspan="2">
                            <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                <b>No</b>
                            </p>
                        </td>
                        <td colspan="4" rowspan="2">
                            <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                <b>NAC</b>
                            </p>
                        </td>
                        <td colspan="6" rowspan="2">
                            <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                <b>Nombre</b>
                            </p>
                        </td>
                        <td colspan="6" rowspan="2">
                            <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                <b>Documento de <br> identidad</b>
                            </p>
                        </td>
                        <td colspan="4" rowspan="2">
                            <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                <b>Cargo</b>
                            </p>
                        </td>
                        <td colspan="4" rowspan="2">
                            <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                <b>Teléfono</b>
                            </p>
                        </td>
                        <td colspan="6" rowspan="2">
                            <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                <b>Email</b>
                            </p>
                        </td>
                    </tr>
                    @foreach ($data->assistants as $key => $assistants)
                        <tr></tr>
                        <tr>
                            <td colspan="4" rowspan="2">
                                <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                    {{ $key + 1 }}
                                </p>
                            </td>
                            <td colspan="4" rowspan="2">
                                <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                    {{-- $addedWizards->assistant->nac->name --}}
                                </p>
                            </td>
                            <td colspan="6" rowspan="2">
                                <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                    {{ $assistants->name }}
                                </p>
                            </td>
                            <td colspan="6" rowspan="2">
                                <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                    {{-- {{ $addedWizards->assistant->assistant_document_number }} --}}
                                </p>
                            </td>
                            <td colspan="4" rowspan="2">
                                <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                    {{-- {{ $addedWizards->assistant->assistant_position }} --}}
                                </p>
                            </td>
                            <td colspan="4" rowspan="2">
                                <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                    {{-- {{ $addedWizards->assistant->assistant_phone }} --}}
                                </p>
                            </td>
                            <td colspan="6" rowspan="2">
                                <p style="text-align: center;margin-top: -1%;margin-bottom:0%">
                                    {{ $assistants->email }}
                                </p>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </tr>
    </div>
</body>

</html>
