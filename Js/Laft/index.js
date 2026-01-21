$(document).ready(function() {

    loadPartial('_Partials/_Inicio.php');

    function mostrarSpinner() {
        document.getElementById('spinner-overlay').style.display = 'flex';
    }
    
    function ocultarSpinner() {
        document.getElementById('spinner-overlay').style.display = 'none';
    }

    function validateForm(form) {
        $(form).find('.is-invalid').removeClass('is-invalid');
        $(form).find('.is-valid').removeClass('is-valid');
        $(form).find('.error-message').hide();

        if (!form.checkValidity()) {
            $(form).addClass('was-validated');
            return false;
        }

        var isValid = true;
        $(form).find('input, select').each(function() {
            var $this = $(this);
            if ($this.prop('required') && (!$this.val() || $this.val() === "")) {
                $this.addClass('is-invalid');
                $this.siblings('.error-message').show();
                isValid = false;
            } else {
                $this.addClass('is-valid');
            }
        });

        var hasCheckboxError = false;

        for (let i = 1; i <= 4; i++) {
            let checkboxGroup = $(form).find('.checkbox-group-' + i + ' input[type="checkbox"]');
            if (checkboxGroup.length > 0) { // Verificar si el grupo de checkboxes existe
                let groupValid = false;
                checkboxGroup.each(function() {
                    if ($(this).is(':checked')) {
                        groupValid = true;
                    }
                });
                if (!groupValid) {
                    hasCheckboxError = true;
                    $(form).find('.checkbox-group-' + i).addClass('is-invalid');
                } else {
                    $(form).find('.checkbox-group-' + i).addClass('is-valid');
                }
            }
        }
    
        if (hasCheckboxError) {
            $('.error-message-general').show();
            isValid = false;
        }else{
            $('.error-message-general').hide();
            isValid = true;
        }

        var desdeCuandoPep = $(form).find('input[name="desde_cuando_pep"]').val();
        var hastaCuandoPep = $(form).find('input[name="hasta_cuando_pep"]').val();

        if (desdeCuandoPep && hastaCuandoPep) {
            if (new Date(desdeCuandoPep) > new Date(hastaCuandoPep)) {
                $(form).find('input[name="desde_cuando_pep"], input[name="hasta_cuando_pep"]').val('').removeClass('is-valid').addClass('is-invalid');
                $('.error-message-general').show();
                isValid = false;
            }
        }

        return isValid;
    }

    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
    
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
    
                    form.classList.add('was-validated');
                }, false);
            });
    })();

    function loadPartial(partial, callback) {
        $('#content').load(partial, function(response, status, xhr) {
            if (status == "success") {
                if (typeof callback === 'function') {
                    callback();
                }
            } else if (status == "error") {
                console.error("Error al cargar el archivo parcial:", xhr.status, xhr.statusText);
            }
        });
    }

    function cargarTipoPersona(){

        var tipoPersona = null;
        $.ajax({
            url: '../../Actions/Laft/tipoPersona.php',
            type: 'POST',
            data: { tipoPersona: tipoPersona },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    var tipoPersonaLaft = data.tipo_persona_laft;
                    ocultarSpinner();
                    if (tipoPersonaLaft) {
                        loadPartial('_Partials/_TipoPersona.php', function() {
                            $('#tipo_persona').val(tipoPersonaLaft);
                        });
                    }
                }else{
                    loadPartial('_Partials/_TipoPersona.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
                ocultarSpinner();
            }
        });
    }

    function cargarPersonaNatural(){

        var isconsulta = true;
        $.ajax({
            url: '../../Actions/Laft/personaNatural.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if(data.success) {
                    loadPartial('_Partials/_PersonaNatural.php', function() {
                        Object.keys(data.personaNatural).forEach(function(key) {
                            var value = data.personaNatural[key];
                            if(key == "condicion_pago" && value == "Otro"){
                                var otroFieldContainer = document.getElementById("otroFieldContainer");
                                var otroFieldInput = document.getElementById("cuantos_dias_condicion_pago");
                                otroFieldContainer.style.display = "block";
                                otroFieldInput.setAttribute("required", "required");
                            }
                            if(key == "requiere_permiso_licencia_operar"){
                                if(value == "1"){
                                    var flexSwitchCheck1 = document.getElementById("flexSwitchCheck1");
                                    var fileFieldContainer = document.getElementById("fileFieldContainer");
                                    flexSwitchCheck1.checked = true;
                                    fileFieldContainer.style.display = "block";

                                    var filePreviewContainer = document.getElementById("filePreviewContainer");
                                    var filePreviewContent = document.getElementById("filePreviewContent");
                                    var modifyFileButton = document.getElementById("modifyFileButton");
                                    var fileFieldContainer = document.getElementById("fileFieldContainer");

                                    if (data.personaNatural.documento_laft) {

                                        var documento = data.personaNatural.documento_laft;
                                        
                                        if (documento!== undefined) {
                                            var fileType = documento.split('.').pop().toLowerCase();
                                    
                                            filePreviewContent.innerHTML = '';
                                            filePreviewContainer.style.display = 'block';
                                    
                                            if (fileType === 'pdf') {
                                                var iframe = document.createElement("iframe");
                                                iframe.src = documento + "#toolbar=0&navpanes=0&scrollbar=0";
                                                iframe.width = "100%";
                                                iframe.height = "200px";
                                                filePreviewContent.appendChild(iframe);
                                            }
                                    
                                            fileFieldContainer.style.display = "none";
                                            modifyFileButton.style.display = "block";

                                        } else {
                                            fileFieldContainer.style.display = "block";
                                            filePreviewContainer.style.display = "none";
                                            modifyFileButton.style.display = "none";
                                        }
                                    }
                                }else{
                                    var flexSwitchCheck2 = document.getElementById("flexSwitchCheck2");
                                    var fileFieldContainer = document.getElementById("fileFieldContainer");
                                    flexSwitchCheck2.checked = true;
                                    fileFieldContainer.style.display = "none";
                                }
                            }
                            $('[name="' + key + '"]').val(value);
                        });
                    });
                }else{
                    loadPartial('_Partials/_PersonaNatural.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }

    function cargarPepInfoGeneral(){
        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/pepInfoGeneral.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    loadPartial('_Partials/_PepInfoGeneral.php', function() {
                        Object.keys(data.pepInfoGeneral).forEach(function(key) {

                            var value = data.pepInfoGeneral[key];

                            var maneja_o_ha_manejado_recursos_publicos_chk1 = document.getElementById('maneja_o_ha_manejado_recursos_publicos_chk1');
                            var maneja_o_ha_manejado_recursos_publicos_chk2 = document.getElementById('maneja_o_ha_manejado_recursos_publicos_chk2');

                            if(key == "maneja_o_ha_manejado_recursos_publicos" && value == "1"){
                                maneja_o_ha_manejado_recursos_publicos_chk1.checked = true;
                            }else if(key == "maneja_o_ha_manejado_recursos_publicos" && value == "0"){
                                maneja_o_ha_manejado_recursos_publicos_chk2.checked = true;
                            }
                            
                            var tiene_o_ha_tenido_cargo_publico_chk1 = document.getElementById("tiene_o_ha_tenido_cargo_publico_chk1");
                            var tiene_o_ha_tenido_cargo_publico_chk2 = document.getElementById("tiene_o_ha_tenido_cargo_publico_chk2");

                            if(key == "tiene_o_ha_tenido_cargo_publico" &&value == "1"){
                                tiene_o_ha_tenido_cargo_publico_chk1.checked = true;
                            }else if(key == "tiene_o_ha_tenido_cargo_publico" &&value == "0"){
                                tiene_o_ha_tenido_cargo_publico_chk2.checked = true;
                            }

                            var ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk1 = document.getElementById("ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk1");
                            var ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk2 = document.getElementById("ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk2");


                            if(key == "ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales" &&value == "1"){
                                ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk1.checked = true;
                            }else if(key == "ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales" &&value == "0"){
                                ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk2.checked = true;
                            }

                            var ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk1 = document.getElementById("ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk1");
                            var ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk2 = document.getElementById("ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk2");

                            if(key == "ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia" &&value == "1"){
                                ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk1.checked = true;
                            }else  if(key == "ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia" &&value == "0"){
                                ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk2.checked = true;
                            }
                        });
                    });
                }else{
                    loadPartial('_Partials/_PepInfoGeneral.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }

    function cargarPep(){
        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/pep.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    loadPartial('_Partials/_Pep.php', function() {
                        
                        data.pep.forEach(function(valuedata, index) {
                            if(index == 0){
                                Object.keys(valuedata).forEach(function(key) {
                                    var value = valuedata[key];
                                    $('[name="' + key + '[]"]').val(value);
                                });
                            }else{
                                agregarFormularioPep(function() {
                                    var nuevoFormId = 'formRow_' + contadorPEPS;
                                    var nuevoForm = $('#' + nuevoFormId);
                                
                                    Object.keys(valuedata).forEach(function(key) {
                                        var value = valuedata[key];
                                        nuevoForm.find('[name="' + key + '[]"]').val(value);
                                    });
                                });
                            }
                            
                        });
                    });
                }else{
                    loadPartial('_Partials/_Pep.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }
    
    function cargarOrigenesFondos(){
        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/origenesFondos.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    loadPartial('_Partials/_OrigenesFondos.php', function() {
                        Object.keys(data.origenesFondos).forEach(function(key) {
                            var value = data.origenesFondos[key];
                            
                            if(key == "declaracion_origen_fondos_informacion" && value != null){
                                var declaracion_origen_fondos_informacion_chk1 = document.getElementById("declaracion_origen_fondos_informacion_chk1");
                                var declaracion_origen_fondos_informacion_chk2 = document.getElementById("declaracion_origen_fondos_informacion_chk2");
                                if(value == "1"){
                                    declaracion_origen_fondos_informacion_chk1.checked = true;
                                }else{
                                    declaracion_origen_fondos_informacion_chk2.checked = true;
                                }
                            }
                        });
                    });
                }else{
                    loadPartial('_Partials/_OrigenesFondos.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }

    function cargarAutorizacionProteccionDatos(){
        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/autorizacionProteccionDatos.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    loadPartial('_Partials/_AutorizacionProteccionDatos.php', function() {
                        Object.keys(data.autorizacionProteccionDatos).forEach(function(key) {
                            var value = data.autorizacionProteccionDatos[key];
                            
                            if(key == "autorizacion_proteccion_datos" && value != null){
                                var autorizacion_proteccion_datos_chk1 = document.getElementById("autorizacion_proteccion_datos_chk1");
                                var autorizacion_proteccion_datos_chk2 = document.getElementById("autorizacion_proteccion_datos_chk2");
                                if(value == "1"){
                                    autorizacion_proteccion_datos_chk1.checked = true;
                                }else{
                                    autorizacion_proteccion_datos_chk2.checked = true;
                                }
                            }
                        });
                    });
                }else{
                    loadPartial('_Partials/_AutorizacionProteccionDatos.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }

    function cargarDeclaracionEtica(){
        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/declaracionEtica.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    loadPartial('_Partials/_DeclaracionEtica.php', function() {
                        Object.keys(data.declaracionEtica).forEach(function(key) {
                            var value = data.declaracionEtica[key];
                            
                            if(key == "declaracion_etica" && value != null){
                                var declaracion_etica_chk1 = document.getElementById("declaracion_etica_chk1");
                                var declaracion_etica_chk2 = document.getElementById("declaracion_etica_chk2");
                                if(value == "1"){
                                    declaracion_etica_chk1.checked = true;
                                }else{
                                    declaracion_etica_chk2.checked = true;
                                }
                            }
                        });
                    });
                }else{
                    loadPartial('_Partials/_AutorizacionProteccionDatos.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }

    function cargarPersonaJuridica(){
        mostrarSpinner();
        var isconsulta = true;
        $.ajax({
            url: '../../Actions/Laft/personaJuridica.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                
                var data = JSON.parse(response);
                ocultarSpinner();
                if(data.success) {
                    loadPartial('_Partials/_PersonaJuridica.php', function() {
                        Object.keys(data.personaJuridica).forEach(function(key) {
                            var value = data.personaJuridica[key];
                            if(key == "condicion_pago" && value == "Otro"){
                                var otroFieldContainer = document.getElementById("otroFieldContainer");
                                var otroFieldInput = document.getElementById("cuantos_dias_condicion_pago");
                                otroFieldContainer.style.display = "block";
                                otroFieldInput.setAttribute("required", "required");
                            }
                            if(key == "requiere_permiso_licencia_operar"){
                                if(value == "1"){
                                    var flexSwitchCheck1 = document.getElementById("flexSwitchCheck1");
                                    var fileFieldContainer = document.getElementById("fileFieldContainer");
                                    flexSwitchCheck1.checked = true;
                                    fileFieldContainer.style.display = "block";

                                    var filePreviewContainer = document.getElementById("filePreviewContainer");
                                    var filePreviewContent = document.getElementById("filePreviewContent");
                                    var modifyFileButton = document.getElementById("modifyFileButton");
                                    var fileFieldContainer = document.getElementById("fileFieldContainer");
                
                                    for (var i = 0; i < data.documentos.length; i++) {
                                        var documento = data.documentos[i];
                                        
                                        if (documento.tipo_documento_laft == "requiere_permiso_licencia") {
                                            var fileType = documento.documento_laft.split('.').pop().toLowerCase();
                                    
                                            filePreviewContent.innerHTML = '';
                                            filePreviewContainer.style.display = 'block';
                                    
                                            if (fileType === 'pdf') {
                                                var iframe = document.createElement("iframe");
                                                iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                                iframe.width = "100%";
                                                iframe.height = "200px";
                                                filePreviewContent.appendChild(iframe);
                                            }
                                    
                                            fileFieldContainer.style.display = "none";
                                            modifyFileButton.style.display = "block";
                                    
                                            break;  // Salir del bucle cuando se cumple la condición
                                        } else {
                                            fileFieldContainer.style.display = "block";
                                            filePreviewContainer.style.display = "none";
                                            modifyFileButton.style.display = "none";
                                        }
                                    }
                                }else{
                                    var flexSwitchCheck2 = document.getElementById("flexSwitchCheck2");
                                    var fileFieldContainer = document.getElementById("fileFieldContainer");
                                    flexSwitchCheck2.checked = true;
                                    fileFieldContainer.style.display = "none";
                                }
                            }
                            if(key == "ISO_9001" && value == "1"){
                                var ISO_9001 = document.getElementById("ISO_9001");
                                ISO_9001.checked = true;

                                var filePreviewContainerISO_9001 = document.getElementById("filePreviewContainerISO_9001");
                                var filePreviewContentISO_9001 = document.getElementById("filePreviewContentISO_9001");
                                var modifyFileButtonISO_9001 = document.getElementById("modifyFileButtonISO_9001");
                                var file_ISO_9001 = document.getElementById("file_ISO_9001");
                                
                                for (var i = 0; i < data.documentos.length; i++) {
                                    var documento = data.documentos[i];
                                    if (documento.tipo_documento_laft == "ISO_9001") {
                                        var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                        filePreviewContentISO_9001.innerHTML = '';
                                        filePreviewContainerISO_9001.style.display = 'block';
                
                                        if (fileType === 'pdf') {
                                            var iframe = document.createElement("iframe");
                                            iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                            iframe.width = "100%";
                                            iframe.height = "200px";
                                            filePreviewContentISO_9001.appendChild(iframe);
                                        }

                                        file_ISO_9001.style.display = "none";
                                        modifyFileButtonISO_9001.style.display = "block";

                                        break;
                                    }else {
                                        file_ISO_9001.style.display = "block";
                                        filePreviewContainerISO_9001.style.display = "none";
                                        modifyFileButtonISO_9001.style.display = "none";
                                    }
                                }
                            }

                            if(key == "ISO_14001" && value == "1"){
                                var ISO_14001 = document.getElementById("ISO_14001");
                                ISO_14001.checked = true;

                                var filePreviewContainerISO_14001 = document.getElementById("filePreviewContainerISO_14001");
                                var filePreviewContentISO_14001 = document.getElementById("filePreviewContentISO_14001");
                                var modifyFileButtonISO_14001 = document.getElementById("modifyFileButtonISO_14001");
                                var file_ISO_14001 = document.getElementById("file_ISO_14001");
                                
                                for (var i = 0; i < data.documentos.length; i++) {
                                    var documento = data.documentos[i];
                                    if (documento.tipo_documento_laft == "ISO_14001") {
                                        var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                        filePreviewContentISO_14001.innerHTML = '';
                                        filePreviewContainerISO_14001.style.display = 'block';
                
                                        if (fileType === 'pdf') {
                                            var iframe = document.createElement("iframe");
                                            iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                            iframe.width = "100%";
                                            iframe.height = "200px";
                                            filePreviewContentISO_14001.appendChild(iframe);
                                        }

                                        file_ISO_14001.style.display = "none";
                                        modifyFileButtonISO_14001.style.display = "block";

                                        break;
                                    }else {
                                        file_ISO_14001.style.display = "block";
                                        filePreviewContainerISO_14001.style.display = "none";
                                        modifyFileButtonISO_14001.style.display = "none";
                                    }
                                }
                            }
                            if(key == "ISO_45001" && value == "1"){
                                var ISO_45001 = document.getElementById("ISO_45001");
                                ISO_45001.checked = true;

                                var filePreviewContainerISO_45001 = document.getElementById("filePreviewContainerISO_45001");
                                var filePreviewContentISO_45001 = document.getElementById("filePreviewContentISO_45001");
                                var modifyFileButtonISO_45001 = document.getElementById("modifyFileButtonISO_45001");
                                var file_ISO_45001 = document.getElementById("file_ISO_45001");
                                
                                for (var i = 0; i < data.documentos.length; i++) {
                                    var documento = data.documentos[i];
                                    if (documento.tipo_documento_laft == "ISO_45001") {
                                        var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                        filePreviewContentISO_45001.innerHTML = '';
                                        filePreviewContainerISO_45001.style.display = 'block';
                
                                        if (fileType === 'pdf') {
                                            var iframe = document.createElement("iframe");
                                            iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                            iframe.width = "100%";
                                            iframe.height = "200px";
                                            filePreviewContentISO_45001.appendChild(iframe);
                                        }

                                        file_ISO_45001.style.display = "none";
                                        modifyFileButtonISO_45001.style.display = "block";

                                        break;
                                    }else {
                                        file_ISO_45001.style.display = "block";
                                        filePreviewContainerISO_45001.style.display = "none";
                                        modifyFileButtonISO_45001.style.display = "none";
                                    }
                                }
                            }
                            if(key == "BASC" && value == "1"){
                                var BASC = document.getElementById("BASC");
                                BASC.checked = true;

                                var filePreviewContainerBASC = document.getElementById("filePreviewContainerBASC");
                                var filePreviewContentBASC = document.getElementById("filePreviewContentBASC");
                                var modifyFileButtonBASC = document.getElementById("modifyFileButtonBASC");
                                var file_BASC = document.getElementById("file_BASC");
                                
                                for (var i = 0; i < data.documentos.length; i++) {
                                    var documento = data.documentos[i];
                                    if (documento.tipo_documento_laft == "BASC") {
                                        var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                        filePreviewContentBASC.innerHTML = '';
                                        filePreviewContainerBASC.style.display = 'block';
                
                                        if (fileType === 'pdf') {
                                            var iframe = document.createElement("iframe");
                                            iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                            iframe.width = "100%";
                                            iframe.height = "200px";
                                            filePreviewContentBASC.appendChild(iframe);
                                        }

                                        file_BASC.style.display = "none";
                                        modifyFileButtonBASC.style.display = "block";

                                        break;
                                    }else {
                                        file_BASC.style.display = "block";
                                        filePreviewContainerBASC.style.display = "none";
                                        modifyFileButtonBASC.style.display = "none";
                                    }
                                }
                            }
                            if(key == "OEA" && value == "1"){
                                var OEA = document.getElementById("OEA");
                                OEA.checked = true;

                                var filePreviewContainerOEA = document.getElementById("filePreviewContainerOEA");
                                var filePreviewContentOEA = document.getElementById("filePreviewContentOEA");
                                var modifyFileButtonOEA = document.getElementById("modifyFileButtonOEA");
                                var file_OEA = document.getElementById("file_OEA");
                                
                                for (var i = 0; i < data.documentos.length; i++) {
                                    var documento = data.documentos[i];
                                    if (documento.tipo_documento_laft == "OEA") {
                                        var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                        filePreviewContentOEA.innerHTML = '';
                                        filePreviewContainerOEA.style.display = 'block';
                
                                        if (fileType === 'pdf') {
                                            var iframe = document.createElement("iframe");
                                            iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                            iframe.width = "100%";
                                            iframe.height = "200px";
                                            filePreviewContentOEA.appendChild(iframe);
                                        }

                                        file_OEA.style.display = "none";
                                        modifyFileButtonOEA.style.display = "block";

                                        break;
                                    }else {
                                        file_OEA.style.display = "block";
                                        filePreviewContainerOEA.style.display = "none";
                                        modifyFileButtonOEA.style.display = "none";
                                    }
                                }
                            }
                            if(key == "otro_certificacion" && value != null){
                                
                                var otro_certificacion = document.getElementById("otroFieldContainer2");
                                otro_certificacion.style.display = "block";
                                var Otro = document.getElementById("Otro");
                                Otro.checked = true;

                                
                                $('#otro_certificacion').attr("required", "required");

                                var filePreviewContainerOtro = document.getElementById("filePreviewContainerOtro");
                                var filePreviewContentOtro = document.getElementById("filePreviewContentOtro");
                                var modifyFileButtonOtro = document.getElementById("modifyFileButtonOtro");
                                var file_Otro = document.getElementById("file_Otro");
                                
                                for (var i = 0; i < data.documentos.length; i++) {
                                    var documento = data.documentos[i];
                                    if (documento.tipo_documento_laft == "Otro") {
                                        var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                        filePreviewContentOtro.innerHTML = '';
                                        filePreviewContainerOtro.style.display = 'block';
                
                                        if (fileType === 'pdf') {
                                            var iframe = document.createElement("iframe");
                                            iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                            iframe.width = "100%";
                                            iframe.height = "200px";
                                            filePreviewContentOtro.appendChild(iframe);
                                        }

                                        file_Otro.style.display = "none";
                                        modifyFileButtonOtro.style.display = "block";

                                        break;
                                    }else {
                                        file_Otro.style.display = "block";
                                        filePreviewContainerOtro.style.display = "none";
                                        modifyFileButtonOtro.style.display = "none";
                                    }
                                }
                            }
                            $('[name="' + key + '"]').val(value);
                        });
                    });
                }else{
                    loadPartial('_Partials/_PersonaJuridica.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }
    
    function cargarRepresentanteLegal(){

        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/representanteLegal.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    loadPartial('_Partials/_RepresentanteLegal.php', function() {
                        Object.keys(data.representanteLegal).forEach(function(key) {
                            var value = data.representanteLegal[key];
                            $('[name="' + key + '"]').val(value);
                        });
                    });
                }else{
                    loadPartial('_Partials/_RepresentanteLegal.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }

    function cargarSuplenteRepresentanteLegal(){
        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/suplenteRepresentanteLegal.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    loadPartial('_Partials/_SuplenteRepresentanteLegal.php', function() {
                        Object.keys(data.suplenteRepresentanteLegal).forEach(function(key) {
                            var value = data.suplenteRepresentanteLegal[key];
                            $('[name="' + key + '"]').val(value);
                        });
                    });
                }else{
                    loadPartial('_Partials/_SuplenteRepresentanteLegal.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }

    var contadorBeneficiariosFinales = 0;

    function cargarBeneficiariosFinales() {
        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/cartaBeneficiariosFinales.php',
            type: 'POST',
            data: { isconsulta: isconsulta },
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    var cartabeneficiariosFinales = data.cartabeneficiariosFinales;
                    if (cartabeneficiariosFinales.length > 0) {
                        loadPartial('_Partials/_CartaBeneficiariosFinales.php', function() {
                            var filePreviewContainercarta_beneficiarios_finales = document.getElementById("filePreviewContainercarta_beneficiarios_finales");
                            var filePreviewContentcarta_beneficiarios_finales = document.getElementById("filePreviewContentcarta_beneficiarios_finales");
                            var modifyFileButtoncarta_beneficiarios_finales = document.getElementById("modifyFileButtoncarta_beneficiarios_finales");
                            var file_carta_beneficiarios_finales = document.getElementById("file_carta_beneficiarios_finales");
                            
                            var fileType = cartabeneficiariosFinales[0].documento_laft.split('.').pop().toLowerCase();

                            filePreviewContentcarta_beneficiarios_finales.innerHTML = '';
                            filePreviewContainercarta_beneficiarios_finales.style.display = 'block';
    
                            if (fileType === 'pdf') {
                                var iframe = document.createElement("iframe");
                                iframe.src = cartabeneficiariosFinales[0].documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                iframe.width = "100%";
                                iframe.height = "200px";
                                filePreviewContentcarta_beneficiarios_finales.appendChild(iframe);
                            }

                            file_carta_beneficiarios_finales.style.display = "none";
                            modifyFileButtoncarta_beneficiarios_finales.style.display = "block";
                            $('#carta_beneficiarios_finales').removeAttr("required", "required");
                        });
                    } else {
                        loadPartial('_Partials/_CartaBeneficiariosFinales.php');
                    }
                } else {
                    loadPartial('_Partials/_CartaBeneficiariosFinales.php');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
            }
        });
    }

    function cargarCartaBeneficiariosFinales() {
        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/cartaBeneficiariosFinales.php',
            type: 'POST',
            data: { isconsulta: isconsulta },
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    var cartabeneficiariosFinales = data.cartabeneficiariosFinales;
                    if (cartabeneficiariosFinales.length > 0) {
                        loadPartial('_Partials/_CartaBeneficiariosFinales.php', function() {
                            var filePreviewContainercarta_beneficiarios_finales = document.getElementById("filePreviewContainercarta_beneficiarios_finales");
                            var filePreviewContentcarta_beneficiarios_finales = document.getElementById("filePreviewContentcarta_beneficiarios_finales");
                            var modifyFileButtoncarta_beneficiarios_finales = document.getElementById("modifyFileButtoncarta_beneficiarios_finales");
                            var file_carta_beneficiarios_finales = document.getElementById("file_carta_beneficiarios_finales");
                            
                            var fileType = cartabeneficiariosFinales[0].documento_laft.split('.').pop().toLowerCase();

                            filePreviewContentcarta_beneficiarios_finales.innerHTML = '';
                            filePreviewContainercarta_beneficiarios_finales.style.display = 'block';
    
                            if (fileType === 'pdf') {
                                var iframe = document.createElement("iframe");
                                iframe.src = cartabeneficiariosFinales[0].documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                iframe.width = "100%";
                                iframe.height = "200px";
                                filePreviewContentcarta_beneficiarios_finales.appendChild(iframe);
                            }

                            file_carta_beneficiarios_finales.style.display = "none";
                            modifyFileButtoncarta_beneficiarios_finales.style.display = "block";
                            $('#carta_beneficiarios_finales').removeAttr("required", "required");
                        });
                    } else {
                        loadPartial('_Partials/_CartaBeneficiariosFinales.php');
                    }
                } else {
                    loadPartial('_Partials/_CartaBeneficiariosFinales.php');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
            }
        });
    }

    function agregarFormularioBeneficiario(beneficiario, index) {
        $.ajax({
            url: '../../Actions/Laft/consultarIdioma.php',
            type: 'POST',
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    contadorBeneficiariosFinales++;
                    if(data.idioma == 'Es'){
                        var formHtml = `
                                <div class="row formRow" id="formRow_${contadorBeneficiariosFinales}">
                                <hr style="margin-top: 50px;">
                                <input type="hidden" name="id_beneficiarios_finales[]" value="${beneficiario.Id_beneficiarios_finales || ''}">
                                <div class="col-md-6 mt-4">
                                    <p>Nombres o Razón Social: *</p>
                                    <input type="text" class="form-control" name="nombre_razon_social_beneficiarios_finales[]" value="${beneficiario.nombre_razon_social_beneficiarios_finales}" required>
                                </div>
                                <div class="col-md-6 mt-4">
                                    <p>Tipo de Identificación: *</p>
                                    <select name="tipo_identificacion_beneficiarios_finales[]" id="tipo_identificacion_beneficiarios_finales_${contadorBeneficiariosFinales}" class="form-select">
                                        <option selected disabled>Seleccionar Opción</option>
                                        <option value="Cedula Ciudadania">Cédula Ciudadanía</option>
                                        <option value="Cedula Extranjeria">Cédula Extranjería</option>
                                        <option value="NIT">NIT</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                    <span class="error-message" style="display:none; color:red">Requerido.</span>
                                </div>
                                <div class="col-md-6 mt-4 otroTipoIdentificacionContainer" id="otroTipoIdentificacionContainer_${contadorBeneficiariosFinales}" style="display:none;">
                                    <p>Which? *</p>
                                    <input type="text" class="form-control otro_tipo_identificacion" name="otro_tipo_identificacion[]" value="${beneficiario.otro_tipo_identificacion}">
                                </div>
                                <div class="col-md-6 mt-4">
                                    <p>Identification Number: *</p>
                                    <input type="text" class="form-control" name="numero_identificacion_beneficiarios_finales[]" value="${beneficiario.numero_identificacion_beneficiarios_finales}" required>
                                </div>
                                <div class="col-md-5 mt-4">
                                    <p>Participation Percentage: *</p>
                                    <input type="text" class="form-control" name="porcentaje_participacion_beneficiarios_finales[]" value="${beneficiario.porcentaje_participacion_beneficiarios_finales.replace('.', ',')}" required>
                                </div>
                                <div class="col-md-1 d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn btn-danger deleteForm"><i class="material-icons">delete</i></button>
                                </div>
                            </div>
                        `;
        
                        $('#formBeneficiariosFinales').append(formHtml);
                
                        var selectElement = document.getElementById('tipo_identificacion_beneficiarios_finales_' + contadorBeneficiariosFinales);
                        selectElement.value = beneficiario.tipo_identificacion_beneficiarios_finales;
                
                        if (beneficiario.tipo_identificacion_beneficiarios_finales === 'Otro') {
                            $('#otroTipoIdentificacionContainer_' + contadorBeneficiariosFinales).show();
                        }
                
                        selectElement.addEventListener('change', function() {
                            manejarCambioTipoIdentificacionJs(this);
                        });
                
                        document.querySelectorAll('.deleteForm').forEach(function(button) {
                            button.addEventListener('click', function() {
                                this.closest('.formRow').remove();
                            });
                });
                    }else{
                        var formHtml = `
                            <div class="row formRow" id="formRow_${contadorBeneficiariosFinales}">
                                <hr style="margin-top: 50px;">
                                <input type="hidden" name="id_beneficiarios_finales[]" value="${beneficiario.Id_beneficiarios_finales || ''}">
                                <div class="col-md-6 mt-4">
                                    <p>Names or Company Name: *</p>
                                    <input type="text" class="form-control" name="nombre_razon_social_beneficiarios_finales[]" value="${beneficiario.nombre_razon_social_beneficiarios_finales}" required>
                                </div>
                                <div class="col-md-6 mt-4">
                                    <p>ID Type: *</p>
                                    <select name="tipo_identificacion_beneficiarios_finales[]" id="tipo_identificacion_beneficiarios_finales_${contadorBeneficiariosFinales}" class="form-select">
                                        <option selected disabled>Select an Option</option>
                                        <option value="Cedula Ciudadania">Citizen ID</option>
                                        <option value="Cedula Extranjeria">Foreigner ID</option>
                                        <option value="NIT">NIT</option>
                                        <option value="Otro">Other</option>
                                    </select>
                                    <span class="error-message" style="display:none; color:red">Requerido.</span>
                                </div>
                                <div class="col-md-6 mt-4 otroTipoIdentificacionContainer" id="otroTipoIdentificacionContainer_${contadorBeneficiariosFinales}" style="display:none;">
                                    <p>Cuál? *</p>
                                    <input type="text" class="form-control otro_tipo_identificacion" name="otro_tipo_identificacion[]" value="${beneficiario.otro_tipo_identificacion}">
                                </div>
                                <div class="col-md-6 mt-4">
                                    <p>Número de Identificación: *</p>
                                    <input type="text" class="form-control" name="numero_identificacion_beneficiarios_finales[]" value="${beneficiario.numero_identificacion_beneficiarios_finales}" required>
                                </div>
                                <div class="col-md-5 mt-4">
                                    <p>Porcentaje de Participación: *</p>
                                    <input type="text" class="form-control" name="porcentaje_participacion_beneficiarios_finales[]" value="${beneficiario.porcentaje_participacion_beneficiarios_finales.replace('.', ',')}" required>
                                </div>
                                <div class="col-md-1 d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn btn-danger deleteForm"><i class="material-icons">delete</i></button>
                                </div>
                            </div>
                        `;
                
                        $('#formBeneficiariosFinales').append(formHtml);
                
                        var selectElement = document.getElementById('tipo_identificacion_beneficiarios_finales_' + contadorBeneficiariosFinales);
                        selectElement.value = beneficiario.tipo_identificacion_beneficiarios_finales;
                
                        if (beneficiario.tipo_identificacion_beneficiarios_finales === 'Otro') {
                            $('#otroTipoIdentificacionContainer_' + contadorBeneficiariosFinales).show();
                        }
                
                        selectElement.addEventListener('change', function() {
                            manejarCambioTipoIdentificacionJs(this);
                        });
                
                        document.querySelectorAll('.deleteForm').forEach(function(button) {
                            button.addEventListener('click', function() {
                                this.closest('.formRow').remove();
                            });
                        });
                    }
                }
            }
        });
    }

    var contadorPEPS = 0;

    function agregarFormularioPep(callback){
        contadorPEPS++;
    
        $.ajax({
            url: '../../Actions/Laft/consultarIdioma.php',
            type: 'POST',
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    var formHtml = `
                        <div class="row formRow" id="formRow_${contadorPEPS}">
                        <hr style="margin-top: 50px;">
                        <div class="col-md-6 mt-4">
                            <p>${data.idioma == 'Es' ? 'Nombre de la persona catalogada como PEP: *' : 'Name of the person classified as PEP: *'}</p>
                            <input type="text" class="form-control" name="nombre_pep[]" required>
                        </div>
                        <div class="col-md-6 mt-4">
                            <p>${data.idioma == 'Es' ? 'Tipo de Identificación: *' : 'ID Type: *'}</p>
                            <select name="tipo_documento_pep[]" id="tipo_documento_pep" class="form-select" required>
                                <option value="" selected disabled>${data.idioma == 'Es' ? 'Seleccione una opción' : 'Select an option'}</option>
                                <option value="Cedula Ciudadania">${data.idioma == 'Es' ? 'Cédula de Ciudadanía' : 'Citizen ID'}</option>
                                <option value="Cedula Extranjeria">${data.idioma == 'Es' ? 'Cédula de Extranjería' : 'Foreigner ID'}</option>
                            </select>
                            <span class="error-message" style="display:none; color:red">Requerido.</span>
                        </div>
                        <div class="col-md-6 mt-4">
                            <p>${data.idioma == 'Es' ? 'Número de Identificación: *' : 'Identification Number: *'}</p>
                            <input type="text" class="form-control" name="numero_identificacion_pep[]" required oninput="validarNumero(this)">
                        </div>
                        <div class="col-md-6 mt-4">
                            <p>${data.idioma == 'Es' ? 'Cargo que ocupa en la compañía: *' : 'Position held in the company: *'}</p>
                            <input type="text" class="form-control" name="cargo_ocupa_pep[]" required>
                        </div>
                        <div class="col-md-12 mt-4">
                            <p>${data.idioma == 'Es' ? '¿Qué cargo ocupa/ocupó que lo cataloga como PEP? *' : 'What position do you hold/did you hold that classifies you as a PEP? *'}</p>
                            <input type="text" class="form-control" name="cargo_ocupa_ocupo_cataloga_pep[]" required>
                        </div>
                        <div class="col-md-6 mt-4">
                            <p>${data.idioma == 'Es' ? '¿Desde cuándo? *' : 'Since when? *'}</p>
                            <input type="date" class="form-control" name="desde_cuando_pep[]" required>
                        </div>
                        <div class="col-md-5 mt-4">
                            <p>${data.idioma == 'Es' ? '¿Hasta cuándo? *' : 'Even when? *'}</p>
                            <input type="date" class="form-control" name="hasta_cuando_pep[]" required>
                        </div>
                        <div class="col-md-1 d-flex align-items-center justify-content-center">
                            <button type="button" class="btn btn-danger deleteForm"><i class="material-icons">delete</i></button>
                        </div>
                        </div>
                    `;
                    
                    
                    $('#formPEP').append(formHtml);
                    $('#formRow_' + contadorPEPS).find('.deleteForm').on('click', function() {
                        $(this).closest('.formRow').remove();
                    });

                    if (callback) callback();
                }
            }
        });
    }

    function manejarCambioTipoIdentificacionJs(select) {
        var formRow = select.closest('.formRow');
        var otroTipoIdentificacionContainer = formRow.querySelector('.otroTipoIdentificacionContainer');
        var otro_tipo_identificacion = formRow.querySelector('.otro_tipo_identificacion');
        if (select.value === 'Otro') {
            otroTipoIdentificacionContainer.style.display = 'block';
        } else {
            otro_tipo_identificacion.value = '';
            otroTipoIdentificacionContainer.style.display = 'none';
        }
    }

    function cargarContactoComercial(){
        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/contactoComercial.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    loadPartial('_Partials/_ContactoComercial.php', function() {
                        Object.keys(data.contactoComercial).forEach(function(key) {
                            var value = data.contactoComercial[key];
                            $('[name="' + key + '"]').val(value);
                        });
                    });
                }else{
                    loadPartial('_Partials/_ContactoComercial.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }
    
    function cargarContactoFinanciero(){
        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/contactoFinanciero.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    loadPartial('_Partials/_ContactoFinanciero.php', function() {
                        Object.keys(data.contactoFinanciero).forEach(function(key) {
                            var value = data.contactoFinanciero[key];
                            $('[name="' + key + '"]').val(value);
                        });
                    });
                }else{
                    loadPartial('_Partials/_ContactoFinanciero.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }

    function cargarContactoOficialCumplimiento(){
        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/contactoOficialCumplimiento.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    
                    loadPartial('_Partials/_ContactoOficialCumplimiento.php', function() {
                        document.getElementById('chk_1').checked = true;
                        document.getElementById('chk_2').checked = false;
                        document.getElementById('oficial_cumplimiento').value = 1;
                        const additionalForm = document.getElementById('formulario_adicional');
                        additionalForm.style.display = 'block';
                        Object.keys(data.oficialCumplimiento).forEach(function(key) {
                            var value = data.oficialCumplimiento[key];
                            $('[name="' + key + '"]').val(value);
                        });
                    });
                }else{
                    
                    loadPartial('_Partials/_ContactoOficialCumplimiento.php', function(){
                        document.getElementById('chk_1').checked = false;
                        document.getElementById('chk_2').checked = true;
                        document.getElementById('oficial_cumplimiento').value = 0;
                        const additionalForm = document.getElementById('formulario_adicional');
                        additionalForm.style.display = 'none';
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }

    function cargarGestionAmbiental(){
        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/gestionAmbiental.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    loadPartial('_Partials/_GestionAmbiental.php', function() {
                        
                        Object.keys(data.gestionAmbiental).forEach(function(key) {
                            var value = data.gestionAmbiental[key];
                            if(key == 'ha_obtenido_sancion' && value=='1'){
                                document.getElementById('ha_obtenido_sancion_chk_1').checked = true;
                                document.getElementById('ha_obtenido_sancion_chk_2').checked = false;
                            }
                            if(key == 'ha_obtenido_sancion' && value=='0'){
                                document.getElementById('ha_obtenido_sancion_chk_1').checked = false;
                                document.getElementById('ha_obtenido_sancion_chk_2').checked = true;
                            }
                            if(key == 'certificado_ISO_14001' && value=='1'){
                                document.getElementById('certificado_ISO_14001_chk_1').checked = true;
                                document.getElementById('certificado_ISO_14001_chk_2').checked = false;
                            }
                            if(key == 'certificado_ISO_14001' && value=='0'){
                                document.getElementById('certificado_ISO_14001_chk_1').checked = false;
                                document.getElementById('certificado_ISO_14001_chk_2').checked = true;
                            }
                            const permisos_cuenta_cont = document.getElementById('permisos_cuenta_cont');
                            const textareaElement = document.getElementById('permisos_cuenta');

                            if(key == 'permiso_uso_recursos_naturales' && value == '1'){
                                permisos_cuenta_cont.style.display = 'block';
                                textareaElement.setAttribute('required', 'required');
                            }
                            if(key == 'permiso_uso_recursos_naturales' && value != '1'){
                                permisos_cuenta_cont.style.display = 'none';
                                textareaElement.removeAttribute('required');
                            }
                            $('[name="' + key + '"]').val(value);
                        });
                    });
                }else{
                    loadPartial('_Partials/_GestionAmbiental.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }

    function cargarSostenibilidad(){
        var isconsulta = true;

        $.ajax({
            url: '../../Actions/Laft/sostenibilidad.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                var data = JSON.parse(response);
                ocultarSpinner();
                if (data.success) {
                    loadPartial('_Partials/_Sostenibilidad.php', function() {
                        Object.keys(data.sostenibilidadAmbiental).forEach(function(key) {
                            var value = data.sostenibilidadAmbiental[key];
                            var element = $('[name="' + key + '"]');

                            if (element.is(':checkbox')) {
                                if (value == 1) {
                                    element.prop('checked', true);
                                } else {
                                    element.prop('checked', false);
                                }
                            } else {
                                element.val(value);
                            }
                        });
                    });
                }else{
                    loadPartial('_Partials/_Sostenibilidad.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }

    function preventDeselection(checkbox, otherCheckbox) {
        checkbox.addEventListener('change', function () {
            if (!checkbox.checked && !otherCheckbox.checked) {
                checkbox.checked = true;
            }
        });
    }

    function cargarDocumentacion(){
        var isconsulta = true;
        $.ajax({
            url: '../../Actions/Laft/documentacion.php',
            type: 'POST',
            data: { isconsulta: isconsulta},
            success: function(response) {
                
                var data = JSON.parse(response);
                ocultarSpinner();
                if(data.success) {
                    loadPartial('_Partials/_Documentacion.php', function() {

                        var existePortafolio = false;
                        
                        for (var i = 0; i < data.documentos.length; i++) {

                            var documento = data.documentos[i];

                            if (documento.tipo_documento_laft == "PDF Portafolio Servicios" && documento.is_url_documento_laft == 0) {
                                existePortafolio = true;
                                var filePreviewContainerpdf_portafolio = document.getElementById("filePreviewContainerpdf_portafolio");
                                var filePreviewContentpdf_portafolio = document.getElementById("filePreviewContentpdf_portafolio");
                                var modifyFileButtonpdf_portafolio = document.getElementById("modifyFileButtonpdf_portafolio");
                                var pdfInput = document.getElementById("pdfInput");

                                document.getElementById('portafolioServicios').value = 1;
                                document.getElementById('tipo_portafolio').value = "PDF";

                                $('#flexSwitchCheckSi').prop('checked', true);
                                $('#flexSwitchCheckNo').prop('checked', false);

                                const additionalOptions = document.getElementById('additionalOptions');
                                additionalOptions.style.display = 'block';

                                $('#checkboxPdf').prop('checked', true);
                                $('#checkboxUrl').prop('checked', false);
                                document.getElementById('checkboxUrl').required = false;
                                document.getElementById('inputUrl').required = false;

                                const checkboxUrl = document.getElementById('checkboxUrl');
                                const checkboxPdf = document.getElementById('checkboxPdf');
                                preventDeselection(checkboxUrl, checkboxPdf);
                                preventDeselection(checkboxPdf, checkboxUrl);
                                
                                var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                filePreviewContentpdf_portafolio.innerHTML = '';
                                filePreviewContainerpdf_portafolio.style.display = 'block';
        
                                if (fileType === 'pdf') {
                                    var iframe = document.createElement("iframe");
                                    iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                    iframe.width = "100%";
                                    iframe.height = "200px";
                                    filePreviewContentpdf_portafolio.appendChild(iframe);
                                }

                                pdfInput.style.display = "none";
                                modifyFileButtonpdf_portafolio.style.display = "block";
                                $('#pdf_portafolio').removeAttr("required", "required");

                            }else if(documento.tipo_documento_laft == "PDF Portafolio Servicios" && documento.is_url_documento_laft == 1){
                                existePortafolio = true;

                                document.getElementById('portafolioServicios').value = 1;
                                document.getElementById('tipo_portafolio').value = "Url";

                                $('#flexSwitchCheckSi').prop('checked', true);
                                $('#flexSwitchCheckNo').prop('checked', false);

                                document.getElementById('checkboxPdf').required = false;
                                document.getElementById('pdf_portafolio').required = false;

                                const additionalOptions = document.getElementById('additionalOptions');
                                additionalOptions.style.display = 'block';

                                $('#checkboxPdf').prop('checked', false);
                                $('#checkboxUrl').prop('checked', true);
                                const urlInput = document.getElementById('urlInput');
                                urlInput.style.display = 'block';
                                document.getElementById('inputUrl').value = documento.documento_laft;

                                const checkboxUrl = document.getElementById('checkboxUrl');
                                const checkboxPdf = document.getElementById('checkboxPdf');
                                preventDeselection(checkboxUrl, checkboxPdf);
                                preventDeselection(checkboxPdf, checkboxUrl);

                            }else if(!existePortafolio){
                                $('#flexSwitchCheckNo').prop('checked', true);
                                $('#flexSwitchCheckSi').prop('checked', false);

                                const additionalOptions = document.getElementById('additionalOptions');
                                if(additionalOptions){
                                    additionalOptions.style.display = 'none';
                                }
                            }

                            if (documento.tipo_documento_laft == "RUT") {

                                var filePreviewContainerRUT = document.getElementById("filePreviewContainerRUT");
                                var filePreviewContentRUT = document.getElementById("filePreviewContentRUT");
                                var modifyFileButtonRUT = document.getElementById("modifyFileButtonRUT");
                                var file_RUT = document.getElementById("file_RUT");
                                
                                var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                filePreviewContentRUT.innerHTML = '';
                                filePreviewContainerRUT.style.display = 'block';
        
                                if (fileType === 'pdf') {
                                    var iframe = document.createElement("iframe");
                                    iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                    iframe.width = "100%";
                                    iframe.height = "200px";
                                    filePreviewContentRUT.appendChild(iframe);
                                }

                                file_RUT.style.display = "none";
                                modifyFileButtonRUT.style.display = "block";
                                $('#RUT').removeAttr("required", "required");
                            }
                            
                            if(documento.tipo_documento_laft == "Cámara de Comercio"){

                                var filePreviewContainercamara_comercio = document.getElementById("filePreviewContainercamara_comercio");
                                var filePreviewContentcamara_comercio = document.getElementById("filePreviewContentcamara_comercio");
                                var modifyFileButtoncamara_comercio = document.getElementById("modifyFileButtoncamara_comercio");
                                var file_camara_comercio = document.getElementById("file_camara_comercio");
                                var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                filePreviewContentcamara_comercio.innerHTML = '';
                                filePreviewContainercamara_comercio.style.display = 'block';
        
                                if (fileType === 'pdf') {
                                    var iframe = document.createElement("iframe");
                                    iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                    iframe.width = "100%";
                                    iframe.height = "200px";
                                    filePreviewContentcamara_comercio.appendChild(iframe);
                                }

                                file_camara_comercio.style.display = "none";
                                modifyFileButtoncamara_comercio.style.display = "block";
                                $('#camara_comercio').removeAttr("required", "required");
                            }
                            
                            if(documento.tipo_documento_laft == "Copia Cédula Rep. Legal"){

                                var filePreviewContainercopia_cedula_representante_legal = document.getElementById("filePreviewContainercopia_cedula_representante_legal");
                                var filePreviewContentcopia_cedula_representante_legal = document.getElementById("filePreviewContentcopia_cedula_representante_legal");
                                var modifyFileButtoncopia_cedula_representante_legal = document.getElementById("modifyFileButtoncopia_cedula_representante_legal");
                                var file_copia_cedula_representante_legal = document.getElementById("file_copia_cedula_representante_legal");
                                var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                filePreviewContentcopia_cedula_representante_legal.innerHTML = '';
                                filePreviewContainercopia_cedula_representante_legal.style.display = 'block';
        
                                if (fileType === 'pdf') {
                                    var iframe = document.createElement("iframe");
                                    iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                    iframe.width = "100%";
                                    iframe.height = "200px";
                                    filePreviewContentcopia_cedula_representante_legal.appendChild(iframe);
                                }

                                file_copia_cedula_representante_legal.style.display = "none";
                                modifyFileButtoncopia_cedula_representante_legal.style.display = "block";
                                $('#copia_cedula_representante_legal').removeAttr("required", "required");
                            }
                            
                            if(documento.tipo_documento_laft == "Certificación Bancaria"){

                                var filePreviewContainercertificacion_bancaria = document.getElementById("filePreviewContainercertificacion_bancaria");
                                var filePreviewContentcertificacion_bancaria = document.getElementById("filePreviewContentcertificacion_bancaria");
                                var modifyFileButtoncertificacion_bancaria = document.getElementById("modifyFileButtoncertificacion_bancaria");
                                var file_certificacion_bancaria = document.getElementById("file_certificacion_bancaria");
                                var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                filePreviewContentcertificacion_bancaria.innerHTML = '';
                                filePreviewContainercertificacion_bancaria.style.display = 'block';
        
                                if (fileType === 'pdf') {
                                    var iframe = document.createElement("iframe");
                                    iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                    iframe.width = "100%";
                                    iframe.height = "200px";
                                    filePreviewContentcertificacion_bancaria.appendChild(iframe);
                                }

                                file_certificacion_bancaria.style.display = "none";
                                modifyFileButtoncertificacion_bancaria.style.display = "block";
                                $('#certificacion_bancaria').removeAttr("required", "required");
                            }

                            if(documento.tipo_documento_laft == "RUB"){

                                var filePreviewContainerRUB = document.getElementById("filePreviewContainerRUB");
                                var filePreviewContentRUB = document.getElementById("filePreviewContentRUB");
                                var modifyFileButtonRUB = document.getElementById("modifyFileButtonRUB");
                                var file_RUB = document.getElementById("file_RUB");
                                var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                filePreviewContentRUB.innerHTML = '';
                                filePreviewContainerRUB.style.display = 'block';
        
                                if (fileType === 'pdf') {
                                    var iframe = document.createElement("iframe");
                                    iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                    iframe.width = "100%";
                                    iframe.height = "200px";
                                    filePreviewContentRUB.appendChild(iframe);
                                }

                                file_RUB.style.display = "none";
                                modifyFileButtonRUB.style.display = "block";
                                $('#RUB').removeAttr("required", "required");
                            }

                            if(documento.tipo_documento_laft == "Certificado de Afiliación"){

                                var filePreviewContainercertificado_afiliacion = document.getElementById("filePreviewContainercertificado_afiliacion");
                                var filePreviewContentcertificado_afiliacion = document.getElementById("filePreviewContentcertificado_afiliacion");
                                var modifyFileButtoncertificado_afiliacion = document.getElementById("modifyFileButtoncertificado_afiliacion");
                                var file_certificado_afiliacion = document.getElementById("file_certificado_afiliacion");
                                var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                filePreviewContentcertificado_afiliacion.innerHTML = '';
                                filePreviewContainercertificado_afiliacion.style.display = 'block';
        
                                if (fileType === 'pdf') {
                                    var iframe = document.createElement("iframe");
                                    iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                    iframe.width = "100%";
                                    iframe.height = "200px";
                                    filePreviewContentcertificado_afiliacion.appendChild(iframe);
                                }

                                file_certificado_afiliacion.style.display = "none";
                                modifyFileButtoncertificado_afiliacion.style.display = "block";
                                $('#certificado_afiliacion').removeAttr("required", "required");
                            }

                            if(documento.tipo_documento_laft == "Carta que Certifica no Tiene Personas a Cargo"){

                                var filePreviewContainercarta_personas_cargo = document.getElementById("filePreviewContainercarta_personas_cargo");
                                var filePreviewContentcarta_personas_cargo = document.getElementById("filePreviewContentcarta_personas_cargo");
                                var modifyFileButtoncarta_personas_cargo = document.getElementById("modifyFileButtoncarta_personas_cargo");
                                var file_carta_personas_cargo = document.getElementById("file_carta_personas_cargo");
                                var fileType = documento.documento_laft.split('.').pop().toLowerCase();

                                filePreviewContentcarta_personas_cargo.innerHTML = '';
                                filePreviewContainercarta_personas_cargo.style.display = 'block';
        
                                if (fileType === 'pdf') {
                                    var iframe = document.createElement("iframe");
                                    iframe.src = documento.documento_laft + "#toolbar=0&navpanes=0&scrollbar=0";
                                    iframe.width = "100%";
                                    iframe.height = "200px";
                                    filePreviewContentcarta_personas_cargo.appendChild(iframe);
                                }

                                file_carta_personas_cargo.style.display = "none";
                                modifyFileButtoncarta_personas_cargo.style.display = "block";
                                $('#carta_personas_cargo').removeAttr("required", "required");
                            }
                        }
                    });
                }else{
                    loadPartial('_Partials/_PersonaJuridica.php');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    }

    $(document).ready(function() {
        $('#addForm').on('click', function() {
            agregarFormularioBeneficiario({}, contadorBeneficiariosFinales);
        });

        $(document).on('change', '[id^=tipo_identificacion_beneficiarios_finales]', function() {
            manejarCambioTipoIdentificacionJs(this);
        });

        $(document).on('click', '.deleteForm', function() {
            $(this).closest('.formRow').remove();
        });
    });

    $(document).on('click', '#then', function() {

        $.ajax({
            url: '../../Actions/Laft/consultarIdioma.php',
            type: 'POST',
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    if(data.idioma == 'Es'){
                        var titleModal = "Cerrando Sesión...";
                        var textModal = "Recuerda que debes diligenciar este formulario para acceder al sistema.";

                    }else{
                        var titleModal = "Closing Session...";
                        var textModal = "Remember that you must fill out this form to access the system.";
                    }

                    Swal.fire({
                        icon: "warning",
                        title: titleModal,
                        text: textModal,
                        showConfirmButton: false,
                        timer: 4500,
                        allowOutsideClick: false
                    });
                    setTimeout(function() { window.location.href = "../../Actions/Generals/cerrarsesion.php"; }, 4000);
                }
            }
        });
    });

    $(document).on('click', '#continue', function() {
        cargarTipoPersona();
    });
    
    $(document).on('click', '#next_tipoPersona', function() {
        mostrarSpinner();
        let tipoPersona = document.getElementById('tipo_persona').value;
        if (tipoPersona === null || tipoPersona === "Select an option") {
            $('.error-message').show();
            ocultarSpinner();
        } else {
            $('.error-message').hide();
            $('#spinner').show();

            $.ajax({
                url: '../../Actions/Laft/tipoPersona.php',
                type: 'POST',
                data: { tipoPersona: tipoPersona },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                            }
                        });
                        if (tipoPersona == "Natural") {
                            $.ajax({
                                url: '../../Actions/Laft/eliminarPorTipoPersona.php',
                                type: 'POST',
                                data: { tipoPersona: tipoPersona },
                                success: function(response) {
                                    
                                    var data = JSON.parse(response);
                                    if (data.success) {
                                        cargarPersonaNatural();
                                    }
                                }
                            });
                        } else if (tipoPersona == "Juridica") {
                            $.ajax({
                                url: '../../Actions/Laft/eliminarPorTipoPersona.php',
                                type: 'POST',
                                data: { tipoPersona: tipoPersona },
                                success: function(response) {
                                    var data = JSON.parse(response);
                                    if (data.success) {
                                        cargarPersonaJuridica();
                                    }
                                }
                            });
                        }
                    }else{
                        if (tipoPersona == "Natural") {
                            loadPartial('_Partials/_PersonaNatural.php');
                        }else if (tipoPersona == "Juridica") {
                            loadPartial('_Partials/_PersonaJuridica.php');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }
    });

    $(document).on('click', '#back_personaNatural', function() {
        mostrarSpinner();
        cargarTipoPersona();
    });

    $(document).on('click', '#next_personaNatural', function() {

        mostrarSpinner();
    
        var form = document.querySelector('#formPersonaNatural');
        
        if (validateForm(form)) {
            var formData = new FormData(form);

            $.ajax({
                url: '../../Actions/Laft/personaNatural.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var enviarCorreo = false;
                    $.ajax({
                        url: '../../Actions/Laft/generarPDFLAFT.php',
                        type: 'POST',
                        data: { enviarCorreo: enviarCorreo },
                        success: function(response) {
                            cargarPepInfoGeneral();
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }
    });

    $(document).on('click', '#next_pepInfoGeneral', function() {

        mostrarSpinner();

        var form = document.querySelector('#formPepInfoGeneral');
        
        if (validateForm(form)) {
            var formData = $('#formPepInfoGeneral').serialize();
            var params = new URLSearchParams(formData);
            $.ajax({
                url: '../../Actions/Laft/pepInfoGeneral.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                            }
                        });
                        var maneja_o_ha_manejado_recursos_publicos = params.get('maneja_o_ha_manejado_recursos_publicos');
                        var tiene_o_ha_tenido_cargo_publico = params.get('tiene_o_ha_tenido_cargo_publico');
                        var ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales = params.get('ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales');
                        var ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia = params.get('ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia');
                        
                        if(maneja_o_ha_manejado_recursos_publicos == "1" ||
                        tiene_o_ha_tenido_cargo_publico == "1" ||
                        ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales == "1" ||
                        ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia == "1"
                        ){
                            cargarPep();
                        }else{
                            cargarOrigenesFondos();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }
    });

    $(document).on('click', '#back_pepInfoGeneral', function() {

        mostrarSpinner();

        $.ajax({
            url: '../../Actions/Laft/consultarTipoPersona.php',
            type: 'POST',
            success: function(response) {
                
                var data = JSON.parse(response);
                if (data.success) {
                    if(data.laft.tipo_persona_laft == 'Juridica'){
                        cargarContactoOficialCumplimiento();
                    }else{
                        cargarPersonaNatural();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    });

    $(document).on('click', '#next_pep', function() {

        mostrarSpinner();
        
        var form = document.querySelector('#formPEP');
        
        if (validateForm(form)) {
            var formData = $('#formPEP').serialize();

            $.ajax({
                url: '../../Actions/Laft/pep.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                            }
                        });
                        cargarOrigenesFondos();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }
    });

    $(document).on('click', '#back_pep', function() {
        mostrarSpinner();
        cargarPepInfoGeneral();
    });

    $(document).on('click', '#back_origenesFondos', function() {

        mostrarSpinner();

        $.ajax({
            url: '../../Actions/Laft/consultarPEPInfoGeneral.php',
            type: 'POST',
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    if(data.returnPEP){
                        cargarPep();
                    }else{
                        cargarPepInfoGeneral();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", error);
            }
        });
    });

    $(document).on('click', '#next_origenesFondos', function() {

        mostrarSpinner();

        var form = document.querySelector('#formOrigenesFondos');
        
        if (validateForm(form)) {
            var formData = $('#formOrigenesFondos').serialize();

            $.ajax({
                url: '../../Actions/Laft/origenesFondos.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                            }
                        });
                        cargarAutorizacionProteccionDatos();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }
    });

    $(document).on('click', '#next_autorizacionProteccionDatos', function() {

        mostrarSpinner();

        var form = document.querySelector('#formAutorizacionProteccionDatos');
        
        if (validateForm(form)) {
            var formData = $('#formAutorizacionProteccionDatos').serialize();

            $.ajax({
                url: '../../Actions/Laft/autorizacionProteccionDatos.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                            }
                        });
                        cargarDeclaracionEtica();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }
    });

    $(document).on('click', '#back_autorizacionProteccionDatos', function() {
        mostrarSpinner();
        cargarOrigenesFondos();
    });

    $(document).on('click', '#next_declaracionEtica', function() {

        mostrarSpinner();

        var form = document.querySelector('#formDeclaracionEtica');
        
        if (validateForm(form)) {
            var formData = $('#formDeclaracionEtica').serialize();

            $.ajax({
                url: '../../Actions/Laft/declaracionEtica.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                            }
                        });
                        cargarDocumentacion();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }
    });

    $(document).on('click', '#back_declaracionEtica', function() {
        mostrarSpinner();
        cargarAutorizacionProteccionDatos();
    });

    $(document).on('click', '#next_personaJuridica', function() {

        mostrarSpinner();

        var form = document.querySelector('#formPersonaJuridica');
        if (validateForm(form)) {
            var formData = new FormData(form);
            $.ajax({
                url: '../../Actions/Laft/personaJuridica.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                            }
                        });
                        cargarRepresentanteLegal();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }
    });

    $(document).on('click', '#back_personaJuridica', function() {
        mostrarSpinner();
        cargarTipoPersona();
    });

    $(document).on('click', '#next_representanteLegal', function() {

        mostrarSpinner();

        var form = document.querySelector('#formRepresentanteLegal');
        
        if (validateForm(form)) {
            var formData = $('#formRepresentanteLegal').serialize();

            $.ajax({
                url: '../../Actions/Laft/representanteLegal.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                            }
                        });
                        cargarSuplenteRepresentanteLegal();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }
    });

    $(document).on('click', '#back_representanteLegal', function() {
        mostrarSpinner();
        cargarPersonaJuridica();
    });
    
    $(document).on('click', '#next_suplenteRepresentanteLegal', function() {
        mostrarSpinner();

        var form = document.querySelector('#formSuplenteRepresentanteLegal');
        var carta_beneficiarios_finales = form.querySelector('input[name="carta_beneficiarios_finales"]').value;
        
        if (validateForm(form)) {
            var formData = $('#formSuplenteRepresentanteLegal').serialize();

            $.ajax({
                url: '../../Actions/Laft/suplenteRepresentanteLegal.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                            }
                        });
                        if(carta_beneficiarios_finales == 1){
                            ocultarSpinner();
                            cargarCartaBeneficiariosFinales();
                        }else{
                            cargarBeneficiariosFinales();
                        }   
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }
    });

    $(document).on('click', '#back_suplenteRepresentanteLegal', function() {
        mostrarSpinner();
        cargarRepresentanteLegal();
    });

    function validarFormulario() {
        var formularios = document.querySelectorAll('#formBeneficiariosFinales .formRow');
        var contadorNITsOtros = 0;
        var contadorCedulas = 0;
    
        formularios.forEach(function(formulario) {
            var tipoIdentificacion = formulario.querySelector('.form-select').value;
    
            if (tipoIdentificacion === 'NIT' || tipoIdentificacion === 'Otro') {
                contadorNITsOtros++;
            } else if (tipoIdentificacion === 'Cedula Ciudadania' || tipoIdentificacion === 'Cedula Extranjeria') {
                contadorCedulas++;
            }
        });

        if (contadorNITsOtros > 0) {
            if (contadorCedulas < contadorNITsOtros) {

                $.ajax({
                    url: '../../Actions/Laft/consultarIdioma.php',
                    type: 'POST',
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            if(data.idioma == 'Es'){
                                var titleModal = "Error de Registro";
                                var textModal = "Para cada beneficiario identificado como NIT u otro tipo de identificación, es necesario registrar al menos una persona natural con cédula como beneficiario final.";
                                var textButton = "Aceptar";
                            }else{
                                var titleModal = "Registry Error";
                                var textModal = "For each beneficiary identified as a NIT or another type of identification, it is necessary to register at least one natural person with an ID as the final beneficiary.";
                                var textButton = "Accept"
                            }

                            Swal.fire({
                                icon: "warning",
                                title: titleModal,
                                text: textModal,
                                confirmButtonText: textButton,
                                confirmButtonColor: '#0093B2'
                            });
                            
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX: ", error);
                    }
                });
                return false;
            }
        }
        return true;
    }

    $(document).on('click', '#next_cartaBeneficiariosFinales', function() {

        mostrarSpinner();

        var form = document.querySelector('#formCartaBeneficiariosFinales');
        
        if (validateForm(form)) {
            var formData = new FormData(form);

            $.ajax({
                url: '../../Actions/Laft/cartaBeneficiariosFinales.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                            }
                        });
                        cargarContactoComercial();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }
    });

    $(document).on('click', '#back_cartaBeneficiariosFinales', function() {
        mostrarSpinner();
        cargarSuplenteRepresentanteLegal();
    });

    $(document).on('click', '#next_beneficiariosFinales', function() {
        mostrarSpinner();

        var form = document.querySelector('#formBeneficiariosFinales');
        
        if (validateForm(form)) {
            var formData = new FormData(form);

            $.ajax({
                url: '../../Actions/Laft/beneficiariosFinales.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                            }
                        });
                        cargarContactoComercial();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }
    });

    $(document).on('click', '#back_beneficiariosFinales', function() {
        mostrarSpinner();
        cargarSuplenteRepresentanteLegal();
    });

    $(document).on('click', '#next_contactoComercial', function() {

        mostrarSpinner();

        var form = document.querySelector('#formContactoComercial');
        
        if (validateForm(form)) {
            
            var formData = $('#formContactoComercial').serialize();

            $.ajax({
                url: '../../Actions/Laft/contactoComercial.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                                
                                
                            }
                        });
                        cargarContactoFinanciero();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }
    });

    $(document).on('click', '#back_contactoComercial', function() {
        mostrarSpinner();
        var form = document.querySelector('#formContactoComercial');
        var carta_beneficiarios_finales = form.querySelector('input[name="carta_beneficiarios_finales"]').value;
        if(carta_beneficiarios_finales == 1){
            ocultarSpinner();
            cargarCartaBeneficiariosFinales();
        }else{
            cargarBeneficiariosFinales();
        }
    });

    $(document).on('click', '#next_contactoFinanciero', function() {

        mostrarSpinner();

        var form = document.querySelector('#formContactoFinanciero');
        
        if (validateForm(form)) {
        var formData = $('#formContactoFinanciero').serialize();
            $.ajax({
                url: '../../Actions/Laft/contactoFinanciero.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                            }
                        });
                        cargarContactoOficialCumplimiento();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }

    });
    
    $(document).on('click', '#back_contactoFinanciero', function() {
        mostrarSpinner();
        cargarContactoComercial();
    });

    $(document).on('click', '#next_contactoOficialCumplimiento', function() {

        mostrarSpinner();

        var form = document.querySelector('#formContactoOficialCumplimiento');
        
        if (validateForm(form)) {

            var formData = $('#formContactoOficialCumplimiento').serialize();

            $.ajax({
                url: '../../Actions/Laft/contactoOficialCumplimiento.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var enviarCorreo = false;
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                            }
                        });
                        cargarPepInfoGeneral();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });

        }else{
            ocultarSpinner();
        }
    });
    
    $(document).on('click', '#back_contactoOficialCumplimiento', function() {
        mostrarSpinner();
        cargarContactoFinanciero();
    });

    $(document).on('click', '#next_gestionAmbiental', function() {

        mostrarSpinner();

        var form = document.querySelector('#formGestionAmbiental');
        
        if (validateForm(form)) {

            var formData = $('#formGestionAmbiental').serialize();

            $.ajax({
                url: '../../Actions/Laft/gestionAmbiental.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    
                    var data = JSON.parse(response);
                    if (data.success) {
                        cargarSostenibilidad();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });

        }else{
            ocultarSpinner();
        }
    });
    
    $(document).on('click', '#back_gestionAmbiental', function() {
        mostrarSpinner();
        cargarDocumentacion();
    });

    $(document).on('click', '#next_sostenibilidad', function() {

        mostrarSpinner();

        var form = document.querySelector('#formSostenibilidad');
        
        if (validateForm(form)) {

            var formData = $('#formSostenibilidad').serialize();

            $.ajax({
                url: '../../Actions/Laft/sostenibilidad.php',
                type: 'POST',
                data: formData,
                success: function(response) {

                    enviarCorreo = true;
                    
                    var data = JSON.parse(response);
                    if (data.success) {
                        $.ajax({
                            url: '../../Actions/Laft/generarPDFLAFT.php',
                            type: 'POST',
                            data: { enviarCorreo: enviarCorreo },
                            success: function(response) {
                                enviarCorreo = true;
                                $.ajax({
                                    url: '../../Actions/Laft/generarPDFAmbiental.php',
                                    type: 'POST',
                                    data: { enviarCorreo: enviarCorreo },
                                    success: function(response) {
                                        console.log("Respuesta de generarPDFAmbiental:", response);
                                        $.ajax({
                                            url: '../../Actions/Laft/consultarIdioma.php',
                                            type: 'POST',
                                            success: function(response) {
                                                var data = JSON.parse(response);
                                                if (data.success) {
                                                    
                                                    if(data.idioma == 'Es'){
                                                        var titleModal = "Datos Registrados...";
                                                        var textModal = "Los datos han sido registrados y enviados a revisión, debes estar pendiente a la aprobación.";

                                                    }else{
                                                        var titleModal = "Registered Data...";
                                                        var textModal = "The data has been registered and sent for review, you must wait for approval.";
                                                    }

                                                    Swal.fire({
                                                        icon: "success",
                                                        title: titleModal,
                                                        text: textModal,
                                                        showConfirmButton: false,
                                                        timer: 4500,
                                                        allowOutsideClick: false
                                                    });
                                                    setTimeout(function() { window.location.href = "../../Actions/Generals/cerrarsesion.php"; }, 4000);
                                                }
                                            }
                                        });
                                        
                                        
                                    },
                                    error: function(xhr, status, error) {
                                        console.error("Error al generar el PDF:", status, error);
                                    }
                                });
                                
                            },
                            error: function(xhr, status, error) {
                                console.error("Error al generar el PDF:", status, error);
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });

        }else{
            ocultarSpinner();
        }
    });
    
    $(document).on('click', '#back_sostenibilidad', function() {
        mostrarSpinner();
        cargarGestionAmbiental();
    });

    $(document).on('click', '#next_documentacion', function() {

        mostrarSpinner();

        var form = document.querySelector('#formDocumentacion');
        
        if (validateForm(form)) {
            var formData = new FormData(form);

            var enviarCorreo = true;
            
            $.ajax({
                url: '../../Actions/Laft/documentacion.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    var data = JSON.parse(response);
                    if (data.success) {
                        if(data.formulario_ambiental == 0){
                            $.ajax({
                                url: '../../Actions/Laft/generarPDFLAFT.php',
                                type: 'POST',
                                data: { enviarCorreo: enviarCorreo },
                                success: function(response) {
                                    ocultarSpinner();
                                    $.ajax({
                                        url: '../../Actions/Laft/consultarIdioma.php',
                                        type: 'POST',
                                        success: function(response) {
                                            var data = JSON.parse(response);
                                            if (data.success) {
                                                if(data.idioma == 'Es'){
                                                    var titleModal = "Datos Registrados...";
                                                    var textModal = "Los datos han sido registrados y enviados a revisión, debes estar pendiente a la aprobación.";

                                                }else{
                                                    var titleModal = "Registered Data...";
                                                    var textModal = "The data has been registered and sent for review, you must wait for approval.";
                                                }

                                                Swal.fire({
                                                    icon: "success",
                                                    title: titleModal,
                                                    text: textModal,
                                                    showConfirmButton: false,
                                                    timer: 4500,
                                                    allowOutsideClick: false
                                                });
                                                setTimeout(function() { window.location.href = "../../Actions/Generals/cerrarsesion.php"; }, 4000);
                                            }
                                        }
                                    });
                                },
                                error: function(xhr, status, error) {
                                    console.error("Error al generar el PDF:", status, error);
                                }
                            });
                        }else{
                            cargarGestionAmbiental();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        }else{
            ocultarSpinner();
        }
    });

    $(document).on('click', '#back_documentacion', function() {
        mostrarSpinner();
        cargarDeclaracionEtica();
    });
});
