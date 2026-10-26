# How-to: cargar actividades desde Excel

Objetivo: insertar muchas actividades UNSPSC de una sola vez, con validación **todo o nada**.

## 1. Descargar la plantilla

En **Actividades → Listado**, abrí **Cargue masivo** y pulsá **Descargar plantilla**.

Eso llama `GET /api/actividad/plantilla` y baja `plantilla_actividades.xlsx`. La primera fila son encabezados; la segunda es un ejemplo (Gatos / código 10101501).

## 2. Armar el archivo

Solo `.xlsx`, máximo 10 MB. Columnas en este orden:

1. codigo segmento (numérico, 1–10 dígitos)
2. segmento (texto, hasta 200 caracteres)
3. codigo familia
4. familia
5. codigo clase
6. clase
7. codigo producto
8. producto

Textos permitidos: letras Unicode, números, espacios, y `, ( ) - / & ' # ; % °`. **No uses puntos** en los textos.

La primera fila se ignora (encabezados). Las filas vacías se omiten.

El `codigo producto` no puede repetirse dentro del archivo ni existir ya en la tabla `actividades`.

## 3. Subir

Seleccioná el archivo en el modal y pulsá **Cargar actividades**. El campo del formulario es `archivo_actividades`.

## 4. Interpretar el resultado

- **201**: se insertaron todas las filas (en lotes de 500, dentro de una transacción). Quedan a tu nombre (`usuario_id`).
- **400 con `errores`**: ninguna fila se insertó. La tabla del modal muestra `fila` y `motivo`. Corregí el Excel y volvé a subir el archivo completo.

Motivos típicos: campo vacío, texto inválido, código no numérico, producto duplicado en el archivo, producto ya existente en el sistema.

Si PHP no tiene `zip`/`gd`, la lectura del `.xlsx` falla aunque el archivo esté bien.
