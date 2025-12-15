# 🚀 GitHub Actions - Despliegue FTP Corregido

## ✅ Problemas Corregidos

### **Errores Anteriores:**
1. ❌ **Versión antigua de FTP-Deploy-Action** (`3.1.1`)
2. ❌ **Parámetro incorrecto:** `ftp-server` → debe ser `server`
3. ❌ **Parámetro incorrecto:** `ftp-username` → debe ser `username`
4. ❌ **Parámetro incorrecto:** `ftp-password` → debe ser `password`
5. ❌ **Parámetro inexistente:** `git-ftp-args` (no existe en esta acción)
6. ❌ **Parámetro incorrecto:** `ftp-base-path` → debe ser `server-dir`
7. ❌ **Versión antigua de checkout** (`v2.1.0`)

### **Soluciones Aplicadas:**
1. ✅ **Actualizado a FTP-Deploy-Action v4.3.5** (versión estable más reciente)
2. ✅ **Parámetros corregidos** según la documentación oficial
3. ✅ **Actualizado checkout a v4** (versión más reciente)
4. ✅ **Agregado `exclude`** para no subir archivos innecesarios
5. ✅ **Configurado `log-level: verbose`** para mejor debugging

---

## 🔧 Configuración de GitHub Secrets

Para que el workflow funcione, debes configurar estos **3 secrets** en tu repositorio de GitHub:

### **Paso a Paso:**

1. Ve a tu repositorio en GitHub
2. Click en **Settings** (Configuración)
3. En el menú lateral, click en **Secrets and variables** → **Actions**
4. Click en **New repository secret**
5. Crea los siguientes 3 secrets:

---

### **Secret 1: FTP_SERVER**
```
Nombre: FTP_SERVER
Valor: ftpupload.net
```
**Nota:** Para InfinityFree, el servidor FTP es `ftpupload.net` (sin `ftp://` ni puerto)

---

### **Secret 2: FTP_USERNAME**
```
Nombre: FTP_USERNAME
Valor: if0_40687916
```
**Nota:** Este es tu usuario de FTP de InfinityFree

---

### **Secret 3: FTP_PASSWORD**
```
Nombre: FTP_PASSWORD
Valor: wgLejdg0EC18
```
**Nota:** Esta es tu contraseña de FTP de InfinityFree

---

## 📋 Configuración Actual del Workflow

### **Archivo:** `.github/workflows/deploycPanel.yml`

```yaml
name: Deploy to InfinityFree via FTP

on:
  push:
    branches:
      - main

jobs:
  deploy:
    name: Deploy to InfinityFree
    runs-on: ubuntu-latest
    
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4
        with:
          fetch-depth: 2
      
      - name: Deploy via FTP
        uses: SamKirkland/FTP-Deploy-Action@v4.3.5
        with:
          server: ${{ secrets.FTP_SERVER }}
          username: ${{ secrets.FTP_USERNAME }}
          password: ${{ secrets.FTP_PASSWORD }}
          server-dir: /htdocs/
          local-dir: ./
          exclude: |
            **/.git*
            **/.git*/**
            **/node_modules/**
            **/.github/**
            **/README.md
            **/CORRECCIONES.md
            **/MODAL_BETA.md
            **/.vscode/**
            **/.idea/**
            **/uploads/.gitkeep
          dangerous-clean-slate: false
          log-level: verbose
```

---

## 🎯 Características del Nuevo Workflow

### **1. Despliegue Automático**
- ✅ Se ejecuta automáticamente al hacer `push` a la rama `main`
- ✅ Solo sube archivos modificados (incremental)
- ✅ No borra archivos del servidor (`dangerous-clean-slate: false`)

### **2. Archivos Excluidos**
El workflow **NO subirá** estos archivos/carpetas:
- `.git` y archivos de Git
- `node_modules/`
- `.github/` (workflows)
- Archivos markdown de documentación
- `.vscode/` y `.idea/` (configuraciones de IDE)
- `.gitkeep` en uploads

### **3. Logging Verbose**
- Muestra información detallada en los logs
- Útil para debugging si hay errores

---

## 🚀 Cómo Usar

### **Despliegue Manual:**
1. Haz cambios en tu código
2. Commit: `git add .` → `git commit -m "mensaje"`
3. Push: `git push origin main`
4. El workflow se ejecutará automáticamente

### **Ver el Progreso:**
1. Ve a tu repositorio en GitHub
2. Click en la pestaña **Actions**
3. Verás el workflow en ejecución
4. Click en el workflow para ver los detalles

---

## 🔍 Verificación de Secrets

Para verificar que los secrets están configurados:

1. Ve a **Settings** → **Secrets and variables** → **Actions**
2. Deberías ver 3 secrets:
   - `FTP_SERVER`
   - `FTP_USERNAME`
   - `FTP_PASSWORD`

**Nota:** Por seguridad, GitHub no muestra los valores de los secrets.

---

## ⚠️ Notas Importantes

### **InfinityFree FTP:**
- **Servidor:** `ftpupload.net`
- **Puerto:** 21 (por defecto, no es necesario especificarlo)
- **Directorio remoto:** `/htdocs/`
- **Protocolo:** FTP (no FTPS/SFTP)

### **Limitaciones de InfinityFree:**
- ⚠️ InfinityFree puede tener límites de conexiones FTP simultáneas
- ⚠️ Si el despliegue falla, espera unos minutos y vuelve a intentar
- ⚠️ Archivos muy grandes pueden tardar más en subir

---

## 🐛 Troubleshooting

### **Error: "Authentication failed"**
- Verifica que los secrets estén correctamente configurados
- Verifica que el usuario y contraseña sean correctos

### **Error: "Connection timeout"**
- InfinityFree puede estar bloqueando la conexión
- Intenta de nuevo en unos minutos
- Verifica que el servidor FTP sea `ftpupload.net`

### **Error: "Directory not found"**
- Verifica que `server-dir: /htdocs/` sea correcto
- Asegúrate de que la carpeta existe en tu hosting

### **Archivos no se actualizan:**
- Verifica que no estén en la lista de `exclude`
- Revisa los logs del workflow para ver qué archivos se subieron

---

## 📚 Referencias

- [FTP-Deploy-Action v4 Documentation](https://github.com/SamKirkland/FTP-Deploy-Action)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [InfinityFree FTP Guide](https://forum.infinityfree.com/docs?topic=49)

---

**Fecha de actualización:** 2025-12-15
**Versión del workflow:** 2.0
**Estado:** ✅ Corregido y optimizado
