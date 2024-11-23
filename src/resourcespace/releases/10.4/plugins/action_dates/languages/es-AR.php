<?php


$lang["action_dates_configuration"]='Seleccione los campos que se utilizarán para realizar automáticamente las acciones especificadas.';
$lang["action_dates_deletesettings"]='Configuraciones automáticas de acción principal de recursos - usar con precaución';
$lang["action_dates_delete"]='Eliminar automáticamente o cambiar el estado de los recursos cuando se alcanza la fecha en este campo';
$lang["action_dates_eligible_states"]='Estados elegibles para la acción automática primaria. Si no se selecciona ningún estado, entonces todos los estados son elegibles.';
$lang["action_dates_restrict"]='Restringir automáticamente el acceso a los recursos cuando se alcanza la fecha en este campo. Esto solo se aplica a los recursos cuyo acceso está actualmente abierto.';
$lang["action_dates_delete_logtext"]='Automáticamente accionado por el plugin de fechas de acción';
$lang["action_dates_restrict_logtext"]='Restringido automáticamente por el plugin de fechas de acción';
$lang["action_dates_reallydelete"]='¿Eliminar completamente el recurso cuando la fecha de acción haya pasado? Si se establece en falso, los recursos se moverán al estado de eliminación de recursos configurado y, por lo tanto, serán recuperables';
$lang["action_dates_email_admin_days"]='Notificar a los administradores del sistema un número determinado de días antes de que se alcance esta fecha. Deje esta opción en blanco para no enviar ninguna notificación.';
$lang["action_dates_email_text_restrict"]='Los siguientes recursos están programados para restringirse en [days] días.';
$lang["action_dates_email_text_state"]='Los siguientes recursos están programados para cambiar de estado en [days] días.';
$lang["action_dates_email_text"]='Los siguientes recursos están programados para ser restringidos y/o cambiar de estado en [days] días.';
$lang["action_dates_email_range_restrict"]='Los siguientes recursos están programados para restringirse en un plazo de [days_min] a [days] días.';
$lang["action_dates_email_range_state"]='Los siguientes recursos están programados para cambiar de estado en un plazo de [days_min] a [days] días.';
$lang["action_dates_email_range"]='Los siguientes recursos están programados para restringirse y/o cambiar de estado en un plazo de [days_min] a [days] días.';
$lang["action_dates_email_subject_restrict"]='Notificación de recursos que están por ser restringidos';
$lang["action_dates_email_subject_state"]='Notificación de recursos que deben cambiar de estado';
$lang["action_dates_email_subject"]='Notificación de recursos que están por ser restringidos y/o cambiar de estado';
$lang["action_dates_new_state"]='Nuevo estado al que mover (si la opción anterior está configurada para eliminar completamente los recursos, esto se ignorará)';
$lang["action_dates_notification_subject"]='Notificación del plugin de fechas de acción';
$lang["action_dates_additional_settings"]='Acciones adicionales';
$lang["action_dates_additional_settings_info"]='Además, mover los recursos al estado seleccionado cuando se alcance el campo especificado';
$lang["action_dates_additional_settings_date"]='Cuando se alcanza esta fecha';
$lang["action_dates_additional_settings_status"]='Mover recursos a este estado de archivo';
$lang["action_dates_remove_from_collection"]='¿Eliminar recursos de todas las colecciones asociadas cuando se cambia el estado?';
$lang["action_dates_email_for_state"]='Enviar notificación para cambios de estado de recursos. Requiere configurar los campos de cambio de estado mencionados anteriormente.';
$lang["action_dates_email_for_restrict"]='Enviar notificación para restringir recursos. Requiere que los campos de restricción de recursos arriba hayan sido configurados.';
$lang["action_dates_workflow_actions"]='Si el complemento de Flujo de Trabajo Avanzado está habilitado, ¿deberían aplicarse sus notificaciones a los cambios de estado iniciados por este complemento?';
$lang["action_dates_weekdays"]='Seleccionar los días de la semana en los que se procesarán las acciones.';
$lang["weekday-0"]='Domingo';
$lang["weekday-1"]='Lunes';
$lang["weekday-2"]='Martes';
$lang["weekday-3"]='Miércoles';
$lang["weekday-4"]='Jueves';
$lang["weekday-5"]='Viernes';
$lang["weekday-6"]='Sábado';
$lang["show_affected_resources"]='Mostrar recursos afectados';
$lang["group_no"]='Grupo';
$lang["plugin-action_dates-title"]='Fechas de Acción';
$lang["plugin-action_dates-desc"]='Habilita la eliminación o restricción programada de recursos basada en campos de fecha';