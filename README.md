Esta aplicación es una API-Rest para ser utilizada en un backend. Utiliza el framework SLIM y como lenguaje de programacion utiliza intregramente PHP, y algunas consultas SQL para la base de datos.
Este sistema está diseñado para cubrir las necesidades y deseos de gestión de un restaurante con múltiples sectores. 
El restaurante se divide en cuatro sectores: Barra de tragos y vinos, barra de cerveza artesanal y cocina.

**USUARIOS:**
Hay una clasificacion de empleados en diferentes roles: bartender, cerveceros, cocineros, mozos, y socios.
De los cuales se controla y puede seguir los ingresos al sistema por días y horarios. Cantidad de operaciones por sector y por empleado, Posibilidad de dar de alta, suspender o borrarlos del sistema (soft-delete).

**COMANDA:**
Cada comanda incluye la información necesaria para ser realizada. El mozo asigna un código único alfanumérico de 5 caracteres al cliente para identificar su pedido.
Los empleados correspondientes pueden ver y gestionar sus pedidos pendientes:
- Cambiar el estado a “en preparación” y agregar un tiempo estimado de finalización.
- Cambiar el estado a “listo para servir” cuando el pedido esté listo.
Los socios pueden ver en todo momento el estado de todos los pedidos.

**MESAS:**
Cada mesa tiene una identificacion única  con un código de 5 caracteres, y un estado propio que puede ser cualquiera de los siguiente:
“con cliente esperando pedido”
“con cliente comiendo”
“con cliente pagando”
“cerrada” (este estado solo puede ser cambiado por un socio)
El cliente puede ingresar el código de la mesa y el número del pedido para ver el tiempo restante.

**ENCUESTA:**
Al finalizar la comida, se habilita una encuesta para calificar del 1 al 10:
- La mesa
- El restaurante
- El mozo
- El cocinero
Posibilidad de agregar un breve comentario de hasta 66 caracteres sobre la experiencia.

**ESTADISTICAS:**
Los socio pueden ver estadisticas del bar cuando lo desean, y podran ver lo siguiente:
- De los empleados:
	Días y horarios de ingreso.
	Cantidad de operaciones por sector y por empleado.
	Operaciones por separado.
- De los pedidos:
	Lo más vendido y lo menos vendido.
	Pedidos no entregados a tiempo y cancelados.
- De las mesas:
	La más usada y la menos usada.
	La que más facturó y la que menos facturó.
	Facturas con el mayor y menor importe.
	Facturación entre dos fechas dadas.
	Mejores y peores comentarios.

Este sistema fue hecho como trabajo practico de la cursada de programacion III en la tecnicatura universitaria de programacion de la UTN-FRA por Agustin Teira.
