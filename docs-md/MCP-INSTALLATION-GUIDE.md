# Guía de Instalación MCP Servers para VGENERALCONTRACTORS-WEB

## 1. Configuración en Cursor

### Paso 1: Abrir configuración de Cursor

-   Presiona `Ctrl+Shift+P` (Windows/Linux) o `Cmd+Shift+P` (Mac)
-   Busca "Preferences: Open Settings (JSON)"
-   Agrega la configuración MCP

### Paso 2: Configuración JSON en Cursor

```json
{
    "mcpServers": {
        "filesystem": {
            "command": "npx",
            "args": [
                "@modelcontextprotocol/server-filesystem",
                "C:/Users/ARGENIS/Documents/LARAVEL/VGENERALCONTRACTORS-WEB"
            ],
            "env": {}
        },
        "brave-search": {
            "command": "npx",
            "args": ["@modelcontextprotocol/server-brave-search"],
            "env": {
                "BRAVE_API_KEY": "tu_api_key_de_brave"
            }
        },
        "github": {
            "command": "npx",
            "args": ["@modelcontextprotocol/server-github"],
            "env": {
                "GITHUB_PERSONAL_ACCESS_TOKEN": "tu_github_token"
            }
        },
        "puppeteer": {
            "command": "npx",
            "args": ["@modelcontextprotocol/server-puppeteer"],
            "env": {}
        },
        "context7": {
            "command": "npx",
            "args": ["@modelcontextprotocol/server-context7"],
            "env": {}
        },
        "sequential-thinking": {
            "command": "npx",
            "args": ["@modelcontextprotocol/server-sequential-thinking"],
            "env": {}
        },
        "memory": {
            "command": "npx",
            "args": ["@modelcontextprotocol/server-memory"],
            "env": {}
        }
    }
}
```

## 2. Instalación de Dependencias

### Instalar MCP Servers globalmente:

```bash
# Servidores principales
npm install -g @modelcontextprotocol/server-filesystem
npm install -g @modelcontextprotocol/server-brave-search
npm install -g @modelcontextprotocol/server-github
npm install -g @modelcontextprotocol/server-puppeteer

# Servidores avanzados
npm i @upstash/context7-mcp@1.0.11
npm install -g @modelcontextprotocol/server-context7
npm install -g @modelcontextprotocol/server-sequential-thinking
npm install -g @modelcontextprotocol/server-memory

# Otros servidores útiles (opcionales)
npm install -g @modelcontextprotocol/server-sqlite
npm install -g @modelcontextprotocol/server-postgres
```

## 3. Configuración de API Keys

### Brave Search API Key:

1. Ve a https://api.search.brave.com/
2. Crea una cuenta y obtén tu API key
3. Agrega la key en la configuración

### GitHub Personal Access Token:

1. Ve a GitHub Settings > Developer settings > Personal access tokens
2. Genera un nuevo token con permisos de repo
3. Agrega el token en la configuración

## 4. Reiniciar Cursor

Después de configurar todo, reinicia Cursor para que tome los cambios.

## 5. Verificación

Una vez configurado, deberías ver las opciones de MCP disponibles en el chat de Cursor.

## 6. Descripción de Cada MCP Server

### **FileSystem MCP**

-   **Función**: Acceso directo a archivos y directorios del proyecto
-   **Uso**: Análisis de código, refactoring, auditorías de arquitectura
-   **Ejemplo**: "Analiza todos los controllers de mi proyecto Laravel"

### **Brave Search MCP**

-   **Función**: Búsquedas web en tiempo real
-   **Uso**: Investigación de tendencias, análisis de competencia, mejores prácticas
-   **Ejemplo**: "Busca las últimas técnicas de SEO para sitios de construcción"

### **GitHub MCP**

-   **Función**: Interacción con repositorios GitHub
-   **Uso**: Análisis de commits, PRs, issues, comparación con otros proyectos
-   **Ejemplo**: "Revisa los últimos commits y sugiere mejoras"

### **Puppeteer MCP**

-   **Función**: Automatización de navegador
-   **Uso**: Testing E2E, scraping, análisis de performance
-   **Ejemplo**: "Testea el flujo completo del formulario de citas"

### **Context7 MCP**

-   **Función**: Gestión avanzada de contexto
-   **Uso**: Mantener conversaciones largas con contexto, análisis multi-sesión
-   **Ejemplo**: "Mantén el contexto de todas las mejoras sugeridas"

### **Sequential Thinking MCP**

-   **Función**: Razonamiento estructurado paso a paso
-   **Uso**: Planificación compleja, resolución de problemas, arquitectura
-   **Ejemplo**: "Planifica paso a paso la migración a Laravel 11"

### **Memory MCP**

-   **Función**: Almacenamiento persistente de información
-   **Uso**: Guardar decisiones, patrones, configuraciones del proyecto
-   **Ejemplo**: "Recuerda las convenciones de código que usamos"

### **SQLite/Postgres MCP** (Opcionales)

-   **Función**: Interacción directa con bases de datos
-   **Uso**: Análisis de datos, consultas complejas, optimización
-   **Ejemplo**: "Analiza las consultas más lentas en la base de datos"

## 7. Troubleshooting

### Error: "MCP Server not found"

```bash
# Reinstalar el servidor específico
npm uninstall -g @modelcontextprotocol/server-filesystem
npm install -g @modelcontextprotocol/server-filesystem
```

### Error: "Permission denied"

```bash
# En Windows, ejecutar PowerShell como administrador
# En Linux/Mac, usar sudo
sudo npm install -g @modelcontextprotocol/server-*
```

### Error: "API Key invalid"

-   Verifica que las API keys estén correctamente configuradas
-   Revisa que no haya espacios extra en las variables de entorno

## 8. Comandos de Instalación Rápida

Para instalar todo de una vez:

```bash
npm install -g @modelcontextprotocol/server-filesystem @modelcontextprotocol/server-brave-search @modelcontextprotocol/server-github @modelcontextprotocol/server-puppeteer @modelcontextprotocol/server-context7 @modelcontextprotocol/server-sequential-thinking @modelcontextprotocol/server-memory
```
