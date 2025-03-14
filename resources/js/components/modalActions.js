export function setupDeleteConfirmation() {
    return {
        showDeleteModal: false,
        itemToDelete: null,
        isDeleting: false,

        init() {
            window.addEventListener("delete-confirmation", (event) => {
                this.itemToDelete = event.detail;
                this.showDeleteModal = true;
            });

            window.addEventListener("itemDeleted", () => {
                this.showDeleteModal = false;
                this.isDeleting = false;
                this.itemToDelete = null;
            });
        },

        confirmDelete(deleteFunction) {
            this.isDeleting = true;
            deleteFunction(this.itemToDelete.uuid)
                .then(() => {
                    this.showDeleteModal = false;
                    this.isDeleting = false;
                    this.itemToDelete = null;
                })
                .catch((error) => {
                    console.error("Error deleting item:", error);
                    this.isDeleting = false;
                });
        },
    };
}

export function setupRestoreConfirmation() {
    return {
        showRestoreModal: false,
        itemToRestore: null,
        isRestoring: false,

        init() {
            window.addEventListener("restore-confirmation", (event) => {
                this.itemToRestore = event.detail;
                this.showRestoreModal = true;
            });

            window.addEventListener("itemRestored", () => {
                this.showRestoreModal = false;
                this.isRestoring = false;
                this.itemToRestore = null;
            });
        },

        confirmRestore(restoreFunction) {
            this.isRestoring = true;
            restoreFunction(this.itemToRestore.uuid)
                .then(() => {
                    this.showRestoreModal = false;
                    this.isRestoring = false;
                    this.itemToRestore = null;
                })
                .catch((error) => {
                    console.error("Error restoring item:", error);
                    this.isRestoring = false;
                });
        },
    };
}
