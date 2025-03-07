import "./bootstrap";
import "../css/app.css";
import "../css/common.css"; // Import common styles
import "./common"; // Import common scripts

// Import Alpine.js
import Alpine from "alpinejs";

// Make Alpine available globally
window.Alpine = Alpine;

// Start Alpine
Alpine.start();
