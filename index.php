<?php
include("./php/modelo.php");
function clearInput($value, $conn)
{
    // Expresiones regulares para eliminar etiquetas y comentarios no deseados.
    $search = [
        '/<script[^>]*?>.*?<\/script>/si', // Remueve etiquetas script
        /*'/<[\/\!]*?[^<>]*?>/si', // Remueve etiquetas html
        '/<style[^>]*?>.*?<\/style>/siU', // Remueve etiquetas css*/
        '/<![\s\S]*?--[ \t\n\r]*>/', // Remueve HTML multilínea.
        '/SELECT.*?FROM/si', // Remueve SQL SELECT.
        '/FROM.*?WHERE/si', // Remueve SQL FROM.
        '/UPDATE.*?SET/si', // Remueve SQL UPDATE.
        '/INSERT INTO.*?SET/si', // Remueve  SQL INSERT INTO.
        '/DELETE FROM.*?/si', // Remueve SQL DELETE.
        '/ALTER TABLE.*?/si' // Remueve SQL ALTER TABLE.
    ];
    if (is_array($value)) {
        return array_map(function ($val) use ($conn) {
            return clearInput($val, $conn);
        }, $value);
    } else {
        return preg_replace($search, '', mysqli_real_escape_string($conn, $value));
    }
}

function preparePost($post = array(), $conn, $Parse_Ignore = array(), $return_type = 'array')
{
    $data = "";
    $indice = 0;
    if (!is_array($post)) {
        $data = clearInput($post, $conn);
    } else {
        $post = clearInput($post, $conn);
        foreach ($post as $key => $value) {
            if (!in_array($key, $Parse_Ignore) || (gettype($Parse_Ignore) === "boolean" && $Parse_Ignore === true)) {
                if (gettype($Parse_Ignore) != "boolean") {
                    if ($return_type == 'string') {
                        $value = (is_array($value) ? json_encode($value) : $value);
                        $data .= "{$key} = '{$value}'" . ($indice == (count($post) - 1) ? "" : ", ");
                    } else if ($return_type == 'keyvalue') {
                        $data .= "{$key}:{$value}" . ($indice == (count($post) - 1) ? "" : ", ");
                    } else {
                        $post[$key] = $value;
                    }
                } else {
                    $post[$key] = $value;
                }
                $indice++;
            }
        }
    }
    if ($return_type == 'string') {
        return $data;
    } else if ($return_type == 'keyvalue') {
        return $data;
    } else {
        return $post;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link rel="stylesheet" href="./css/estilosDesplegable.css">
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>