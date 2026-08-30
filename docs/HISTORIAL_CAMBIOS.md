# Historial de cambios del proyecto

Este documento registra las mejoras funcionales y técnicas realizadas en el sistema. Las entradas más recientes se agregan al comienzo.

## 2026-08-30 — Prioridad 1: integridad de reservas

### Objetivo

Proteger el inventario hotelero sin cambiar el flujo comercial existente: se conserva la elección del tipo vendido, la asignación de una habitación física y la posibilidad de realizar upgrades.

### Cambios realizados

- Se centralizó el alta y la edición en `GuardarReservaAction`.
- La persistencia ahora revalida fechas, entidades relacionadas, estados, medio de pago e importe.
- Se agregó un bloqueo atómico por habitación y una transacción con bloqueo de filas para serializar reservas concurrentes.
- Antes de guardar se vuelve a comprobar capacidad, mantenimiento y solapamientos.
- Las reservas canceladas continúan sin bloquear disponibilidad.
- Las habitaciones en mantenimiento dejaron de aparecer como disponibles.
- En edición se excluye la propia reserva al calcular disponibilidad y solapamientos.
- La salida debe ser posterior a la entrada; ya no se admiten estadías de cero noches.
- Se incorporaron scopes reutilizables para reservas bloqueantes y rangos solapados.
- Se agregaron casts de fechas, cantidad de personas y total pagado.
- Se añadió un índice compuesto para la consulta de disponibilidad.
- Se crearon factories de dominio y pruebas Pest para los casos principales.

### Consideraciones operativas

- Todas las futuras vías de alta o edición de reservas deben delegar en `GuardarReservaAction`.
- El bloqueo de base de datos ofrece su garantía más fuerte sobre MySQL o PostgreSQL. El bloqueo atómico de caché complementa el comportamiento cuando se utiliza SQLite, siempre que el store de caché sea compartido entre procesos.
- El estado `Ocupada` no bloquea reservas futuras por sí solo; la ocupación se determina por fechas. `Mantenimiento` sí excluye actualmente la habitación para cualquier fecha.

### Verificación

- Pruebas de creación válida.
- Rechazo de solapamientos.
- Aceptación de reservas consecutivas.
- Reservas canceladas sin bloqueo.
- Rechazo de habitaciones en mantenimiento.
- Validación de capacidad y fechas.
- Actualización sin falso solapamiento con la propia reserva.
- Verificación del scope de disponibilidad.
