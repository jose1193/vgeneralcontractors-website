/**
 * Calendar Configuration Module
 * Configuración principal para FullCalendar
 */

class CalendarConfig {
    constructor() {
        this.routes = this.getRoutes();
        this.translations = this.getTranslations();
    }

    /**
     * Obtener rutas desde las meta tags
     */
    getRoutes() {
        return {
            events:
                document.querySelector('meta[name="calendar-events-url"]')
                    ?.content || "",
            store:
                document.querySelector('meta[name="calendar-create-url"]')
                    ?.content || "",
            update:
                document.querySelector('meta[name="calendar-update-url"]')
                    ?.content || "",
            status:
                document.querySelector('meta[name="calendar-status-url"]')
                    ?.content || "",
            getClients:
                document.querySelector('meta[name="calendar-clients-url"]')
                    ?.content || "",
            createAppointment:
                document.querySelector(
                    'meta[name="calendar-create-appointment-url"]'
                )?.content || "",
        };
    }

    /**
     * Obtener traducciones desde las meta tags o variables globales
     */
    getTranslations() {
        // Las traducciones pueden estar definidas globalmente o en meta tags
        return (
            window.translations || {
                please_select_client: "Please select a client",
                appointment_created_successfully:
                    "Appointment created successfully",
                success: "Success",
                error: "Error",
                unexpected_error: "Unexpected error",
                reschedule_appointment: "Reschedule Appointment",
                move_appointment_to: "Move appointment to",
                yes_move: "Yes, move",
                cancel: "Cancel",
                moved: "Moved",
                could_not_update_appointment: "Could not update appointment",
                confirm_appointment_title: "Confirm Appointment",
                confirm_appointment_text:
                    "Are you sure you want to confirm this appointment?",
                yes_confirm: "Yes, confirm",
                confirmed: "Confirmed",
                could_not_confirm_appointment: "Could not confirm appointment",
                decline_appointment_title: "Decline Appointment",
                decline_appointment_text:
                    "Are you sure you want to decline this appointment?",
                yes_decline: "Yes, decline",
                declined: "Declined",
                could_not_decline_appointment: "Could not decline appointment",
                create_new_client: "Create New Client",
                create_confirmed_appointment: "Create Confirmed Appointment",
                create_lead: "Create Lead",
            }
        );
    }

    /**
     * Configuración principal de FullCalendar
     */
    getCalendarConfig() {
        return {
            // Core options
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek",
            },
            initialView: "timeGridWeek",
            locale: "en",
            timeZone: "local",
            navLinks: true,
            editable: true,
            selectable: true,
            selectMirror: true,
            dayMaxEvents: true,
            nowIndicator: true,

            // Time grid options
            slotDuration: "00:30:00",
            slotMinTime: "08:00:00",
            slotMaxTime: "20:00:00",
            defaultTimedEventDuration: "03:00:00", // 3 hours default duration
            businessHours: {
                daysOfWeek: [1, 2, 3, 4, 5], // Monday - Friday
                startTime: "08:00",
                endTime: "18:00",
            },

            // Event Data Source
            events: {
                url: this.routes.events,
                failure: (err) => {
                    console.error("Failed to load events:", err);
                },
                success: (events) => {
                    console.log("Events loaded successfully:", events);
                },
            },

            // Event formatting
            eventTimeFormat: {
                hour: "2-digit",
                minute: "2-digit",
                hour12: false,
            },
        };
    }

    /**
     * Configuración para el renderizado personalizado de eventos
     */
    getEventContentConfig() {
        return {
            eventContent: (arg) => {
                let content = document.createElement("div");
                content.classList.add("fc-event-content-custom");
                content.style.cursor = "pointer";
                content.style.width = "100%";
                content.style.height = "100%";

                // 1. Nombre del cliente (primera línea)
                let clientTitle = document.createElement("div");
                clientTitle.classList.add("client-title");
                clientTitle.innerHTML = arg.event.title;

                // 2. Horario (segunda línea)
                let timeText = document.createElement("div");
                timeText.classList.add("event-time");

                const start = arg.event.start;
                const end = arg.event.end;
                const startTime = start.toLocaleTimeString("es-ES", {
                    hour: "2-digit",
                    minute: "2-digit",
                    hour12: false,
                });
                const endTime = end
                    ? end.toLocaleTimeString("es-ES", {
                          hour: "2-digit",
                          minute: "2-digit",
                          hour12: false,
                      })
                    : "";

                timeText.innerHTML =
                    startTime + (endTime ? " - " + endTime : "") + " (3h)";

                // 3. Estado (última línea)
                let statusText = document.createElement("div");
                statusText.classList.add("appointment-status");
                statusText.innerHTML =
                    arg.event.extendedProps.status || "Pending";

                // Agregar todo al contenedor
                content.appendChild(clientTitle);
                content.appendChild(timeText);
                content.appendChild(statusText);

                return { domNodes: [content] };
            },
        };
    }
}

// Hacer disponible globalmente
window.CalendarConfig = CalendarConfig;
