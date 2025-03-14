import "./bootstrap";
import "../css/app.css";
import "../css/common.css"; // Import common styles
import "./common"; // Import common scripts

// Import Alpine.js
import Alpine from "alpinejs";
import formValidation from "./components/formValidation";
import { formatPhone } from "./components/phoneFormat";
import {
    setupDeleteConfirmation,
    setupRestoreConfirmation,
} from "./components/modalActions";

// Make Alpine available globally
window.Alpine = Alpine;
window.formValidation = formValidation;
window.formatPhone = formatPhone;
window.setupDeleteConfirmation = setupDeleteConfirmation;
window.setupRestoreConfirmation = setupRestoreConfirmation;

// Start Alpine
Alpine.start();
