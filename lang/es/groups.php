<?php

return [
    // Títulos principales
    'title' => 'Grupos',
    'my_groups' => 'Mis Grupos',
    'all_groups' => 'Todos los Grupos',
    'create_group' => 'Crear Grupo',
    'edit_group' => 'Editar Grupo',
    'group_details' => 'Detalles del Grupo',
    'group_members' => 'Miembros del Grupo',
    'group_invitations' => 'Invitaciones del Grupo',
    'group_join_requests' => 'Solicitudes de Participación',

    // Acciones
    'create' => 'Crear Grupo',
    'edit' => 'Editar',
    'delete' => 'Eliminar',
    'join' => 'Unirse',
    'leave' => 'Salir',
    'invite' => 'Invitar',
    'accept' => 'Aceptar',
    'decline' => 'Rechazar',
    'promote' => 'Promover',
    'demote' => 'Degradar',
    'remove' => 'Eliminar',
    'send_request' => 'Enviar Solicitud',
    'cancel_request' => 'Cancelar Solicitud',

    // Campos del grupo
    'name' => 'Nombre del Grupo',
    'description' => 'Descripción',
    'image' => 'Imagen del Grupo',
    'visibility' => 'Visibilidad',
    'visibility_public' => 'Público',
    'visibility_private' => 'Privado',
    'created_by' => 'Creado por',
    'created_at' => 'Creado el',
    'members_count' => 'Número de Miembros',
    'role' => 'Rol',
    'joined_at' => 'Miembro desde',

    // Roles
    'role_admin' => 'Administrador',
    'role_moderator' => 'Moderador',
    'role_member' => 'Miembro',

    // Mensajes
    'group_created' => '¡Grupo creado exitosamente!',
    'group_updated' => '¡Grupo actualizado exitosamente!',
    'group_deleted' => '¡Grupo eliminado exitosamente!',
    'joined_group' => '¡Te has unido al grupo exitosamente!',
    'left_group' => 'Has salido del grupo.',
    'member_removed' => 'Miembro eliminado del grupo.',
    'member_promoted' => 'Miembro promovido exitosamente.',
    'member_demoted' => 'Miembro degradado exitosamente.',
    'invitation_sent' => '¡Invitación enviada exitosamente!',
    'invitation_accepted' => '¡Invitación aceptada exitosamente!',
    'invitation_declined' => 'Invitación rechazada.',
    'request_sent' => '¡Solicitud de participación enviada!',
    'request_accepted' => '¡Solicitud de participación aceptada!',
    'request_declined' => 'Solicitud de participación rechazada.',

    // Mensajes de error
    'not_found' => 'Grupo no encontrado.',
    'access_denied' => 'No tienes permisos para acceder a este grupo.',
    'already_member' => 'Ya eres miembro de este grupo.',
    'not_member' => 'No eres miembro de este grupo.',
    'invitation_exists' => 'Ya tienes una invitación pendiente para este grupo.',
    'request_exists' => 'Ya tienes una solicitud pendiente para este grupo.',
    'cannot_leave_admin' => 'No puedes salir de un grupo del que eres administrador. Promueve a otro miembro primero.',
    'cannot_remove_admin' => 'No puedes eliminar a un administrador. Degrádalo primero.',
    'cannot_promote_admin' => 'Este usuario ya es administrador.',
    'cannot_demote_member' => 'Este usuario ya es un miembro regular.',

    // Filtros y búsqueda
    'search_placeholder' => 'Buscar grupos...',
    'filter_all' => 'Todos los Grupos',
    'filter_my_groups' => 'Mis Grupos',
    'filter_public' => 'Grupos Públicos',
    'filter_private' => 'Grupos Privados',
    'filter_admin' => 'Grupos que Administro',

    // Etiquetas de formulario
    'group_name_placeholder' => 'Ingresa el nombre del grupo',
    'group_description_placeholder' => 'Describe tu grupo...',
    'invitation_message_placeholder' => 'Mensaje de invitación (opcional)',
    'join_request_message_placeholder' => 'Mensaje de solicitud (opcional)',

    // Estadísticas
    'stats' => 'Estadísticas',
    'total_members' => 'Total de Miembros',
    'admins_count' => 'Administradores',
    'moderators_count' => 'Moderadores',
    'members_count_label' => 'Miembros',
    'pending_invitations' => 'Invitaciones Pendientes',
    'pending_requests' => 'Solicitudes Pendientes',

    // Eventos del grupo
    'group_events' => 'Eventos del Grupo',
    'no_group_events' => 'No hay eventos asociados a este grupo.',
    'create_group_event' => 'Crear Evento del Grupo',

    // Permisos de eventos
    'event_permissions' => 'Permisos de Eventos',
    'creator_only' => 'Solo Creador',
    'group_admins' => 'Administradores del Grupo',
    'group_members' => 'Miembros del Grupo',

    // Paginación
    'showing' => 'Mostrando',
    'to' => 'a',
    'of' => 'de',
    'results' => 'resultados',

    // Acciones rápidas
    'quick_actions' => 'Acciones Rápidas',
    'view_members' => 'Ver Miembros',
    'manage_invitations' => 'Gestionar Invitaciones',
    'manage_requests' => 'Gestionar Solicitudes',
    'group_settings' => 'Configuración del Grupo',

    // Confirmaciones
    'confirm_delete' => '¿Estás seguro de que quieres eliminar este grupo?',
    'confirm_leave' => '¿Estás seguro de que quieres salir de este grupo?',
    'confirm_remove_member' => '¿Estás seguro de que quieres eliminar a este miembro?',
    'confirm_decline_invitation' => '¿Estás seguro de que quieres rechazar esta invitación?',
    'confirm_cancel_request' => '¿Estás seguro de que quieres cancelar esta solicitud?',

    // Estados
    'status_pending' => 'Pendiente',
    'status_accepted' => 'Aceptado',
    'status_declined' => 'Rechazado',
    'status_expired' => 'Expirado',

    // Información adicional
    'group_info' => 'Información del Grupo',
    'member_since' => 'Miembro desde',
    'invited_by' => 'Invitado por',
    'processed_by' => 'Procesado por',
    'expires_at' => 'Expira el',
    'processed_at' => 'Procesado el',

    // Estados vacíos
    'no_groups' => 'No se encontraron grupos.',
    'no_members' => 'No hay miembros en este grupo.',
    'no_invitations' => 'No hay invitaciones pendientes.',
    'no_requests' => 'No hay solicitudes pendientes.',
    'no_my_groups' => 'Aún no has creado ningún grupo.',
    'no_joined_groups' => 'Aún no eres miembro de ningún grupo.',

    // Consejos
    'tips' => [
        'create_group' => 'Crea un grupo para organizar eventos y colaborar con otros poetas y organizadores.',
        'invite_members' => 'Invita a otros usuarios para hacer crecer tu comunidad.',
        'manage_permissions' => 'Gestiona los permisos para mantener el control de tu grupo.',
        'group_events' => 'Asocia los eventos al grupo para una mejor organización.',
        'public_visibility' => 'Cualquiera puede ver y solicitar unirse al grupo.',
        'private_visibility' => 'Solo los usuarios invitados pueden unirse al grupo.',
    ],

    // Mensajes adicionales
    'delete_warning' => 'Esta acción eliminará permanentemente el grupo y todos sus datos.',
    'delete_confirmation_text' => '¿Estás seguro de que quieres eliminar este grupo? Esta acción no se puede deshacer.',
    'delete_confirmation_members' => 'Todos los miembros serán removidos del grupo',
    'delete_confirmation_events' => 'Los eventos asociados al grupo serán desasociados',
    'delete_confirmation_invitations' => 'Todas las invitaciones y solicitudes pendientes serán eliminadas',
    'invite_first_member' => 'Invita al primer miembro para comenzar a construir tu comunidad.',
    'you' => 'Tú',
]; 
