import swal from 'sweetalert2';

window.showAlert = (type, message) => {
    if (typeof type !== 'string') {
        console.error('SweetAlert type must be a string. Received:', type);
        return;
    }
    // Your existing code
    swal.fire({
        icon: type,
        title: type.charAt(0).toUpperCase() + type.slice(1),
        text: message,
        timer: 3000,
        showConfirmButton: false
    });
};