SISTEMA INTEGRAL DE PUNTO DE VENTA (POS) - GESTIÓN DE JABONES Y LIMPIEZA
==========================================================================

1. INTRODUCCIÓN Y CONTEXTO
--------------------------
Este sistema ha sido desarrollado específicamente para negocios dedicados a la venta de productos de limpieza, jabones, detergentes y artículos de aseo. A diferencia de un POS genérico, este software está optimizado para manejar la complejidad de la **venta a granel** (líquidos por mililitros/litros y sólidos por peso), permitiendo transacciones rápidas y precisas sin necesidad de usar calculadoras externas.

El objetivo principal es agilizar la atención al cliente, minimizar errores de cálculo en ventas fraccionadas y mantener un control estricto del inventario en tiempo real.

2. FUNCIONALIDADES DETALLADAS
=============================

A. MÓDULO DE PUNTO DE VENTA (EL CORAZÓN DEL SISTEMA)
----------------------------------------------------
Este es el espacio de trabajo principal del cajero/vendedor.
- **Venta Híbrida (Granel y Unidades):** El sistema permite vender productos unitarios (botellas, esponjas) y productos fraccionables (detergentes, lavandina) en la misma transacción.
- **Calculadora Inteligente de Precios:**
  - *Caso de Uso Típico:* Un cliente llega y pide "$5 de detergente". El vendedor ingresa $5 y el sistema calcula automáticamente cuántos mililitros corresponden.
  - *Caso Inverso:* El cliente pide "500ml de suavizante". El vendedor ingresa la cantidad y el sistema calcula el precio exacto.
  - Al confirmar, el producto calculado se agrega al carrito listo para cobrar.
- **Búsqueda Instantánea:** Barra de búsqueda optimizada para encontrar productos por nombre, código SKU o categoría sin recargar la página.
- **Control de Carrito:**
  - Edición de cantidades sobre la marcha.
  - Eliminación de ítems.
  - Cálculo automático de subtotales y total final en Dólares ($).
- **Finalización de Compra:**
  - **Pago en Efectivo:** Interfaz para ingresar el monto entregado por el cliente, mostrando instantáneamente el cambio/vuelto a devolver.
  - **Pago Digital:** Opción para registrar pagos con Tarjeta o QR.
  - **Ticket de Venta:** Generación automática de un recibo imprimible con el detalle la compra.

B. GESTIÓN DE INVENTARIO Y PRODUCTOS
------------------------------------
- **Catálogo Visual:** Vista de cuadrícula con imágenes de productos para fácil identificación.
- **Indicadores de Estado:**
  - Etiquetas visuales para distinguir entre productos "SÓLIDOS" y "LÍQUIDOS".
  - **Alertas de Stock Bajo:** Los productos próximos a agotarse se marcan visualmente (animación de pulso y color rojo) para avisar al administrador.
- **Administración de Productos:**
  - Creación de productos con atributos complejos: Costo de compra, Precio de venta, Código SKU, Marca, y Unidad de Medida (Litro, Galón, Kilo, Unidad, etc.).
  - **Carga de Imágenes:** Sistema para subir y previsualizar fotos de los productos.
  - **Clonado Inteligente:** Al crear un producto nuevo, el sistema sugiere copiar datos de productos similares para ahorrar tiempo de escritura.
- **Reposición Rápida:** Botón de "Pilas/Suma" en el catálogo para agregar stock entrante sin entrar al modo de edición completo.

C. SEGURIDAD Y CONTROL
----------------------
- **Bloqueo por PIN:** Para evitar accesos no autorizados cuando el equipo queda solo, el sistema cuenta con una pantalla de bloqueo. Se requiere un PIN numérico (Configurable, por defecto '000000') para desbloquear la interfaz.
- **Sesiones:** Manejo seguro de la sesión del usuario.

D. REPORTES E HISTORIAL
-----------------------
- **Historial Transaccional:** Lista cronológica de todas las ventas realizadas.
- **Detalle de Venta:** Permite "abrir" una venta pasada para ver exactamente qué productos se vendieron, en qué cantidades y el método de pago utilizado.

E. EXPERIENCIA DE USUARIO (UX/UI)
---------------------------------
- **Diseño Responsivo:** El sistema funciona perfectamente en computadoras de escritorio, tablets y teléfonos móviles. Los menús se adaptan automáticamente.
- **Temas Visuales (Modo Día / Noche):**
  - Incluye un interruptor de tema global.
  - **Modo Oscuro:** Ideal para interiores o ambientes con poca luz, reduce la fatiga visual con tonos slate/azul oscuro.
  - **Modo Claro:** Interfaz limpia y brillante con alto contraste para ambientes muy iluminados.
- **Tecnología Reactiva:** Uso de Alpine.js para que las interacciones (modales, cálculos, búsquedas) sean instantáneas y fluidas, sin esperas de recarga.

3. FLUJO DE TRABAJO TÍPICO
--------------------------
1. **Inicio:** El vendedor ingresa el PIN de seguridad.
2. **Apertura:** Se presenta el Catálogo/POS.
3. **Venta:** 
   - Selecciona productos tocándolos o buscándolos.
   - Si es a granel, usa la calculadora para definir el monto o la cantidad exacta.
4. **Cobro:** Presiona "Cobrar", selecciona el método de pago y confirma.
5. **Cierre:** El sistema descuenta el stock, guarda el registro en el historial y ofrece imprimir el ticket. El carrito se limpia para el siguiente cliente.

4. ESPECIFICACIONES TÉCNICAS
----------------------------
- Lenguaje Base: PHP (Compatible con versiones 7.4 - 8.x).
- Base de Datos: MySQL / MariaDB (Tablas relacionales para productos, ventas, items de venta y categorías).
- Frontend: HTML5 Estándar + Tailwind CSS (vía CDN) + FontAwesome (Íconos).
- Moneda Base: Dólares Americanos ($).

5. MANTENIMIENTO
----------------
Es recomendable realizar copias de seguridad periódicas de la base de datos MySQL para resguardar la información histórica de ventas e inventario.
