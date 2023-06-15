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
                                <h3 style="text-align: center; color:#ffffff;">INFORME DE SEGUIMIENTO GESTOR CULTURAL</h3>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="34" style="background: #164E63">
                                <h3 style="text-align: center; color:#ffffff;">{{ $titulos['Titulo1'] }}</h3>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>1.1 Nombre del monitor cultural:</b>
                                </p>
                            </td>
                            <td colspan="9" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->monitor->name }}
                                </p>
                            </td>
                            <td colspan="8" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>1.6 Fecha del seguimiento:</b>
                                </p>
                            </td>
                            <td colspan="9" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->activity_date }}
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="8" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>1.2 Agente metodólogo:</b>
                                </p>
                            </td>
                            <td colspan="9" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{-- $data->nac->name --}}
                                </p>
                            </td>
                            <td colspan="8" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>1.7 Hora de inicio:</b>
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
                                    <b>1.3 Nodo de atención cultural:</b>
                                </p>
                            </td>
                            <td colspan="9" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->nac->name }}
                                </p>
                            </td>
                            <td colspan="8" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>1.8 Hora final:</b>
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
                            <td colspan="12" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>1.4 Nombre del tutorial revisado:</b>
                                </p>
                            </td>
                            <td colspan="22" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{-- $data->activity_date --}}
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="12" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>1.5 Objetivo del seguimiento:</b>
                                </p>
                            </td>
                            <td colspan="22" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->target_tracking }}
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
                                <h3 style="text-align: center; color:#ffffff;">{{ $titulos['Titulo2'] }}</h3>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="34">
                                <p>A continuación, encontrará una serie de preguntas. Califique de 1 a 5, siendo 5 la mejor puntuación y 1 la más mala.</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="24" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>Aspecto a evaluar</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>1</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>2</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>3</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>4</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>5</b>
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="24" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>2.1 La ficha pedagógica da cuenta del proceso cultural (manifestación cultural) a trabajar en el mes.</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_process == 1 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_process == 2 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_process == 3 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_process == 4 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_process == 5 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="24" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>2.2 la ficha pedagógica refleja los derechos culturales y lineamos a trabajar en el mes.</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_guidelines == 1 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_guidelines == 2 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_guidelines == 3 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_guidelines == 4 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_guidelines == 5 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="24" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>2.3 El monitor cultural maneja una comunicación acorde a las particularidades de los beneficiarios.</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_communication == 1 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_communication == 2 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_communication == 3 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_communication == 4 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                            <td colspan="2" rowspan="2">
                                <p style="text-align: center;margin-top: 0%;margin-bottom:0%">
                                    <b>{{ $data->cultural_communication == 5 ? ' X ' : '' }}</b>
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="14" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>2.4 Mencione 1 dificultad evidencia en el proceso cultural</b>
                                </p>
                            </td>
                            <td colspan="20" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->difficulty_cultural_process }}
                                </p>
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="14" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    <b>2.5 Mencione una propuesta para el mejoramiento metodológico y pedagógico de la ficha pedagógica.</b>
                                </p>
                            </td>
                            <td colspan="20" rowspan="2">
                                <p style="text-align: left;margin-top: 0%;margin-bottom:0%">
                                    {{ $data->proposal_improvement }}
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </tr>
        </div>
    </body>
</html>
