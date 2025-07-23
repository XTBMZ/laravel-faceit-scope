<?php
return [
    'title' => 'Contato - Faceit Scope',
    'hero' => [
        'title' => 'Contato',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Desenvolvedor',
            'name_label' => 'Nome',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Resposta',
            'average_delay' => 'Atraso médio',
            'delay_value' => '24h',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Tipo de mensagem',
            'required' => '*',
            'placeholder' => 'Selecione um tipo',
            'options' => [
                'bug' => 'Reportar um bug',
                'suggestion' => 'Sugestão',
                'question' => 'Pergunta',
                'feedback' => 'Feedback',
                'other' => 'Outro',
            ],
        ],
        'subject' => [
            'label' => 'Assunto',
            'required' => '*',
        ],
        'email' => [
            'label' => 'E-mail',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Usuário do Faceit',
            'optional' => '(opcional)',
        ],
        'message' => [
            'label' => 'Mensagem',
            'required' => '*',
            'character_count' => 'caracteres',
        ],
        'submit' => [
            'send' => 'Enviar',
            'sending' => 'Enviando...',
        ],
        'privacy_note' => 'Seus dados são usados apenas para processar sua solicitação',
    ],
    'messages' => [
        'success' => [
            'title' => 'Mensagem enviada com sucesso',
            'ticket_id' => 'ID do Ticket:',
        ],
        'error' => [
            'title' => 'Erro de envio',
            'connection' => 'Erro de conexão. Tente novamente.',
            'generic' => 'Ocorreu um erro.',
        ],
    ],
];
