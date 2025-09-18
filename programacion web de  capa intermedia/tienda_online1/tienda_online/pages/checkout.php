<?php
include '../includes/header.php';
include '../includes/db.php';

// Verificar si hay productos en el carrito y usuario logueado
if (empty($_SESSION['carrito']) || !isset($_SESSION['usuario_id'])) {
    header('Location: carrito.php');
    exit();
}

// Calcular el total
$total = 0;
foreach ($_SESSION['carrito'] as $id => $cantidad) {
    $stmt = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();
    $total += $producto['precio'] * $cantidad;
}

// ID de cliente de PayPal (modo sandbox para pruebas)
$paypalClientId = 'ASW1OWCSsrd9nMhY9x0FnMZApXRcN8y6ckoX55qxZRf4gTbVTw-YnZxykZlS_bsIvy6EhuCNCSAfPEOq';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SDK de PayPal con opciones "currency" y "debug" añadidas -->
    <script src="https://www.paypal.com/sdk/js?client-id=<?= $paypalClientId ?>&currency=USD&debug=true"></script>
    <style>
        .payment-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .payment-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
        }

        .payment-methods {
            margin-top: 20px;
        }

        .payment-method {
            padding: 15px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .payment-method:hover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }

        .payment-method.active {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }

        #error-container {
            display: none;
            margin-top: 15px;
        }

        #loading-indicator {
            display: none;
            text-align: center;
            margin: 15px 0;
        }

        .spinner-border {
            width: 2rem;
            height: 2rem;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Finalizar Compra</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Resumen de la compra</h5>
                <p>Total a pagar: <strong>$<?= number_format($total, 2) ?></strong></p>
                <p>Número de productos: <strong><?= count($_SESSION['carrito']) ?></strong></p>
                <div class="mt-3">
                    <h6>Detalle de productos:</h6>
                    <ul>
                        <?php
                        foreach ($_SESSION['carrito'] as $id => $cantidad) {
                            $stmt = $conn->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
                            $stmt->execute([$id]);
                            $producto = $stmt->fetch();
                            echo "<li>" . htmlspecialchars($producto['nombre']) . " x " . $cantidad . " = $" .
                                number_format($producto['precio'] * $cantidad, 2) . "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button id="openPaymentModal" class="btn btn-primary btn-lg">Seleccionar Método de Pago</button>
        </div>
    </div>

    <!-- Modal de métodos de pago -->
    <div id="paymentModal" class="payment-modal">
        <div class="payment-content">
            <h3 class="text-center mb-4">Selecciona tu método de pago</h3>

            <div class="payment-methods">
                <div class="payment-method active" data-method="paypal">
                    <h5>PayPal</h5>
                    <p>Paga con tu cuenta PayPal o tarjeta de crédito/débito</p>
                </div>

                <div class="payment-method" data-method="credit-card">
                    <h5>Tarjeta de Crédito</h5>
                    <p>Paga con tarjeta de crédito o débito</p>
                </div>
            </div>

            <!-- Indicador de carga -->
            <div id="loading-indicator">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <p class="mt-2">Procesando pago, espere por favor...</p>
            </div>

            <div id="paypal-button-container" class="mt-4"></div>

            <div id="credit-card-form" style="display: none;">
                <p class="text-muted">Esta opción estará disponible pronto.</p>
            </div>

            <!-- Contenedor para mostrar errores -->
            <div id="error-container" class="alert alert-danger mt-3"></div>

            <div class="text-center mt-4">
                <button id="closePaymentModal" class="btn btn-secondary">Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        // Mostrar/ocultar modal
        const paymentModal = document.getElementById('paymentModal');
        const openPaymentModal = document.getElementById('openPaymentModal');
        const closePaymentModal = document.getElementById('closePaymentModal');
        const errorContainer = document.getElementById('error-container');
        const loadingIndicator = document.getElementById('loading-indicator');

        openPaymentModal.addEventListener('click', () => {
            paymentModal.style.display = 'flex';
        });

        closePaymentModal.addEventListener('click', () => {
            paymentModal.style.display = 'none';
            errorContainer.style.display = 'none';
        });

        // Cambiar método de pago
        const paymentMethods = document.querySelectorAll('.payment-method');
        paymentMethods.forEach(method => {
            method.addEventListener('click', () => {
                paymentMethods.forEach(m => m.classList.remove('active'));
                method.classList.add('active');

                const selectedMethod = method.dataset.method;
                if (selectedMethod === 'paypal') {
                    document.getElementById('paypal-button-container').style.display = 'block';
                    document.getElementById('credit-card-form').style.display = 'none';
                } else {
                    document.getElementById('paypal-button-container').style.display = 'none';
                    document.getElementById('credit-card-form').style.display = 'block';
                }
            });
        });

        // Función para mostrar errores
        function showError(message) {
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
            loadingIndicator.style.display = 'none';
        }

        // Función para mostrar cargando
        function showLoading(show = true) {
            loadingIndicator.style.display = show ? 'block' : 'none';
            if (show) {
                errorContainer.style.display = 'none';
            }
        }

        // Integración de PayPal mejorada
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },

            // Configurar la orden
            createOrder: function(data, actions) {
                showLoading(true);
                console.log('Iniciando creación de orden en PayPal...');

                // Asegurar formato correcto del total
                const formattedTotal = parseFloat(<?= $total ?>).toFixed(2);
                console.log('Total formateado:', formattedTotal);

                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: formattedTotal
                        }
                    }]
                }).catch(error => {
                    console.error('Error al crear la orden:', error);
                    showError('Error al crear la orden de PayPal: ' + error.message);
                    throw error;
                });
            },

            // Cuando el pago es aprobado
            onApprove: function(data, actions) {
                showLoading(true);
                console.log('Pago aprobado, capturando detalles...');

                return actions.order.capture().then(function(details) {
                    console.log('Detalles capturados:', details);

                    // Datos para enviar al servidor
                    const paymentData = {
                        transaction_id: data.orderID,
                        payer_email: details.payer.email_address,
                        amount: details.purchase_units[0].amount.value,
                        carrito: <?= json_encode($_SESSION['carrito']) ?>,
                        usuario_id: <?= $_SESSION['usuario_id'] ?? 'null' ?>
                    };

                    console.log('Enviando datos al servidor:', paymentData);

                    // Enviar datos al servidor para registrar la compra
                    return fetch('procesar_compra.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(paymentData)
                        })
                        .then(response => {
                            console.log('Respuesta recibida, status:', response.status);
                            if (!response.ok) {
                                throw new Error('Error en la respuesta del servidor: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Datos procesados:', data);
                            showLoading(false);

                            if (data.success) {
                                window.location.href = 'compra-exitosa.php?transaction_id=' + data.transaction_id;
                            } else {
                                throw new Error(data.message || 'Error desconocido al procesar la compra');
                            }
                        })
                        .catch(error => {
                            console.error('Error en el procesamiento del servidor:', error);
                            showError('Error al registrar la compra: ' + error.message);
                        });
                }).catch(error => {
                    console.error('Error al capturar el pago:', error);
                    showError('Error al capturar el pago: ' + error.message);
                });
            },

            // Manejo de errores
            onError: function(err) {
                console.error('Error en PayPal:', err);
                showError('Ocurrió un error al procesar el pago con PayPal: ' + (err.message || 'Error desconocido'));
            },

            // Si el usuario cancela
            onCancel: function(data) {
                console.log('Pago cancelado:', data);
                showError('Has cancelado el pago. Puedes intentarlo de nuevo cuando estés listo.');
            }
        }).render('#paypal-button-container');
    </script>
</body>

</html>