/**
 * Calendar Modals Module
 * Manejo de modales del calendario (detalles de evento, nuevo appointment)
 */

class CalendarModals {
    constructor(calendar, translations, routes) {
        this.calendar = calendar;
        this.translations = translations;
        this.routes = routes;
        this.selectedStart = null;
        this.selectedEnd = null;
        this.setupModalEventListeners();
    }

    /**
     * Configurar event listeners para modales
     */
    setupModalEventListeners() {
        // Event detail modal
        const closeEventModalBtn = document.getElementById('closeEventModalBtn');
        const eventDetailModal = document.getElementById('eventDetailModal');
        
        if (closeEventModalBtn) {
            closeEventModalBtn.addEventListener('click', () => {
                this.closeEventDetailModal();
            });
        }

        if (eventDetailModal) {
            eventDetailModal.addEventListener('click', (event) => {
                if (event.target === eventDetailModal) {
                    this.closeEventDetailModal();
                }
            });
        }

        // New appointment modal
        const closeNewAppointmentModalBtn = document.getElementById('closeNewAppointmentModalBtn');
        const newAppointmentModal = document.getElementById('newAppointmentModal');
        
        if (closeNewAppointmentModalBtn) {
            closeNewAppointmentModalBtn.addEventListener('click', () => {
                this.closeNewAppointmentModal();
            });
        }

        if (newAppointmentModal) {
            newAppointmentModal.addEventListener('click', (event) => {
                if (event.target === newAppointmentModal) {
                    this.closeNewAppointmentModal();
                }
            });
        }

        // Create appointment button
        const createAppointmentBtn = document.getElementById('createAppointmentBtn');
        if (createAppointmentBtn) {
            createAppointmentBtn.addEventListener('click', () => {
                this.handleCreateAppointment();
            });
        }

        // Action buttons
        const confirmAppointmentBtn = document.getElementById('confirmAppointmentBtn');
        const declineAppointmentBtn = document.getElementById('declineAppointmentBtn');
        
        if (confirmAppointmentBtn && window.CalendarEvents) {
            confirmAppointmentBtn.addEventListener('click', () => {
                const appointmentId = window.CalendarEvents.getCurrentAppointmentId();
                window.CalendarEvents.confirmAppointment(appointmentId);
            });
        }

        if (declineAppointmentBtn && window.CalendarEvents) {
            declineAppointmentBtn.addEventListener('click', () => {
                const appointmentId = window.CalendarEvents.getCurrentAppointmentId();
                window.CalendarEvents.declineAppointment(appointmentId);
            });
        }
    }

    /**
     * Abrir modal de detalles de evento
     */
    openEventDetailModal(event, props) {
        const modal = document.getElementById('eventDetailModal');
        if (!modal) return;

        // Poblar datos del modal
        this.populateEventDetailModal(event, props);
        
        // Mostrar modal
        modal.classList.remove('hidden');
        modal.style.display = 'block';
    }

