function obtenerIdDesdeJWT() {
    let usuarioId = null;
    
    const token = localStorage.getItem("jwtToken");  // Cambié el nombre de la clave

    if (token && token.split(".").length === 3) {
        try {
            const decoded = jwt_decode(token);
            usuarioId = decoded.data.id; // Extrae el ID del usuario
        } catch (error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `Error al decodificar el token: ${error.message}`,
            });
            console.error("Error al decodificar el token:", error); 
        }
    } else {
        Swal.fire({
            icon: "error",
            title: "Atención",
            text: "Token inválido o no encontrado. Por favor, inicia sesión.",
        });
        console.log("Token encontrado:", token);
    }

    return usuarioId;
}


document.addEventListener("DOMContentLoaded", () => {

    // Función para llamar a la API
    function obtenerReporteTutor(id) {
        const apiUrl = `http://localhost/BDM-/Backend/API/APIReportes.php/reporte/Tutores/Buscar?id=${id}`;

        fetch(apiUrl, { method: "GET" })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("No se encontró la información del tutor.");
                }
                return response.json();
            })
            .then((data) => {
                if (!data || Object.keys(data).length === 0) {
                    Swal.fire({
                        icon: "info",
                        title: "Sin datos",
                        text: "No se encontró información para este tutor.",
                    });
                } else {
                    llenarInformacionTutor(data);
                }
            })
            .catch((error) => {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: `Error al obtener el reporte del tutor: ${error.message}`,
                });
            });
    }

    // Función para llenar la información en el HTML
    function llenarInformacionTutor(data) {
        try {
            document.querySelector("#cursosOfrecidos").textContent = data.cursos_ofrecidos || "N/A";
            document.querySelector("#fechaRegistro").textContent = data.fecha_ingreso || "N/A";
            document.querySelector("#ultimoCambio").textContent = data.ultimo_cambio || "N/A";
            document.querySelector("#ingresosTotales").textContent = `$${data.total_ganancias || 0} MX`;
        } catch (error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Hubo un problema al llenar los datos en la interfaz.",
            });
            console.error("Error al llenar la información:", error);
        }
    }

    // Flujo principal
    const tutorId = obtenerIdDesdeJWT();
    if (tutorId) {
        obtenerReporteTutor(tutorId);
    }
});
