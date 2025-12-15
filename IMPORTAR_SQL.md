# 📦 Importar Base de Datos - Guía Paso a Paso

## 📄 **Archivo SQL Creado:**
`database.sql` - Estructura completa de la base de datos

---

## 🚀 **Opción 1: Importar en InfinityFree (phpMyAdmin)**

### **Paso 1: Acceder a phpMyAdmin**
1. Ve a tu panel de InfinityFree
2. Click en **"MySQL Databases"**
3. Click en **"phpMyAdmin"** junto a tu base de datos `if0_40687916_jabon`
4. Se abrirá phpMyAdmin

### **Paso 2: Seleccionar la Base de Datos**
1. En el panel izquierdo, click en `if0_40687916_jabon`
2. La base de datos se seleccionará

### **Paso 3: Importar el Archivo SQL**
1. Click en la pestaña **"Importar"** (Import)
2. Click en **"Seleccionar archivo"** (Choose File)
3. Selecciona el archivo `database.sql`
4. Scroll hacia abajo
5. Click en **"Continuar"** (Go)

### **Paso 4: Verificar**
✅ Deberías ver un mensaje: **"Importación finalizada con éxito"**

✅ En el panel izquierdo verás las tablas:
- `categories` (4 registros)
- `products` (vacía)
- `sales` (vacía)
- `sale_items` (vacía)

---

## 🏠 **Opción 2: Importar en Local (XAMPP)**

### **Paso 1: Acceder a phpMyAdmin Local**
1. Abre: `http://localhost/phpmyadmin`
2. Click en **"Nueva"** (New)
3. Nombre: `inventario_liquid_local`
4. Cotejamiento: `utf8mb4_unicode_ci`
5. Click en **"Crear"**

### **Paso 2: Importar**
1. Selecciona la base de datos `inventario_liquid_local`
2. Click en **"Importar"**
3. Selecciona `database.sql`
4. Click en **"Continuar"**

---

## 📋 **Contenido del Archivo SQL:**

### **Tablas Creadas:**

#### 1️⃣ **categories** (Categorías)
```sql
- id (INT, AUTO_INCREMENT)
- name (VARCHAR 100)
- description (TEXT)
- created_at (TIMESTAMP)
```
**Datos incluidos:** 4 categorías de ejemplo

#### 2️⃣ **products** (Productos)
```sql
- id (INT, AUTO_INCREMENT)
- category_id (INT, FK)
- name (VARCHAR 150)
- sku (VARCHAR 50)
- barcode (VARCHAR 100)
- brand (VARCHAR 100)
- is_liquid (TINYINT 1)
- display_unit (VARCHAR 20)
- price (DECIMAL 10,2)
- cost_price (DECIMAL 10,2)
- stock_quantity (DECIMAL 10,4)
- min_stock (DECIMAL 10,4)
- image_path (VARCHAR 255)
- created_at (TIMESTAMP)
```

#### 3️⃣ **sales** (Ventas)
```sql
- id (INT, AUTO_INCREMENT)
- total_amount (DECIMAL 10,2)
- payment_method (VARCHAR 50)
- amount_tendered (DECIMAL 10,2)
- created_at (TIMESTAMP)
```

#### 4️⃣ **sale_items** (Items de Venta)
```sql
- id (INT, AUTO_INCREMENT)
- sale_id (INT, FK)
- product_id (INT, FK)
- quantity (DECIMAL 10,4)
- subtotal (DECIMAL 10,2)
```

---

## 🔗 **Relaciones (Foreign Keys):**

```
products.category_id → categories.id (ON DELETE SET NULL)
sale_items.sale_id → sales.id (ON DELETE CASCADE)
sale_items.product_id → products.id (ON DELETE SET NULL)
```

---

## ✅ **Datos de Ejemplo Incluidos:**

### **Categorías:**
1. **Detergentes** - Detergentes líquidos y en polvo
2. **Suavizantes** - Suavizantes de ropa
3. **Limpiadores** - Limpiadores de piso y superficies
4. **Desengrasantes** - Para cocina y uso industrial

---

## 🎯 **Después de Importar:**

### **En InfinityFree:**
1. ✅ Base de datos lista
2. ✅ Accede a: `http://jabones.42web.io/`
3. ✅ El sistema funcionará correctamente

### **En Local:**
1. ✅ Base de datos lista
2. ✅ Accede a: `http://localhost/Inventario-Liquid/`
3. ✅ Comienza a desarrollar

---

## ⚠️ **Notas Importantes:**

### **InfinityFree:**
- ✅ El archivo SQL usa `CREATE TABLE IF NOT EXISTS`
- ✅ No borrará datos existentes
- ✅ Seguro para re-importar

### **Charset:**
- ✅ utf8mb4_unicode_ci (soporta emojis y caracteres especiales)
- ✅ Compatible con español y acentos

### **Motor:**
- ✅ InnoDB (soporta transacciones y foreign keys)
- ✅ Compatible con InfinityFree

---

## 🐛 **Troubleshooting:**

### **Error: "Table already exists"**
- Es normal si re-importas
- El SQL usa `IF NOT EXISTS`
- No afecta la importación

### **Error: "Foreign key constraint fails"**
- Asegúrate de importar en orden
- El SQL ya está en el orden correcto

### **Error: "Access denied"**
- Verifica que estés en phpMyAdmin de InfinityFree
- Verifica que la base de datos sea `if0_40687916_jabon`

---

## 📚 **Archivos Relacionados:**

| Archivo | Descripción |
|---------|-------------|
| `database.sql` | ✅ Archivo SQL para importar |
| `install.php` | Instalador automático (alternativa) |
| `config/database.php` | Configuración de conexión |

---

## 🎉 **¡Listo!**

Después de importar el SQL:
1. ✅ Base de datos completamente configurada
2. ✅ 4 categorías de ejemplo
3. ✅ Listo para agregar productos
4. ✅ Sistema funcional

---

**¿Necesitas ayuda con la importación?** 🚀