    /**
     * Poblar modal de detalles con datos del evento
     */
    populateEventDetailModal(event, props) {
        // Título
        const titleElement = document.getElementById('modalEventTitle');
        if (titleElement) {
            titleElement.textContent = props.clientName || event.title;
        }

        // Email
        const emailElement = document.getElementById('modalEventEmail');
        if (emailElement) {
            emailElement.textContent = props.clientEmail || 'N/A';
        }

        // Teléfono
        const phoneElement = document.getElementById('modalEventPhone');
        if (phoneElement) {
            phoneElement.textContent = props.clientPhone || 'N/A';
        }

        // Fecha y hora
        const dateTimeElement = document.getElementById('modalEventDateTime');
        if (dateTimeElement) {
            const start = event.start;
            const end = event.end;
            let formattedDateTime = new Intl.DateTimeFormat('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            }).format(start);

            if (end) {
                formattedDateTime += ' - ' + new Intl.DateTimeFormat('en-US', {
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                }).format(end);
                formattedDateTime += ' (2 hours)';
            }

            dateTimeElement.textContent = formattedDateTime;
        }

        // Estado de la cita
        this.setStatusBadge('modalEventStatus', props.status);
        
        // Estado del lead
        this.setStatusBadge('modalEventLeadStatus', props.leadStatus);

        // Dirección
        const addressElement = document.getElementById('modalEventAddress');
        if (addressElement) {
            addressElement.textContent = props.address || 'N/A';
        }

        // Configurar compartir ubicación
        this.setupMapSharing(props);

        // Notas
        const notesElement = document.getElementById('modalEventNotes');
        if (notesElement) {
            notesElement.textContent = props.notes || 'N/A';
        }

        // Daños
        const damageElement = document.getElementById('modalEventDamage');
        if (damageElement) {
            damageElement.textContent = props.damage || 'N/A';
        }

        // Seguro
        this.setInsuranceBadge('modalEventInsurance', props.hasInsurance);

        // Configurar botones de acción
        this.setupActionButtons(props.status);
    }

    /**
     * Configurar badge de estado
     */
    setStatusBadge(elementId, status) {
        const element = document.getElementById(elementId);
        if (!element) return;

        element.textContent = status || 'N/A';
        element.className = 'px-2 py-1 text-xs font-semibold rounded-full text-white';

        switch (status) {
            case 'Confirmed':
                element.classList.add('bg-purple-600');
                break;
            case 'Completed':
                element.classList.add('bg-green-600');
                break;
            case 'Pending':
                element.classList.add('bg-orange-600');
                break;
            case 'Declined':
                element.classList.add('bg-red-600');
                break;
            case 'New':
                element.classList.add('bg-blue-600');
                break;
            case 'Called':
                element.classList.add('bg-green-600');
                break;
            default:
                element.classList.add('bg-gray-600');
                break;
        }
    }

    /**
     * Configurar badge de seguro
     */
    setInsuranceBadge(elementId, hasInsurance) {
        const element = document.getElementById(elementId);
        if (!element) return;

        element.className = 'px-2 py-1 text-xs font-semibold rounded-full text-white';

        if (hasInsurance === 'Yes') {
            element.textContent = 'Yes';
            element.classList.add('bg-green-600');
        } else if (hasInsurance === 'No') {
            element.textContent = 'No';
            element.classList.add('bg-red-600');
        } else {
            element.textContent = 'N/A';
            element.classList.add('bg-gray-600');
        }
    }

    /**
     * Configurar botones de acción según estado
     */
    setupActionButtons(status) {
        const statusActionButtons = document.getElementById('statusActionButtons');
        const confirmBtn = document.getElementById('confirmAppointmentBtn');

        if (!statusActionButtons) return;

        if (status === 'Completed' || status === 'Declined') {
            statusActionButtons.classList.add('hidden');
        } else {
            statusActionButtons.classList.remove('hidden');

            // Deshabilitar botón confirmar si ya está confirmado
            if (confirmBtn) {
                if (status === 'Confirmed') {
                    confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    confirmBtn.disabled = true;
                } else {
                    confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    confirmBtn.disabled = false;
                }
            }
        }
    }

    /**
     * Configurar funcionalidad de compartir ubicación
     */
    setupMapSharing(props) {
        const address = props.address || '';
        const lat = props.latitude || '';
        const lng = props.longitude || '';

        // Almacenar coordenadas en campos ocultos
        const latField = document.getElementById('event-latitude');
        const lngField = document.getElementById('event-longitude');
        
        if (latField) latField.value = lat;
        if (lngField) lngField.value = lng;

        // Crear URL de Google Maps
        const mapsUrl = (lat && lng) ?
            `https://www.google.com/maps?q=${lat},${lng}` :
            `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;

        // Configurar enlaces de compartir
        this.setupSharingLinks(address, mapsUrl);
    }

    /**
     * Configurar enlaces de compartir
     */
    setupSharingLinks(address, mapsUrl) {
        // WhatsApp
        const whatsappLink = document.getElementById('share-whatsapp');
        if (whatsappLink) {
            whatsappLink.href = `https://wa.me/?text=Location for inspection: ${encodeURIComponent(address)} - ${encodeURIComponent(mapsUrl)}`;
            whatsappLink.target = '_blank';
        }

        // Email
        const emailLink = document.getElementById('share-email');
        if (emailLink) {
            const subject = encodeURIComponent('Location for inspection');
            const body = encodeURIComponent(`The location for the inspection is: ${address}\n\nView in Google Maps: ${mapsUrl}`);
            emailLink.href = `mailto:?subject=${subject}&body=${body}`;
        }

        // Google Maps
        const mapsLink = document.getElementById('share-maps');
        if (mapsLink) {
            mapsLink.href = mapsUrl;
            mapsLink.target = '_blank';
        }

        // Botón copiar
        const copyButton = document.getElementById('copy-address');
        if (copyButton) {
            copyButton.onclick = (e) => {
                e.preventDefault();
                navigator.clipboard.writeText(mapsUrl).then(() => {
                    const originalHTML = copyButton.innerHTML;
                    copyButton.innerHTML = '<svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Copied!';
                    setTimeout(() => {
                        copyButton.innerHTML = originalHTML;
                    }, 2000);
                });
            };
        }

        // Habilitar/deshabilitar botones según disponibilidad de dirección
        const buttons = [whatsappLink, emailLink, mapsLink, copyButton];
        buttons.forEach(button => {
            if (button) {
                if (!address) {
                    button.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    button.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        });
    }

    /**
     * Cerrar modal de detalles de evento
     */
    closeEventDetailModal() {
        const modal = document.getElementById('eventDetailModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
    }

    /**
     * Abrir modal de nueva cita
     */
    openNewAppointmentModal(start, end) {
        this.selectedStart = start;
        this.selectedEnd = end;

        const modal = document.getElementById('newAppointmentModal');
        if (!modal) return;

        // Formatear fecha y hora
        this.formatSelectedDateTime(start, end);

        // Cargar clientes si es necesario
        const clientSelector = document.getElementById('clientSelector');
        if (clientSelector && clientSelector.options.length <= 1) {
            if (window.CalendarAPI) {
                window.CalendarAPI.loadClients();
            }
        }

        // Mostrar modal
        modal.classList.remove('hidden');
        modal.style.display = 'block';
    }

    /**
     * Formatear fecha y hora seleccionada
     */
    formatSelectedDateTime(start, end) {
        const selectedDateTime = document.getElementById('selectedDateTime');
        const appointmentDate = document.getElementById('appointmentDate');
        const appointmentTime = document.getElementById('appointmentTime');

        if (!selectedDateTime || !appointmentDate || !appointmentTime) return;

        // Formatear fecha para mostrar
        const formattedDate = start.toLocaleDateString('en-US', {
            month: 'long',
            day: 'numeric',
            year: 'numeric'
        });

        // Asegurar que la cita dure 2 horas
        const actualEnd = new Date(start.getTime() + (2 * 60 * 60 * 1000));

        const formattedTime = start.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }) + ' - ' + actualEnd.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }) + ' (2 hours)';

        // Mostrar fecha y hora formateada
        selectedDateTime.value = formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1) + ' • ' + formattedTime;

        // Configurar campos ocultos para envío
        const dateStr = start.toISOString().split('T')[0]; // YYYY-MM-DD
        const timeStr = start.toTimeString().substring(0, 5); // HH:MM

        appointmentDate.value = dateStr;
        appointmentTime.value = timeStr;
    }

    /**
     * Cerrar modal de nueva cita
     */
    closeNewAppointmentModal() {
        const modal = document.getElementById('newAppointmentModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        // Limpiar selección en calendario
        if (this.calendar) {
            this.calendar.unselect();
        }
    }

    /**
     * Manejar creación de cita
     */
    handleCreateAppointment() {
        const clientSelector = document.getElementById('clientSelector');
        
        // Validar que se haya seleccionado un cliente
        if (!clientSelector || !clientSelector.value) {
            Swal.fire(this.translations.error, this.translations.please_select_client, 'error');
            return;
        }

        // Crear cita usando CalendarAPI
        if (window.CalendarAPI) {
            window.CalendarAPI.createAppointment();
        }
    }
}

// Hacer disponible globalmente
window.CalendarModals = CalendarModals;