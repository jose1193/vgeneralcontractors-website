# Sistema de Seguimiento Automático - Retell AI

## Descripción General

Este sistema implementa llamadas de seguimiento automáticas para leads con status "New" que no han agendado una cita. Anna (la IA) llamará automáticamente 2 veces por día durante 5 días máximo.

## Configuración

### Variables de Entorno Requeridas

```env
RETELL_PHONE_NUMBER=+17135876423  # Número de la empresa
RETELL_AGENT_ID=your_agent_id     # ID del agente Anna en Retell AI
RETELL_API_KEY=your_api_key       # API key de Retell AI
```

### Horarios de Llamadas

-   **9:00 AM** (Hora Central) - Llamadas matutinas
-   **4:00 PM** (Hora Central) - Llamadas vespertinas

## Funcionalidad

### Criterios de Selección de Leads

El sistema selecciona leads que cumplan **TODOS** estos criterios:

1. `status_lead = 'New'`
2. `inspection_date IS NULL` (sin cita agendada)
3. `created_at >= hace 5 días`
4. `created_at <= hace 2 horas` (para dar tiempo después del registro inicial)

### Límites Anti-Spam

-   **Máximo 10 llamadas** por lead (2 diarias x 5 días)
-   **Solo una llamada por período** (mañana/tarde) por día
-   **Pausa de 10 segundos** entre llamadas
-   **Termina automáticamente** después de 5 días o si se agenda cita

### Registro de Llamadas

Cada llamada se registra en la columna `follow_up_calls` (JSON) con:

```json
[
    {
        "call_id": "retell_call_id",
        "date": "2024-12-20",
        "time": "09:00:15",
        "period": "morning",
        "attempt": 1,
        "timezone": "America/Chicago"
    }
]
```

## Scripts de Anna por Intento

### Primeros Intentos (1-2)

```
"¡Hola [customer_name]! Le saluda Anna de V General Contractors.
Nos comunicamos porque usted mostró interés en nuestra inspección gratuita
de techo para su propiedad en [customer_address]. ¿Recuerda nuestra conversación?"
```

### Intentos Intermedios (3-6)

```
"¡Hola [customer_name]! Es Anna de V General Contractors nuevamente.
Solo quería confirmar si aún le interesa programar su inspección gratuita de techo.
Sabemos que a veces las cosas se nos olvidan en el día a día.
¿Le gustaría que agendemos su cita ahora?"
```

### Últimos Intentos (7-10)

```
"¡Hola [customer_name]! Es Anna de V General Contractors.
Esta será nuestra última llamada de seguimiento. Entendemos que quizás no sea
el momento adecuado, pero si en el futuro necesita una inspección de techo,
estaremos aquí para ayudarle. Nuestro número es (346) 692-0757.
¡Que tenga un excelente día!"
```

## Cronjobs Configurados

```bash
# Llamadas matutinas a las 9:00 AM
0 9 * * * www-data cd /var/www/html && php artisan retell:follow-up-calls --time=09:00

# Llamadas vespertinas a las 4:00 PM
0 16 * * * www-data cd /var/www/html && php artisan retell:follow-up-calls --time=16:00
```

## Comandos Artisan

### Ejecutar Seguimiento Manual

```bash
# Llamada matutina
php artisan retell:follow-up-calls --time=09:00

# Llamada vespertina
php artisan retell:follow-up-calls --time=16:00

# Verificar logs
tail -f storage/logs/retell-followup.log
```

## Logs y Monitoreo

### Archivos de Log

-   `storage/logs/retell-followup.log` - Logs específicos del seguimiento
-   `storage/logs/laravel.log` - Logs generales de errores

### Datos de Monitoreo

```json
{
  "period": "morning|afternoon",
  "total_processed": 5,
  "successful": 4,
  "failed": 1,
  "leads": [...]
}
```

## Metadatos Enviados a Retell AI

```json
{
    "type": "follow_up",
    "lead_id": 123,
    "customer_name": "Juan Pérez",
    "customer_address": "123 Main St, Houston, TX",
    "lead_source": "Website",
    "created_date": "2024-12-15",
    "follow_up_attempt": 3,
    "preferred_language": "spanish",
    "customer_phone": "(713) 456-7890",
    "customer_email": "juan@example.com"
}
```

## Detección Automática de Idioma

El sistema detecta automáticamente el idioma preferido del cliente:

### Métodos de Detección:

