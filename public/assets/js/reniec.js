$(document).ready(function(){
    console.log('Reniec.js loaded'); // Debug log

    $('#btnBuscar').click(function(){
        console.log('Search button clicked'); // Debug log

        var numdni = $('#dni_search').val();
        console.log('DNI entered:', numdni); // Debug log

        // Validate DNI length
        if (numdni.length !== 8) {
            $('#mensaje_busqueda').text('El DNI debe tener 8 dígitos').css('color', 'red');
            return;
        }

        var link_consulta = "https://dniruc.apisperu.com/api/v1/dni/" + numdni +"?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImFuZ2VsLnlhcmFuZzRAZ21haWwuY29tIn0.QjoAby91BOkxcIkiKEB2-KdFvlNMldOgAlDiLtF_TYA";
        console.log('Making request to:', link_consulta); // Debug log

        $.ajax({
            url : link_consulta,
            success:function(data){
                console.log('API Response:', data); // Debug log
                $('#btnBuscar').val('Buscar');

                if(data && data.success && data.nombres && (data.apellidoPaterno || data.apellidoMaterno)){
                    // Fill the form fields
                    console.log('Filling nombres:', data.nombres); // Debug log
                    $('#nombres').val(data.nombres);

                    // Combine apellidoPaterno and apellidoMaterno
                    let apellidos = '';
                    if (data.apellidoPaterno) {
                        apellidos += data.apellidoPaterno;
                    }
                    if (data.apellidoMaterno) {
                        apellidos += (apellidos ? ' ' : '') + data.apellidoMaterno;
                    }
                    console.log('Filling apellidos:', apellidos); // Debug log
                    $('#apellidos').val(apellidos);

                    $('#mensaje_busqueda').text('Datos encontrados').css('color', 'green');

                    // Trigger username generation if this is the create form
                    if ($('#username').length) {
                        console.log('Triggering username generation'); // Debug log
                        $('#nombres, #apellidos').trigger('input');
                    }

                } else if(data && data.success === false) {
                    console.log('DNI not found in RENIEC'); // Debug log
                    $('#nombres').val('');
                    $('#apellidos').val('');
                    $('#mensaje_busqueda').text('DNI no encontrado en RENIEC').css('color', 'red');

                } else {
                    console.log('Invalid response format:', data); // Debug log
                    $('#nombres').val('');
                    $('#apellidos').val('');
                    $('#mensaje_busqueda').text('No se encontraron datos para este DNI').css('color', 'red');
                }

            },
            error : function(xhr, status, error) {
                console.log('Error:', xhr, status, error);
                $('#mensaje_busqueda').text('Error al consultar DNI. Verifique su conexión.').css('color', 'red');
                $('#btnBuscar').val('Buscar');
            },
            beforeSend: function( ) {
                $('#btnBuscar').val('Buscando...');
                $('#mensaje_busqueda').text('Consultando...').css('color', 'blue');
            }

        });

    });

    // Allow search on Enter key press
    $('#dni_search').keypress(function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            $('#btnBuscar').click();
        }
    });

});
