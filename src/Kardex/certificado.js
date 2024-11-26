// Función para lanzar el confeti
function lanzarConfeti() {
    const duration = 3 * 1000; // Duración del confeti (en milisegundos)
    const end = Date.now() + duration;

    const colors = ['#bb0000', '#ffffff', '#d4af37'];

    (function frame() {
        confetti({
            particleCount: 5,
            angle: 60,
            spread: 55,
            origin: { x: 0 },
            colors: colors,
        });
        confetti({
            particleCount: 5,
            angle: 120,
            spread: 55,
            origin: { x: 1 },
            colors: colors,
        });

        if (Date.now() < end) {
            requestAnimationFrame(frame);
        }
    })();
}

// Lanzar confeti al cargar la página
window.onload = lanzarConfeti;

// Función para obtener el usuarioId del token
function obtenerUsuarioId() {
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

// Obtener curso_id del localStorage
const cursoId = localStorage.getItem("curso_id");
const usuarioId = obtenerUsuarioId();

if (usuarioId && cursoId) {
    // Llamada a la API para obtener los datos del certificado
    obtenerCertificado(usuarioId, cursoId);
}

async function obtenerCertificado(usuarioId, cursoId) {
    const url = `http://localhost/BDM-/Backend/API/APIKardex.php/kardex/certificado?usuario_id=${usuarioId}&curso_id=${cursoId}`;
    const token = localStorage.getItem("jwtToken");

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        // Verificar si hay datos en el arreglo y acceder al primer elemento
        if (data && data.length > 0) {
            const certificado = data[0];  // Obtener el primer objeto

            // Rellenar la información en el certificado
            document.getElementById('student-name').textContent = certificado.nombre_usuario;
            document.getElementById('course-name').textContent = certificado.nombre_curso;
            document.getElementById('tutor-name').textContent = certificado.nombre_instructor;

            // Llamar a la función para generar el PDF
            generarPDF(certificado);
        } else {
            console.error("No se encontraron datos del certificado.");
            Swal.fire({
                icon: 'error',
                title: 'No se encontraron datos',
                text: 'No se pudieron obtener los datos del certificado.',
            });
        }
    } catch (error) {
        console.error('Error al obtener el certificado:', error);
        // Mostrar mensaje de error
        Swal.fire({
            icon: 'error',
            title: 'Hubo un error',
            text: 'No se pudo obtener la información del certificado. Intenta nuevamente más tarde.',
            confirmButtonText: 'Cerrar'
        });
    }
}

// Función para generar y descargar el PDF
async function generarPDF(certificado) {
    const { jsPDF } = window.jspdf;

    // Crear un nuevo documento PDF en tamaño A4 horizontal (297mm x 210mm)
    const doc = new jsPDF('l', 'mm', 'a4');  // 'l' es para orientación horizontal, 'a4' es tamaño A4

    // Ruta de la imagen de fondo
    const imageURL = '../resources/images/1.png';  // Reemplaza con la ruta de tu imagen

    // Añadir la imagen de fondo al PDF (ajustar el tamaño de la imagen a las dimensiones del A4 horizontal)
    doc.addImage(imageURL, 'PNG', 0, 0, 297, 210);  // 297mm de ancho y 210mm de alto (tamaño A4 horizontal)

    // Ajustar el texto sobre la imagen proporcionalmente

    // Nombre del estudiante
    doc.setFont('helvetica', 'bold');  // 'helvetica' es la fuente y 'bold' es el estilo
    doc.setFontSize(30);
    doc.setTextColor(0, 0, 0);  // Color del texto (negro por ejemplo)
    doc.text(certificado.nombre_usuario, 117, 115);  // Posición del texto: 20mm desde el borde izquierdo y 50mm desde el borde superior

    // Nombre del curso
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(16);
    doc.text(certificado.nombre_curso, 108, 125);  // Posición del texto: 20mm desde el borde izquierdo y 70mm desde el borde superior

    // Nombre del instructor
    doc.setFontSize(20);
    doc.text(certificado.nombre_instructor, 208, 165);  // Posición del texto: 20mm desde el borde izquierdo y 90mm desde el borde superior

    // Fecha de finalización
    // doc.setFontSize(14);
    // doc.text(`Fecha de finalización: ${certificado.fecha_fin}`, 20, 110);  // Posición del texto: 20mm desde el borde izquierdo y 110mm desde el borde superior

    // Descargar el PDF
    doc.save('certificado.pdf');

    // Eliminar el curso_id de localStorage después de la descarga
    localStorage.removeItem('curso_id');
}
