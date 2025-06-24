# Configuración MCP en Cursor - Paso a Paso

## 1. Abrir Configuración de Cursor

1. **Abrir Command Palette**: `Ctrl+Shift+P` (Windows) o `Cmd+Shift+P` (Mac)
2. **Buscar**: "Preferences: Open Settings (JSON)"
3. **Seleccionar**: La opción que aparece

## 2. Agregar Configuración MCP

Copia y pega esta configuración en el archivo JSON de Cursor:

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
                "BRAVE_API_KEY": "TU_API_KEY_AQUI"
            }
        },
        "github": {
            "command": "npx",
            "args": ["@modelcontextprotocol/server-github"],
            "env": {
                "GITHUB_PERSONAL_ACCESS_TOKEN": "TU_GITHUB_TOKEN_AQUI"
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

## 3. Obtener API Keys (Opcional pero Recomendado)

### Brave Search API Key:

1. Ve a: https://api.search.brave.com/
2. Crear cuenta gratuita
3. Obtener API key
4. Reemplazar "TU_API_KEY_AQUI" en la configuración

### GitHub Personal Access Token:

1. Ve a: https://github.com/settings/tokens
2. Click "Generate new token (classic)"
3. Seleccionar scopes: `repo`, `read:user`
4. Reemplazar "TU_GITHUB_TOKEN_AQUI" en la configuración

## 4. Reiniciar Cursor

Después de guardar la configuración, reinicia Cursor completamente.

## 5. Verificar Instalación

En el chat de Cursor, deberías ver opciones para usar MCP servers cuando escribas prompts.

## 6. Test Inicial

Prueba con este prompt:

```
Usando FileSystem MCP, lista todos los archivos .php en mi carpeta app/Models/
```
