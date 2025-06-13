export function showToast(toast, response) {
    toast.add({
        severity: response.status ? "success" : "error",
        summary: response.status ? "Success" : "Error",
        detail: response.message || "Something went wrong!",
        life: 3000,
    });
}