1. **Notas del lead**: Busca palabras clave como "english", "inglés", "spanish", "español"
2. **Análisis de nombres**: Compara con base de datos de nombres comunes en español
3. **Por defecto**: Español (dado que Houston tiene alta población hispana)

### Nombres Detectados como Español:

-   **Nombres**: Juan, María, Carlos, Ana, Luis, Carmen, José, Francisco, Antonio, Manuel, David, Miguel, Alejandro, Pedro, Pablo
-   **Apellidos**: González, Rodríguez, Hernández, López, Martínez, Pérez, García, Sánchez, Ramírez, Torres, Flores, Rivera, Gómez

### Uso en Llamadas:

-   Anna adapta automáticamente el script al idioma detectado
-   Pronunciación de números de teléfono según el idioma
-   Mensajes de voicemail en el idioma apropiado

## Reglas de Pronunciación

### Números de Teléfono

**Empresa: (346) 692-0757**

**En ESPAÑOL:**

-   **(346) 692-0757** → "tres cuatro seis - seis nueve dos - cero siete cinco siete"

**En INGLÉS:**

-   **(346) 692-0757** → "three four six - six nine two - zero seven five seven"

**Regla General:**

-   Pausas en los guiones para claridad
-   Anna debe detectar el idioma del cliente y adaptar la pronunciación
-   Si hay duda, preguntar: "¿Prefiere que continuemos en español o inglés? / Would you prefer to continue in Spanish or English?"

### Direcciones Houston

**En ESPAÑOL:**

-   **Números de casa**: Dígito por dígito → "123" = "uno dos tres"
-   **Códigos postales**: "77019" = "siete siete cero uno nueve"

**En INGLÉS:**

-   **Números de casa**: Dígito por dígito → "123" = "one two three"
-   **Códigos postales**: "77019" = "seven seven zero one nine"

### Horarios

**En ESPAÑOL:**

-   **9:00 AM** → "Nueve de la mañana"
-   **4:00 PM** → "Cuatro de la tarde"

**En INGLÉS:**

-   **9:00 AM** → "Nine AM"
-   **4:00 PM** → "Four PM"

### Detección de Idioma

Anna detectará el idioma basándose en:

1. Las primeras palabras del cliente
2. Respuestas en el idioma correspondiente
3. Si hay duda, preguntará en ambos idiomas

## Manejo de Respuestas

| Respuesta del Cliente   | Acción de Anna                               |
| ----------------------- | -------------------------------------------- |
| Quiere agendar          | Continuar con flujo principal (Tareas 6-10)  |
| No está interesado      | Despedida cortés, ofrecer número para futuro |
| Pide no volver a llamar | Marcar para no llamar más, despedida         |
| No contesta             | Dejar voicemail corto con número de contacto |

## Consideraciones Técnicas

### Base de Datos

-   Nueva columna: `follow_up_calls` (JSON) en tabla `appointments`
-   Incluido directamente en: `2024_03_21_000000_create_appointments_table.php`

### Servicios

-   `RetellAIService::createCall()` - Crear llamadas salientes
-   `TransactionService` - Para operaciones de base de datos

### Docker

-   Logs configurados en `docker-entrypoint.sh`
-   Cronjobs en `crontab.docker`
-   Permisos adecuados para usuario `www-data`

## Troubleshooting

### Verificar Configuración

```bash
# Verificar variables de entorno
php artisan tinker
>>> config('services.retellai.api_key')
>>> env('RETELL_PHONE_NUMBER')
>>> env('RETELL_AGENT_ID')
```

### Probar Comando Manualmente

```bash
# Ejecutar con output verbose
php artisan retell:follow-up-calls --time=09:00 -v
```

### Revisar Logs de Errores

```bash
# Últimos errores
tail -n 50 storage/logs/laravel.log | grep -i "retell"

# Seguimiento específico
tail -f storage/logs/retell-followup.log
```

### Verificar Cronjobs

```bash
# Dentro del container
docker exec -it cron-container crontab -l

# Verificar logs de cron
docker exec -it cron-container tail -f /var/www/html/storage/logs/cron.log
```

## Métricas de Rendimiento

-   **Tiempo entre llamadas**: 10 segundos
-   **Duración máxima por sesión**: ~5 minutos (para múltiples leads)
-   **Rate limiting**: Respeta límites de API de Retell AI
-   **Reintentos**: Solo en caso de errores de conexión

## Actualizaciones Futuras

1. **Panel de administración** para ver estadísticas de seguimiento
2. **Configuración dinámica** de horarios y límites
3. **Integración con CRM** para mejores métricas
4. **A/B testing** de scripts de seguimiento
