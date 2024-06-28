<?php

$contador_file = 'contador.txt';

$contador = (file_exists($contador_file)) ? (int)file_get_contents($contador_file) : 0;

$contador++;

file_put_contents($contador_file, $contador);

function obtenerContador() {
    global $contador_file;
    return (file_exists($contador_file)) ? (int)file_get_contents($contador_file) : 0;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Notas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <style>
        body {
            background-color: black;
            font-family: 'Courier New', Courier, monospace;
            color: #32CD32;
            cursor: none;
        }

        input[type="text"] {
            background-color: black;
            color: #32CD32;
            border: 2px solid #32CD32;
            border-radius: 5px;
            padding: 5px;
        }

        input[type="text"]::placeholder {
            color: #32CD32;
        }

        button {
            background-color: #32CD32;
            color: black;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        button:hover {
            background-color: #228B22;
            color: white;
        }

        .table-bordered th,
        .table-bordered td,
        .table-bordered thead th,
        .table-bordered tbody th {
            border: 2px solid #32CD32;
            color: #32CD32;
        }

        .table-scrollable {
            overflow-x: auto;
        }

        table th,
        table td {
            text-align: center;
        }

        @media (max-width: 768px) {
            body {
                font-size: 14px;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('contextmenu', function(event) {
                event.preventDefault();
                alert('Que intentas hacer?');
            });
        });
    </script>
    
</head>

<body style="background-color: #212529;">
    <div class="container">
        <h1>Consulta de Notas</h1>
        <form method="POST" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="rut" placeholder="Ingrese el Rut del alumno...">
                <button type="submit">Buscar</button>
            </div>
        </form>
        <table class="table table-bordered table-dark table-responsive">
            <thead>
                <tr>
                    <th>RUT</th>
                    <th>Nombre</th>
                    <th>Promedio Sumativas Unidad 1 20%</th>
                    <th>Prueba Unidad 1 30%</th>
                    <th colspan="4">Unidad 2</th>
                    <th>Promedio Sumativas Unidad 2 20%</th>
                    <th>Prueba Unidad 2 30%</th>
                </tr>
                <tr>
                    <th colspan="2"></th>
                    <th></th>
                    <th></th>
                    <?php for ($i = 1; $i <= 4; $i++) {
                        echo "<th>Nota $i</th>";
                    } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (!empty($_POST['rut'])) {
                        $json_file = 'notas.json';
                        $json_data = file_get_contents($json_file);
                        $data = json_decode($json_data, true);
                        if ($data !== null) {
                            $rut_buscado = $_POST['rut'];
                            $rut_encontrado = false;
                            foreach ($data['alumnos'] as $alumno) {
                                if ($alumno['rut'] == $rut_buscado) {
                                    $rut_encontrado = true;
                                    echo '<tr>';
                                    echo '<td>' . $alumno['rut'] . '</td>';
                                    echo '<td>' . $alumno['nombre'] . '</td>';

                                    $notas_unidad_1 = array_filter($alumno['unidad_1'], function ($nota) {
                                        return $nota !== null;
                                    });

                                    $promedio_unidad_1 = count($notas_unidad_1) > 0 ? array_sum($notas_unidad_1) / count($notas_unidad_1) : 0.0;
                                    echo '<td>' . ($promedio_unidad_1 !== 0 ? number_format($promedio_unidad_1, 1) : '-') . '</td>';

                                    echo '<td>' . ($alumno['prueba_unidad_1'] !== null ? $alumno['prueba_unidad_1'] : '-') . '</td>';

                                    foreach ($alumno['unidad_2'] as $nota) {
                                        echo '<td>' . ($nota !== null ? $nota : '-') . '</td>';
                                    }

                                    $notas_unidad_2 = array_filter($alumno['unidad_2'], function ($nota) {
                                        return $nota !== null;
                                    });

                                    $promedio_unidad_2 = count($notas_unidad_2) > 0 ? array_sum($notas_unidad_2) / count($notas_unidad_2) : 0.0;

                                    echo '<td>' . ($promedio_unidad_2 !== 0 ? number_format($promedio_unidad_2, 1) : '-') . '</td>';

                                    echo '<td>' . ($alumno['prueba_unidad_2'] !== null ? $alumno['prueba_unidad_2'] : '-') . '</td>';

                                    echo '</tr>';
                                    break;
                                }
                            }

                            if (!$rut_encontrado) {
                                echo '<tr><td colspan="10" class="text-center">No se encontraron notas para el Rut ingresado.</td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="10" class="text-center">No se pudieron decodificar los datos del archivo JSON.</td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="10" class="text-center">Ingrese un Rut válido.</td></tr>';
                    }
                }
                ?>
            </tbody>
        </table>
        <table class="table table-bordered table-dark table-responsive">
            <thead>
                <tr>
                    <th colspan="2">Unidad 1</th>
                    <th colspan="2">Unidad 2</th>
                    <th>Promedio Final</th>
                </tr>
                <tr>
                    <th>Sumativas</th>
                    <th>Evaluacion</th>
                    <th>Sumativas</th>
                    <th>Evaluacion</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if ($rut_encontrado) {
                        $sumativas_unidad_1 = $promedio_unidad_1 * 0.2;
                        $evaluacion_unidad_1 = ($alumno['prueba_unidad_1'] !== null) ? $alumno['prueba_unidad_1'] * 0.3 : 0.0;
                        $sumativas_unidad_2 = $promedio_unidad_2 * 0.2;
                        $evaluacion_unidad_2 = ($alumno['prueba_unidad_2'] !== null) ? $alumno['prueba_unidad_2'] * 0.3 : 0.0;

                        $promedio_final = $sumativas_unidad_1 + $evaluacion_unidad_1 + $sumativas_unidad_2 + $evaluacion_unidad_2;

                        echo '<tr>';
                        echo '<td>' . number_format($sumativas_unidad_1, 1) . '</td>';
                        echo '<td>' . number_format($evaluacion_unidad_1, 1) . '</td>';
                        echo '<td>' . number_format($sumativas_unidad_2, 1) . '</td>';
                        echo '<td>' . number_format($evaluacion_unidad_2, 1) . '</td>';
                        echo '<td>' . number_format($promedio_final, 1) . '</td>';
                        echo '</tr>';
                    } else {
                        echo '<tr>';
                        echo '<td>0.0</td>';
                        echo '<td>0.0</td>';
                        echo '<td>0.0</td>';
                        echo '<td>0.0</td>';
                        echo '<td>0.0</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
        <p>Visitas: 3 chillones <span id="puntos"></span></p>
    </div>
    <footer>
        <div class="container">
            <p class="text-center">Desarrollado X <a href="https://github.com/lonkonao" target="_blank">Lonkonao</a></p>
        </div>
    </footer>

    <script>
        function obtenerContador() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "contador.txt", false);
            xhr.send(null);
            return xhr.responseText;
        }

        function animarPuntos() {
            var puntosSpan = document.getElementById('puntos');

            setInterval(function() {
                puntosSpan.textContent = '';
                setTimeout(function() {
                    puntosSpan.textContent = '.';
                }, 100);
                setTimeout(function() {
                    puntosSpan.textContent = '..';
                }, 200);
                setTimeout(function() {
                    puntosSpan.textContent = '...';
                }, 300);
            }, 500);
        }

        document.addEventListener('DOMContentLoaded', function() {
            animarPuntos();
            setInterval(actualizarContador, 5000);
        });

        function actualizarContador() {
            var contadorSpan = document.getElementById('contador');
            contadorSpan.textContent = obtenerContador();
        }
    </script>
</body>

</html>
