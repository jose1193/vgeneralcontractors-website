/**
 * Debug script for invoice-demos system
 * Add this script to test if everything is loading correctly
 */

document.addEventListener("DOMContentLoaded", function () {
    console.log("🔧 INVOICE SYSTEM DEBUG");

    // Check 1: Dependencies
    setTimeout(() => {
        console.log("📋 Dependency Check:");
        console.log("  - Alpine.js:", !!window.Alpine);
        console.log("  - jQuery:", !!window.jQuery);
        console.log("  - InvoiceDemoManager:", !!window.invoiceDemoManager);
        console.log("  - Translations:", !!window.translations);
        console.log(
            "  - invoiceDemoData function:",
            typeof window.invoiceDemoData
        );

        // Check 2: Translation sample
        if (window.translations) {
            console.log("🌐 Translation Sample:");
            console.log("  - draft:", window.translations.draft);
            console.log("  - sent:", window.translations.sent);
            console.log("  - wind:", window.translations.wind);
        }

        // Check 3: Manager functionality
        if (window.invoiceDemoManager) {
            console.log("⚡ Manager Test:");
            try {
                const testTranslation = window.invoiceDemoManager.__("draft");
                console.log("  - Translation test (draft):", testTranslation);
                console.log(
                    "  - Format decimal test:",
                    window.invoiceDemoManager.formatDecimal(2500)
                );
            } catch (error) {
                console.error("  - Manager error:", error);
            }
        }

        // Check 4: Alpine.js component
        if (typeof window.invoiceDemoData === "function") {
            console.log("🎯 Alpine Component Test:");
            try {
                const componentData = window.invoiceDemoData();
                console.log("  - Component created successfully");
                console.log(
                    "  - Has init method:",
                    typeof componentData.init === "function"
                );
                console.log(
                    "  - Form data statuses:",
                    componentData.formData?.statuses?.length || 0
                );
            } catch (error) {
                console.error("  - Component error:", error);
            }
        }

        console.log("✅ Debug check complete");
    }, 1000);
});
