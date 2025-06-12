/**
 * Generic form validation utilities
 */
export default function formValidation(config = {}) {
    return {
        form: config.initialValues || {},
        errors: {},
        isSubmitting: false,
        modalAction: config.modalAction || "",

        get hasErrors() {
            return Object.values(this.errors).some(
                (error) => error && error.length > 0
            );
        },

        init() {
            this.initFormValues();
            this.setupEventListeners();
        },

        initFormValues() {
            // If we have wire data, use it to initialize the form
            if (this.$wire) {
                for (const key in this.form) {
                    this.form[key] = this.$wire[key] || "";
                }
            }
        },

        setupEventListeners() {
            // Listen for validation errors from Livewire
            document.addEventListener("livewire:initialized", () => {
                if (this.$wire) {
                    this.$wire.on("validationErrors", (errors) => {
                        this.errors = errors;
                    });
                }
            });

            // Listen for form data updates
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

        validateRequired(value, fieldName, customMessage = null) {
            if (!value || value.trim() === "") {
                return customMessage || `${fieldName} is required`;
            }
            return "";
        },

        validateName(name, fieldName = "Name") {
            const requiredError = this.validateRequired(name, fieldName);
            if (requiredError) return requiredError;

            // Check if name contains only letters and spaces
            if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u.test(name)) {
                return `${fieldName} should only contain letters and spaces`;
            }

            return "";
        },

        validateField(field) {
            const value = this.form[field];
            let error = "";

            switch (field) {
                case "name":
                    error = this.validateName(value, "First name");
                    break;
                case "last_name":
                    error = this.validateName(value, "Last name");
                    break;
                case "email":
                    if (!this.validateEmail(value)) {
                        error = this.errors.email;
                    }
                    break;
                case "phone":
                    if (value && !this.validatePhone(value)) {
                        error = this.errors.phone;
                    }
                    break;
                case "username":
                    if (!this.validateUsername(value)) {
                        error = this.errors.username;
                    }
                    break;
                // Add other field validations as needed
            }

            this.errors[field] = error;
            return !error;
        },

        validateForm() {
            let isValid = true;
            const requiredFields = ["name", "last_name", "email"];

            // Check required fields
            for (const field of requiredFields) {
                if (!this.validateField(field)) {
                    isValid = false;
                }
            }

            // Check phone if provided
            if (this.form.phone && !this.validateField("phone")) {
                isValid = false;
            }

            // Check username in update mode
            if (
                this.modalAction === "update" &&
                !this.validateField("username")
            ) {
                isValid = false;
            }

            // Sync with Livewire if valid
            if (isValid && this.$wire) {
                this.syncToLivewire();
            }

            return isValid;
        },

        syncToLivewire() {
            if (!this.$wire) return;

            // Sync all form fields to Livewire
            for (const key in this.form) {
                this.$wire.set(key, this.form[key]);
            }
        },

        clearErrors() {
            this.errors = {};
        },

        validatePhone(phone) {
            this.errors.phone = "";
            return phoneFormat.validatePhone(phone)
                ? true
                : ((this.errors.phone =
                      "Please enter a valid phone number format: (XXX) XXX-XXXX"),
                  false);
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
                    this.$dispatch("phone-validation-failed");
                }
            });
        },

        validateLastName(lastName) {
            this.errors.last_name = "";
            if (!lastName) {
                this.errors.last_name = "Last name is required";
                return false;
            }

            // Check if lastName contains only letters and spaces
            if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u.test(lastName)) {
                this.errors.last_name =
                    "Last name should only contain letters and spaces";
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
    };
}
