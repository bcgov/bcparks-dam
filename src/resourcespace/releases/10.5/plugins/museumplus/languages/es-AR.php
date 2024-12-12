<?php


$lang["museumplus_configuration"]='Configuración de MuseumPlus';
$lang["museumplus_top_menu_title"]='MuseumPlus: asociaciones inválidas';
$lang["museumplus_api_settings_header"]='Detalles de la API';
$lang["museumplus_host"]='Anfitrión';
$lang["museumplus_host_api"]='API Host (solo para llamadas de API; generalmente es lo mismo que arriba)';
$lang["museumplus_application"]='Nombre de la aplicación';
$lang["user"]='Usuario';
$lang["museumplus_api_user"]='Usuario';
$lang["password"]='Contraseña';
$lang["museumplus_api_pass"]='Contraseña';
$lang["museumplus_RS_settings_header"]='Configuraciones de ResourceSpace';
$lang["museumplus_mpid_field"]='Campo de metadatos utilizado para almacenar el identificador de MuseumPlus (MpID)';
$lang["museumplus_module_name_field"]='Campo de metadatos utilizado para almacenar el nombre de los módulos para los cuales el MpID es válido. Si no se establece, el complemento utilizará la configuración del módulo "Objeto" por defecto.';
$lang["museumplus_secondary_links_field"]='Campo de metadatos utilizado para contener los enlaces secundarios a otros módulos. ResourceSpace generará una URL de MuseumPlus para cada uno de los enlaces. Los enlaces tendrán un formato de sintaxis especial: nombre_del_módulo:ID (por ejemplo, "Objeto:1234")';
$lang["museumplus_object_details_title"]='Detalles de MuseumPlus';
$lang["museumplus_script_header"]='Configuración del script';
$lang["museumplus_last_run_date"]='Última ejecución del script';
$lang["museumplus_enable_script"]='Habilitar el script de MuseumPlus';
$lang["museumplus_interval_run"]='Ejecutar script en el siguiente intervalo (por ejemplo, +1 día, +2 semanas, quincenal). Dejar en blanco y se ejecutará cada vez que cron_copy_hitcount.php se ejecute';
$lang["museumplus_log_directory"]='Directorio para almacenar los registros de scripts. Si se deja en blanco o es inválido, no se realizará ningún registro.';
$lang["museumplus_integrity_check_field"]='Campo de verificación de integridad';
$lang["museumplus_modules_configuration_header"]='Configuración de módulos';
$lang["museumplus_module"]='Módulo';
$lang["museumplus_add_new_module"]='Agregar nuevo módulo de MuseumPlus';
$lang["museumplus_mplus_field_name"]='Nombre del campo de MuseumPlus';
$lang["museumplus_rs_field"]='Campo de ResourceSpace';
$lang["museumplus_view_in_museumplus"]='Ver en MuseumPlus';
$lang["museumplus_confirm_delete_module_config"]='¿Está seguro de que desea eliminar esta configuración de módulo? ¡Esta acción no se puede deshacer!';
$lang["museumplus_module_setup"]='Configuración del módulo';
$lang["museumplus_module_name"]='Nombre del módulo de MuseumPlus';
$lang["museumplus_mplus_id_field"]='Nombre del campo de identificación de MuseumPlus';
$lang["museumplus_mplus_id_field_helptxt"]='Deje vacío para usar el ID técnico \'__id\' (predeterminado)';
$lang["museumplus_rs_uid_field"]='Campo UID de ResourceSpace';
$lang["museumplus_applicable_resource_types"]='Tipo(s) de recurso(s) aplicable(s)';
$lang["museumplus_field_mappings"]='Mapeo de campos de MuseumPlus a ResourceSpace';
$lang["museumplus_add_mapping"]='Agregar mapeo';
$lang["museumplus_error_bad_conn_data"]='Datos de conexión de MuseumPlus inválidos';
$lang["museumplus_error_unexpected_response"]='Respuesta inesperada del código de MuseumPlus recibida - %code';
$lang["museumplus_error_no_data_found"]='No se encontraron datos en MuseumPlus para este MpID - %mpid';
$lang["museumplus_warning_script_not_completed"]='ADVERTENCIA: El script de MuseumPlus no se ha completado desde \'%script_last_ran\'.
Solo puede ignorar esta advertencia de manera segura si posteriormente recibió una notificación de finalización exitosa del script.';
$lang["museumplus_error_script_failed"]='El script de MuseumPlus no se pudo ejecutar porque había un bloqueo de proceso en su lugar. Esto indica que la ejecución anterior no se completó
Si necesita eliminar el bloqueo después de una ejecución fallida, ejecute el script de la siguiente manera:
php museumplus_script.php --clear-lock';
$lang["museumplus_php_utility_not_found"]='¡La opción de configuración $php_path DEBE establecerse para que la funcionalidad de cron se ejecute correctamente!';
$lang["museumplus_error_not_deleted_module_conf"]='No se puede eliminar la configuración del módulo solicitado.';
$lang["museumplus_error_unknown_type_saved_config"]='¡El \'museumplus_modules_saved_config\' es de un tipo desconocido!';
$lang["museumplus_error_invalid_association"]='Asociación de módulo(s) inválida. ¡Por favor asegúrese de que se hayan ingresado el módulo y/o el ID de registro correctos!';
$lang["museumplus_id_returns_multiple_records"]='Se encontraron múltiples registros - por favor ingrese el ID técnico en su lugar';
$lang["museumplus_error_module_no_field_maps"]='No se puede sincronizar los datos de MuseumPlus. Motivo: el módulo \'%name\' no tiene mapeos de campos configurados.';
$lang["plugin-museumplus-title"]='MuseumPlus';
$lang["plugin-museumplus-desc"]='[Avanzado] Permite extraer metadatos de recursos desde MuseumPlus utilizando su API REST (MpRIA).';