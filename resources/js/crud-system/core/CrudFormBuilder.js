// Construcción dinámica de formularios
export class CrudFormBuilder {
    constructor(formFields) {
        this.formFields = formFields;
    }

    generateFormHtml(entity = null) {
        return this.formFields
            .map((field) => this.generateFieldHtml(field, entity))
            .join("");
    }

    generateFieldHtml(field, entity = null) {
        const value =
            entity && entity[field.name] !== undefined
                ? entity[field.name]
                : field.value || "";
        let html = "";
        switch (field.type) {
            case "text":
            case "email":
            case "tel":
            case "url":
                html = `<label>${field.label || field.name}
                    <input type="${field.type}" name="${
                    field.name
                }" value="${value}" placeholder="${field.placeholder || ""}" ${
                    field.required ? "required" : ""
                } class="form-input" />
                </label>`;
                break;
            case "textarea":
                html = `<label>${field.label || field.name}
                    <textarea name="${field.name}" placeholder="${
                    field.placeholder || ""
                }" rows="${field.rows || 3}" ${
                    field.required ? "required" : ""
                } class="form-textarea">${value}</textarea>
                </label>`;
                break;
            case "hidden":
                html = `<input type="hidden" name="${field.name}" value="${value}" />`;
                break;
            // Puedes agregar más tipos según tus necesidades
            default:
                html = "";
        }
        return `<div class="mb-4">${html}</div>`;
    }

    populateForm(entity) {
        // Asume que los inputs ya existen en el DOM
        Object.keys(entity).forEach((key) => {
            const input = document.querySelector(`[name="${key}"]`);
            if (input) input.value = entity[key];
        });
    }
}
