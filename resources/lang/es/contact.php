<?php
return [
    'title' => 'Contacto - Faceit Scope',
    'hero' => [
        'title' => 'Contacto',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Desarrollador',
            'name_label' => 'Nombre',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Respuesta',
            'average_delay' => 'Retraso promedio',
            'delay_value' => '24h',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Tipo de mensaje',
            'required' => '*',
            'placeholder' => 'Selecciona un tipo',
            'options' => [
                'bug' => 'Reportar un bug',
                'suggestion' => 'Sugerencia',
                'question' => 'Pregunta',
                'feedback' => 'Comentario',
                'other' => 'Otro',
            ],
        ],
        'subject' => [
            'label' => 'Asunto',
            'required' => '*',
        ],
        'email' => [
            'label' => 'Email',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Usuario de Faceit',
            'optional' => '(opcional)',
        ],
        'message' => [
            'label' => 'Mensaje',
            'required' => '*',
            'character_count' => 'caracteres',
        ],
        'submit' => [
            'send' => 'Enviar',
            'sending' => 'Enviando...',
        ],
        'privacy_note' => 'Tus datos se usan solo para procesar tu solicitud',
    ],
    'messages' => [
        'success' => [
            'title' => 'Mensaje enviado con éxito',
            'ticket_id' => 'ID de Ticket:',
        ],
        'error' => [
            'title' => 'Error de envío',
            'connection' => 'Error de conexión. Por favor inténtalo de nuevo.',
            'generic' => 'Ocurrió un error.',
        ],
    ],
];
