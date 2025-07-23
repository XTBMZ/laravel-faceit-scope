<?php
return [
    'title' => 'Acerca de - Faceit Scope',
    'hero' => [
        'title' => 'Acerca de',
        'subtitle' => 'Faceit Scope analiza tu rendimiento en FACEIT utilizando algoritmos avanzados e inteligencia artificial. Un proyecto solo desarrollado con pasión.',
    ],
    'project' => [
        'title' => 'El proyecto',
        'description_1' => 'permite un análisis en profundidad del rendimiento.',
        'description_2' => 'Desarrollado completamente por',
        'description_3' => 'este proyecto utiliza exclusivamente la API oficial de FACEIT para recuperar todos los datos de forma transparente y legal.',
        'description_4' => 'Todo proviene directamente de los servidores de FACEIT y es analizado por nuestros algoritmos propietarios.',
        'stats' => [
            'developer' => 'Desarrollador',
            'api' => 'API de FACEIT',
        ],
    ],
    'how_it_works' => [
        'title' => 'Cómo funciona',
        'subtitle' => 'Algoritmos sofisticados analizan tus datos de FACEIT para darte perspectivas precisas',
        'pis' => [
            'title' => 'Puntuación de Impacto del Jugador (PIS)',
            'combat' => [
                'title' => 'Combate (35%)',
                'description' => 'K/D, ADR y tasa de headshots con normalización logarítmica',
            ],
            'game_sense' => [
                'title' => 'Sentido del Juego (25%)',
                'description' => 'Habilidades de entrada, clutch y francotirador con combinaciones avanzadas',
            ],
            'utility' => [
                'title' => 'Utilidad (15%)',
                'description' => 'Soporte y uso de utilidades con eficiencia ponderada',
            ],
            'consistency' => [
                'title' => 'Consistencia + Exp (25%)',
                'description' => 'Tasa de victorias, rachas y fiabilidad de datos',
            ],
            'level_coefficient' => [
                'title' => 'Coeficiente de nivel crucial:',
                'description' => 'Un Nivel 10 con 1.0 K/D será valorado más alto que un Nivel 2 con 1.5 K/D porque juega contra oponentes más fuertes.',
            ],
        ],
        'roles' => [
            'title' => 'Asignación inteligente de roles',
            'calculations_title' => 'Cálculos de puntuación de roles',
            'priority_title' => 'Prioridad de asignación',
            'entry_fragger' => [
                'title' => 'Entry Fragger',
                'criteria' => 'Criterios específicos: Tasa de Entrada > 25% Y Éxito de Entrada > 55%',
            ],
            'support' => [
                'title' => 'Soporte',
                'criteria' => 'Criterios específicos: Flashes > 0.4/ronda Y Éxito de Flash > 50%',
            ],
            'awper' => [
                'title' => 'AWPer',
                'criteria' => 'Criterios específicos: Tasa de Francotirador > 15%',
            ],
            'priority_items' => [
                'awper' => 'AWPer (si francotirador > 15%)',
                'entry' => 'Entrada (si entrada > 25% + éxito > 55%)',
                'support' => 'Soporte (si flashes > 0.4 + éxito > 50%)',
                'clutcher' => 'Clutcher (si 1v1 > 40%)',
                'fragger' => 'Fragger (si K/D > 1.3 + ADR > 85)',
                'lurker' => 'Lurker (por defecto si no hay criterios)',
            ],
        ],
        'maps' => [
            'title' => 'Algoritmo de análisis de mapas',
            'normalization' => [
                'title' => 'Normalización logarítmica',
            ],
            'weighting' => [
                'title' => 'Ponderación avanzada',
                'win_rate' => 'Tasa de Victorias:',
                'consistency' => 'Consistencia:',
            ],
            'reliability' => [
                'title' => 'Factor de fiabilidad',
            ],
        ],
        'predictions' => [
            'title' => 'Predicciones de partidas',
            'team_strength' => [
                'title' => 'Fuerza del equipo',
                'average_score' => [
                    'title' => 'Puntuación promedio ponderada',
                    'description' => 'Promedio de 5 puntuaciones PIS + bonus de equilibrio de roles',
                ],
                'role_balance' => [
                    'title' => 'Equilibrio de roles',
                    'description' => 'Un equipo con Entrada + Soporte + AWPer + Clutcher + Fragger tendrá un bonus significativo versus 5 fraggers.',
                ],
            ],
            'probability' => [
                'title' => 'Cálculos de probabilidad',
                'match_winner' => [
                    'title' => 'Ganador de la partida',
                    'description' => 'Cuanto mayor sea la diferencia de fuerza, más confiada será la predicción',
                ],
                'predicted_mvp' => [
                    'title' => 'MVP predicho',
                    'description' => 'El jugador con la',
                    'description_end' => 'entre los 10 participantes',
                    'highest_score' => 'puntuación PIS más alta',
                ],
                'confidence' => [
                    'title' => 'Nivel de confianza',
                    'description' => 'Basado en la diferencia de fuerza: Muy Alto (>3), Alto (>2), Moderado (>1), Bajo (<1)',
                ],
            ],
        ],
    ],
    'contact' => [
        'title' => 'Contacto',
        'subtitle' => 'Un proyecto solo desarrollado con pasión. No dudes en contactarme para comentarios o sugerencias.',
    ],
    'disclaimer' => [
        'text' => 'Faceit Scope no está afiliado con FACEIT Ltd. Este proyecto utiliza la API pública de FACEIT en cumplimiento con sus términos de servicio. Los algoritmos de predicción se basan en análisis estadísticos y no garantizan los resultados de las partidas.',
    ],
];
