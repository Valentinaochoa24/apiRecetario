<!DOCTYPE html>
<html>
<head>
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>
    <div class="container">
        <h2>Lista de Productos</h2>
        <table class="striped">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre del Producto</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Arreglo de productos ficticios
                // Incluye el archivo recuperar_cookie.php para mostrar el valor de la cookie
                include 'autenticar_token.php';
                $productos = [
                    [
                        'imagen' => 'https://www.apple.com/co/iphone/home/images/meta/iphone__ky2k6x5u6vue_og.png?202309290413',
                        'nombre' => 'Producto 1',
                        'descripcion' => 'Descripción del Producto 1'
                    ],
                    [
                        'imagen' => 'data:image/jpeg;base64,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',
                        'nombre' => 'Producto 2',
                        'descripcion' => 'Descripción del Producto 2'
                    ],
                    [
                        'imagen' => 'https://www.ktronix.com/medias/750Wx750H-master-hotfolder-transfer-incoming-deposit-hybris-interfaces-IN-media-product-840023249846-001.jpg?context=bWFzdGVyfGltYWdlc3wxOTI4NDV8aW1hZ2UvanBlZ3xhR1l3TDJoaVpDOHhNemczT1RreU1ERTJORGc1TkM4M05UQlhlRGMxTUVoZmJXRnpkR1Z5TDJodmRHWnZiR1JsY2k5MGNtRnVjMlpsY2k5cGJtTnZiV2x1Wnk5a1pYQnZjMmwwTDJoNVluSnBjeTFwYm5SbGNtWmhZMlZ6TDBsT0wyMWxaR2xoTDNCeWIyUjFZM1F2T0RRd01ESXpNalE1T0RRMlh6QXdNUzVxY0djfGJkYzdiYjNkMDE3MmQ2YjQ2NDk2OGMzYjhmMGZmNDE2MDg2N2MzZjExZDNiOThlYWJiNDI1MTJiNjU1MGFiYTg',
                        'nombre' => 'Producto 3',
                        'descripcion' => 'Descripción del Producto 3'
                    ]
                ];

                // Mostrar los productos en la tabla
                foreach ($productos as $key => $producto) {
                    echo '<tr>';
                    echo '<td><img src="' . $producto['imagen'] . '" alt="Producto" style="max-width: 100px;"></td>';
                    echo '<td>' . $producto['nombre'] . '</td>';
                    echo '<td>' . $producto['descripcion'] . '</td>';
                    echo '<td>';
                    echo '<a class="btn-small waves-effect waves-light blue" href="#">Editar</a>';
                    echo '<a class="btn-small waves-effect waves-light red" href="#">Eliminar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>

