<?php
return [
    'title' => 'Comparación de Jugadores - Faceit Scope',
    'hero' => [
        'title' => 'Comparación de Jugadores',
        'subtitle' => 'Compara el rendimiento de dos jugadores de CS2',
    ],
    'search' => [
        'player1' => 'Jugador 1',
        'player2' => 'Jugador 2',
        'placeholder' => 'Nickname de Faceit...',
        'button' => 'Iniciar comparación',
        'loading' => 'Análisis en progreso',
        'loading_text' => 'Comparación de jugadores',
        'errors' => [
            'both_players' => 'Por favor ingresa ambos nicknames',
            'different_players' => 'Por favor ingresa dos nicknames diferentes',
        ]
    ],
    'loading' => [
        'title' => 'Análisis en progreso',
        'messages' => [
            'player1_data' => 'Recuperando datos del jugador 1',
            'player2_data' => 'Recuperando datos del jugador 2',
            'analyzing_stats' => 'Analizando estadísticas',
            'calculating_scores' => 'Calculando puntuaciones de rendimiento',
            'comparing_roles' => 'Comparando roles de juego',
            'generating_report' => 'Generando informe final'
        ]
    ],
    'tabs' => [
        'overview' => 'Resumen',
        'detailed' => 'Estadísticas detalladas',
        'maps' => 'Mapas'
    ],
    'winner' => [
        'analysis_complete' => 'Análisis Completo Terminado',
        'wins_analysis' => ':winner gana el análisis de IA',
        'confidence' => 'Confianza: :percentage%',
        'performance_score' => 'Puntuación de Rendimiento',
        'matches' => 'Partidas'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Puntuaciones de rendimiento',
            'elo_impact' => 'Impacto ELO',
            'combat_performance' => 'Rendimiento de Combate',
            'experience' => 'Experiencia',
            'advanced_stats' => 'Estadísticas Avanzadas'
        ],
        'key_stats' => [
            'title' => 'Estadísticas clave',
            'kd_ratio' => 'Ratio K/D',
            'win_rate' => 'Tasa de victorias',
            'headshots' => 'Headshots',
            'adr' => 'ADR',
            'entry_success' => 'Éxito de Entrada',
            'clutch_1v1' => 'Clutch 1v1'
        ],
        'calculation_info' => [
            'title' => '¿Cómo se calculan las puntuaciones?',
            'elo_impact' => [
                'title' => 'Impacto ELO (35%)',
                'description' => 'El nivel ELO es el factor más importante ya que refleja directamente el nivel de juego contra oponentes de igual fuerza.'
            ],
            'combat_performance' => [
                'title' => 'Rendimiento de Combate (25%)',
                'description' => 'Combina K/D, tasa de victorias, ADR y nivel de Faceit para evaluar la efectividad en combate.'
            ],
            'experience' => [
                'title' => 'Experiencia (20%)',
                'description' => 'Número de partidas jugadas con un multiplicador basado en la experiencia acumulada.'
            ],
            'advanced_stats' => [
                'title' => 'Estadísticas Avanzadas (20%)',
                'description' => 'Headshots, entry fragging y habilidades de clutch para análisis en profundidad del estilo de juego.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'Rendimiento general',
                'stats' => [
                    'total_matches' => 'Partidas totales',
                    'win_rate' => 'Tasa de victorias',
                    'wins' => 'Victorias',
                    'avg_kd' => 'Ratio K/D promedio',
                    'adr' => 'ADR (daño/ronda)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Combate y precisión',
                'stats' => [
                    'avg_headshots' => 'Headshots promedio',
                    'total_headshots' => 'Headshots totales',
                    'total_kills' => 'Kills (estadísticas extendidas)',
                    'total_damage' => 'Daño total'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Entry fragging',
                'stats' => [
                    'entry_rate' => 'Tasa de entrada',
                    'entry_success' => 'Tasa de éxito de entrada',
                    'total_entries' => 'Intentos totales',
                    'successful_entries' => 'Entradas exitosas'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Situaciones de clutch',
                'stats' => [
                    '1v1_win_rate' => 'Tasa de victorias 1v1',
                    '1v2_win_rate' => 'Tasa de victorias 1v2',
                    '1v1_situations' => 'Situaciones 1v1',
                    '1v1_wins' => 'Victorias 1v1',
                    '1v2_situations' => 'Situaciones 1v2',
                    '1v2_wins' => 'Victorias 1v2'
                ]
            ],
            'utility_support' => [
                'title' => 'Utilidad y soporte',
                'stats' => [
                    'flash_success' => 'Tasa de éxito de flash',
                    'flashes_per_round' => 'Flashes por ronda',
                    'total_flashes' => 'Flashes totales',
                    'successful_flashes' => 'Flashes exitosos',
                    'enemies_flashed_per_round' => 'Enemigos flasheados/ronda',
                    'total_enemies_flashed' => 'Enemigos flasheados totales',
                    'utility_success' => 'Tasa de éxito de utilidad',
                    'utility_damage_per_round' => 'Daño de utilidad/ronda',
                    'total_utility_damage' => 'Daño de utilidad total'
                ]
            ],
            'sniper_special' => [
                'title' => 'Francotirador y armas especiales',
                'stats' => [
                    'sniper_kill_rate' => 'Tasa de kills de francotirador',
                    'sniper_kills_per_round' => 'Kills de francotirador/ronda',
                    'total_sniper_kills' => 'Kills de francotirador totales'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Rachas y consistencia',
                'stats' => [
                    'current_streak' => 'Racha actual',
                    'longest_streak' => 'Racha más larga'
                ]
            ]
        ],
        'legend' => 'Los valores en verde indican el jugador con mejor rendimiento para cada estadística'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'Sin mapas en común',
            'description' => 'Ambos jugadores no tienen mapas en común con datos suficientes.'
        ],
        'dominates' => ':player domina',
        'win_rate' => 'Tasa de Victorias (:matches partidas)',
        'kd_ratio' => 'Ratio K/D',
        'headshots' => 'Headshots',
        'adr' => 'ADR',
        'mvps' => 'MVPs',
        'summary' => [
            'title' => 'Resumen de mapas',
            'maps_dominated' => 'Mapas dominados',
            'best_map' => 'Mejor mapa',
            'none' => 'Ninguno'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Entry Fragger',
            'description' => 'Especializado en entradas de sitio'
        ],
        'support' => [
            'name' => 'Soporte',
            'description' => 'Maestro de utilidades de equipo'
        ],
        'clutcher' => [
            'name' => 'Clutcher',
            'description' => 'Experto en situaciones difíciles'
        ],
        'fragger' => [
            'name' => 'Fragger',
            'description' => 'Especialista en eliminaciones'
        ],
        'versatile' => [
            'name' => 'Versátil',
            'description' => 'Jugador equilibrado'
        ]
    ],
    'error' => [
        'title' => 'Error',
        'default_message' => 'Ocurrió un error durante la comparación',
        'retry' => 'Reintentar',
        'player_not_found' => 'Jugador ":player" no encontrado',
        'stats_error' => 'Error al recuperar estadísticas: :status'
    ]
];
