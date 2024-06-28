<?php

// Ruta al archivo JSON que contiene las notas de los alumnos
$json_file = 'notas.json';

// Verificar si el archivo JSON existe
if (file_exists($json_file)) {
    // Leer el contenido del archivo JSON
    $json_data = file_get_contents($json_file);

    // Decodificar el contenido JSON en un array asociativo
    $data = json_decode($json_data, true);

    // Verificar si se pudo decodificar el JSON correctamente
    if ($data !== null) {
        // Iterar sobre los datos de los alumnos
        foreach ($data['alumnos'] as $alumno) {
            echo '<tr>';
            echo '<td>' . $alumno['rut'] . '</td>';
            echo '<td>' . $alumno['nombre'] . '</td>';

            // Mostrar las notas de Unidad 1
            foreach ($alumno['unidad_1'] as $nota) {
                echo '<td>' . ($nota !== null ? $nota : '-') . '</td>';
            }

            // Calcular y mostrar el promedio de las notas de Unidad 1
            $promedio_unidad_1 = array_sum(array_filter($alumno['unidad_1'], function ($nota) {
                return $nota !== null;
            })) / count(array_filter($alumno['unidad_1'], function ($nota) {
                return $nota !== null;
            }));
            echo '<td>' . ($promedio_unidad_1 !== 0 ? number_format($promedio_unidad_1, 1) : '-') . '</td>';

            // Mostrar la nota de Prueba de Unidad 1
            echo '<td>' . ($alumno['prueba_unidad_1'] !== null ? $alumno['prueba_unidad_1'] : '-') . '</td>';

            // Mostrar las notas de Unidad 2
            foreach ($alumno['unidad_2'] as $nota) {
                echo '<td>' . ($nota !== null ? $nota : '-') . '</td>';
            }

            // Calcular y mostrar el promedio de las notas de Unidad 2
            $promedio_unidad_2 = number_format(array_sum(array_filter($alumno['unidad_2'], function ($nota) {
                return $nota !== null;
            })) / count(array_filter($alumno['unidad_2'], function ($nota) {
                return $nota !== null;
            })), 2); // Cambia el 2 por el número de decimales que desees mostrar

            echo '<td>' . ($promedio_unidad_2 !== 0 ? number_format($promedio_unidad_2, 1) : '-') . '</td>';

            // Mostrar la nota de Prueba de Unidad 2
            echo '<td>' . ($alumno['prueba_unidad_2'] !== null ? $alumno['prueba_unidad_2'] : '-') . '</td>';

            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="19">No se pudieron decodificar los datos del archivo JSON.</td></tr>';
    }
} else {
    echo '<tr><td colspan="19">El archivo JSON no existe.</td></tr>';
}
