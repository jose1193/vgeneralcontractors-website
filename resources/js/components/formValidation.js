export default function formValidation() {
    return {
        form: {},
        errors: {},
        isSubmitting: false,
        modalAction: "",

        init() {
            this.initFormValues();

            // Listen for validation errors from Livewire
            document.addEventListener("livewire:initialized", () => {
                Livewire.on("validationErrors", (errors) => {
                    this.errors = errors;
                });
            });

            // Listen for form data from Livewire
            window.addEventListener("form-data", (event) => {
                const data = event.detail;
                for (const key in data) {
                    if (this.form.hasOwnProperty(key)) {
                        this.form[key] = data[key];
                    }
                }
            });

            // Listen for validation errors from custom event
            window.addEventListener("validation-errors", (event) => {
                this.errors = event.detail;
            });

            // Escuchar eventos de actualización
            $wire.on("user-edit", (event) => {
                const data = event.detail;

                // Si no hay datos o la acción es 'store', limpiar el formulario
                if (!data || data.action === "store") {
                    this.form = {
                        name: "",
                        last_name: "",
                        email: "",
                        phone: "",
                        address: "",
                        city: "",
                        zip_code: "",
                        country: "",
                        gender: "",
                        date_of_birth: "",
                        username: "",
                        password: "",
                        password_confirmation: "",
                        send_password_reset: false,
                    };
                } else {
                    // Actualizar el formulario con los datos recibidos
                    this.form.name = data.name || "";
                    this.form.last_name = data.last_name || "";
                    this.form.email = data.email || "";
                    this.form.phone = data.phone || "";
                    this.form.address = data.address || "";
                    this.form.city = data.city || "";
                    this.form.zip_code = data.zip_code || "";
                    this.form.country = data.country || "";
                    this.form.gender = data.gender || "";
                    this.form.username = data.username || "";
                    this.form.date_of_birth = data.date_of_birth || "";
                }

                // Limpiar errores
                this.clearErrors();
            });
        },

        initFormValues() {
            // This should be overridden by the component that uses this mixin
        },

        validateEmail(email) {
            this.errors.email = "";
            if (!email) {
                this.errors.email = "Email is required";
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                this.errors.email = "Please enter a valid email address";
                return false;
            }

            return true;
        },

        validateUsername(username) {
            this.errors.username = "";
            if (!username) {
                this.errors.username = "Username is required";
                return false;
            }

            if (username.length < 7) {
                this.errors.username = "Username must be at least 7 characters";
                return false;
            }

            // Check if username contains at least 2 numbers
            const numbers = username.replace(/[^0-9]/g, "");
            if (numbers.length < 2) {
                this.errors.username =
                    "Username must contain at least 2 numbers";
                return false;
            }

            return true;
        },

        checkUsernameAvailability(username) {
            if (!this.validateUsername(username)) return;

            // Only check availability if username is valid
            this.$wire.checkUsernameExists(username).then((exists) => {
                if (exists) {
                    this.errors.username = "This username is already in use";
                }
            });
        },

        validatePhone(phone) {
            this.errors.phone = "";
            if (!phone) return true; // Phone is optional

            // Check for complete phone number format
            if (!/^\(\d{3}\) \d{3}-\d{4}$/.test(phone)) {
                this.errors.phone =
                    "Please enter a valid phone number format: (XXX) XXX-XXXX";
                return false;
            }
            return true;
        },

        checkEmailAvailability(email) {
            if (!this.validateEmail(email)) return;

            // Only check availability if email is valid
            this.$wire.checkEmailExists(email).then((exists) => {
                if (exists) {
                    this.errors.email = "This email is already in use";
                }
            });
        },

        checkPhoneAvailability(phone) {
            if (!this.validatePhone(phone)) return;
            if (!phone) return; // Phone is optional

            // Only check availability if phone is valid
            this.$wire.checkPhoneExists(phone).then((exists) => {
                if (exists) {
                    this.errors.phone = "This phone number is already in use";
                }
            });
        },

        clearErrors() {
            this.errors = {};
        },

        validateField(field) {
            switch (field) {
                case "email":
                    return this.validateEmail(this.form.email);
                case "phone":
                    return this.validatePhone(this.form.phone);
                case "username":
                    return this.validateUsername(this.form.username);
                default:
                    return true;
            }
        },

        validateForm() {
            let isValid = true;

            // Validate required fields
            if (!this.validateEmail(this.form.email)) isValid = false;
            if (this.form.phone && !this.validatePhone(this.form.phone))
                isValid = false;

            // Only validate username in update mode
            if (
                this.modalAction === "update" &&
                !this.validateUsername(this.form.username)
            )
                isValid = false;

            // Sync with Livewire if valid
            if (isValid) {
                this.syncToLivewire();
            }

            return isValid;
        },

        syncToLivewire() {
            // Ensure phone is properly formatted before sending to Livewire
            if (this.form.phone) {
                const phoneDigits = this.form.phone.replace(/\D/g, "");
                if (phoneDigits.length === 10) {
                    const formattedPhone = `(${phoneDigits.substring(
                        0,
                        3
                    )}) ${phoneDigits.substring(3, 6)}-${phoneDigits.substring(
                        6
                    )}`;
                    this.$wire.set("phone", formattedPhone);
                } else {
                    this.$wire.set("phone", this.form.phone);
                }
            } else {
                this.$wire.set("phone", "");
            }

            // ... rest of existing code ...
        },
    };
}
