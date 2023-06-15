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
    <body class="">
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
                                <h3 style="text-align: center; color:#ffffff;">{{ $titulos['Titulo1'] }}</h3>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="34" style="background: #164E63">
                                <h3 style="text-align: center; color:#ffffff;">{{ $titulos['Titulo2'] }}</h3>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>1.1 Gestor cultural:</b>
                                </p>
                            </td>
                            <td colspan="9" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{-- $data->benefiary->user_id --}}
                                </p>
                            </td>
                            <td colspan="8" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>1.4 Hora inicio:</b>
                                </p>
                            </td>
                            <td colspan="9" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->start_time }}
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="8" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>1.2 Nodo de atención cultural:</b>
                                </p>
                            </td>
                            <td colspan="9" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->nac->name }}
                                </p>
                            </td>
                            <td colspan="8" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>1.5 Hora final:</b>
                                </p>
                            </td>
                            <td colspan="9" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->final_hour }}
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="17" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>1.3 Fecha:</b>
                                </p>
                            </td>
                            <td colspan="17" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->activity_date }}
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="margin-top:5%">
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
                            <td colspan="10" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>2.1 Objetivo de la jornada:</b>
                                </p>
                            </td>
                            <td colspan="24" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->target_workday }}
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="10" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>2.2 Tema abordado:</b>
                                </p>
                            </td>
                            <td colspan="24" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->theme }}
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="10" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>2.3 Agenda del día:</b>
                                </p>
                            </td>
                            <td colspan="24" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->schedule_day }}
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="10" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>2.4 Descripción de la jornada:</b>
                                </p>
                            </td>
                            <td colspan="24" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->workday_description }}
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="10" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>2.5 Logros y dificultades:</b>
                                </p>
                            </td>
                            <td colspan="24" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->achievements_difficulties }}
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="10" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>2.6 Alertas:</b>
                                </p>
                            </td>
                            <td colspan="24" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->alerts }}
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="margin-top:5%" class="page">
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
                            <td colspan="17" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>3.1 Foto que evidencia el desarrollo del diálogo cultural:</b>
                                </p>
                            </td>
                            <td colspan="17" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>3.2 Foto que evidencia la participación virtual de los asistentes a la mesa de diálogo:</b>
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="17" rowspan="2">
                                @php
                                    $im1 = imagecreatefromwebp(storage_path().'/app/public/'.$data->place_image1);
                                    imagejpeg($im1, storage_path().'/app/public/'.substr($data->place_image1,-5).'.jpeg', 100);
                                @endphp
                                <center>
                                    <img style="width:70%;height: 200px;" src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path().'/app/public/'.substr($data->place_image1,-5).'.jpeg'))}}">
                                </center>
                            </td>
                            <td colspan="17" rowspan="2">
                                @php
                                    $im2 = imagecreatefromwebp(storage_path().'/app/public/'.$data->place_image2);
                                    imagejpeg($im2, storage_path().'/app/public/'.substr($data->place_image2,-5).'.jpeg', 100);
                                @endphp
                                <center>
                                    <img style="width:60%;height: 450px;" src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path().'/app/public/'.substr($data->place_image2,-5).'.jpeg'))}}">
                                </center>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="margin-top:5%">
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
                                <h3 style="text-align: center; color:#ffffff;">{{ $titulos['Titulo5'] }}</h3>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>No</b>
                                </p>
                            </td>
                            <td colspan="5" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>NAC</b>
                                </p>
                            </td>
                            <td colspan="6" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>Nombre</b>
                                </p>
                            </td>
                            <td colspan="5" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>Documento de identidad</b>
                                </p>
                            </td>
                            <td colspan="4" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>Cargo</b>
                                </p>
                            </td>
                            <td colspan="5" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>Teléfono</b>
                                </p>
                            </td>
                            <td colspan="7" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>Email</b>
                                </p>
                            </td>
                        </tr>
                        @foreach ($data->assistant as $key => $assistant)
                            <tr></tr>
                            <tr>
                                <td colspan="2" rowspan="2" style="text-align: center">
                                    {{ $key + 1 }}
                                </td>
                                <td colspan="5" rowspan="2" style="text-align: center">
                                    {{ $assistant->nac->name }}
                                </td>
                                <td colspan="6" rowspan="2" style="text-align: center">
                                    {{ $assistant->assistant_name }}
                                </td>
                                <td colspan="5" rowspan="2" style="text-align: center">
                                    {{ $assistant->assistant_document_number }}
                                </td>
                                <td colspan="4" rowspan="2" style="text-align: center">
                                    {{ $assistant->assistant_position }}
                                </td>
                                <td colspan="5" rowspan="2" style="text-align: center">
                                    {{ $assistant->assistant_phone }}
                                </td>
                                <td colspan="7" rowspan="2" style="text-align: center">
                                    {{ $assistant->assistant_email }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </tr>
        </div>
    </body>
</html>
