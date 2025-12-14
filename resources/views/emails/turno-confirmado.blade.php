<!DOCTYPE html>
<html>
<body>
    <h2>¡Tu turno fue confirmado!</h2>

    <p>Hola {{ $nombre }},</p>

    <p>
        Tu reserva para <strong>{{ $servicio }}</strong> con la profesional
        <strong>{{ $profesional }}</strong> fue registrada correctamente.
    </p>

    <p><strong>Fecha:</strong> {{ $fecha }}</p>
    <p><strong>Hora:</strong> {{ substr($hora, 0, 5) }}</p>

    <p>Gracias por confiar en Kinesio ❤️</p>
</body>
</html>