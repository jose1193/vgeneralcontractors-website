export function formatPhone(e) {
    if (!e || !e.target) return;

    // Handle backspace/delete
    if (e.inputType === "deleteContentBackward") {
        let value = e.target.value.replace(/\D/g, "");
        value = value.substring(0, value.length - 1);

        if (value.length === 0) {
            e.target.value = "";
        } else if (value.length <= 3) {
            e.target.value = `(${value}`;
        } else if (value.length <= 6) {
            e.target.value = `(${value.substring(0, 3)}) ${value.substring(3)}`;
        } else {
            e.target.value = `(${value.substring(0, 3)}) ${value.substring(
                3,
                6
            )}-${value.substring(6)}`;
        }
        return e.target.value;
    }

    // Format as user types
    let value = e.target.value.replace(/\D/g, "").substring(0, 10);
    if (value.length >= 6) {
        value = `(${value.substring(0, 3)}) ${value.substring(
            3,
            6
        )}-${value.substring(6)}`;
    } else if (value.length >= 3) {
        value = `(${value.substring(0, 3)}) ${value.substring(3)}`;
    } else if (value.length > 0) {
        value = `(${value}`;
    }

    e.target.value = value;
    return value;
}
