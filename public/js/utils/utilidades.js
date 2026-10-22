/**
 * 
 * @param {Blob} blob - Contenido binario del archivo.
 * @param {Response} [response=null] - Objeto Response de Fetch para extraer el nombre del header Content-Disposition.
 * @param {string} [fallbackFilename='archivo.xlsx'] - Nombre por defecto en caso de no recibir el encabezado.
 */
function descargarBlob(blob, response = null, fallbackFilename = 'archivo.xlsx') {
    let filename = fallbackFilename;

    if (response && response.headers) {
        const disposition = response.headers.get('content-disposition');
        if (disposition) {
            const match = disposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
            if (match && match[1]) {
                filename = match[1].replace(/['"]/g, '').trim();
            }
        }
    }

    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.style.display = 'none';
    a.href = url;
    a.download = filename;

    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    a.remove();
}
