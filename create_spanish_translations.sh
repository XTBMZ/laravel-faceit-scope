#!/bin/bash

# Script de création des fichiers de traduction suédoise
# Traductions complètes pour le marché suédois

set -e  # Arrêter le script en cas d'erreur

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m' # No Color

# Configuration
LANG_DIR="resources/lang"
TARGET_LANG="sv"
TARGET_DIR="${LANG_DIR}/${TARGET_LANG}"

echo -e "${BLUE}🇸🇪 Script de traduction suédoise - Complet${NC}"
echo -e "${BLUE}============================================${NC}"
echo ""

# Vérifier que le répertoire cible existe ou le créer
if [ ! -d "$TARGET_DIR" ]; then
    echo -e "${YELLOW}📁 Création du répertoire '$TARGET_DIR'${NC}"
    mkdir -p "$TARGET_DIR"
else
    echo -e "${GREEN}📁 Répertoire trouvé: $TARGET_DIR${NC}"
fi

echo -e "${YELLOW}🔄 Début de la traduction des fichiers...${NC}"
echo ""

# Liste des fichiers à traiter
FILES_TO_TRANSLATE=(
    "about.php"
    "advanced.php"
    "auth.php"
    "common.php"
    "comparison.php"
    "contact.php"
    "errors.php"
    "footer.php"
    "friends.php"
    "home.php"
    "language.php"
    "leaderboards.php"
    "messages.php"
    "navigation.php"
    "privacy.php"
    "tournaments.php"
)

# ===============================
# ABOUT.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: about.php${NC}"
cat > "${TARGET_DIR}/about.php" << 'EOF'
<?php
return [
    'title' => 'Om oss - Faceit Scope',
    'hero' => [
        'title' => 'Om oss',
        'subtitle' => 'Faceit Scope använder avancerade algoritmer och artificiell intelligens för att analysera din prestanda på FACEIT. Detta är ett projekt utvecklat med passion.',
    ],
    'project' => [
        'title' => 'Projektet',
        'description_1' => 'Möjliggör djupgående prestandaanalys.',
        'description_2' => 'Helt utvecklat av',
        'description_3' => ', detta projekt använder endast det officiella FACEIT API:et för att få all data på ett transparent och lagligt sätt.',
        'description_4' => 'Allt kommer direkt från FACEIT-servrarna och analyseras av våra proprietära algoritmer.',
        'stats' => [
            'developer' => 'Utvecklare',
            'api' => 'FACEIT API',
        ],
    ],
    'how_it_works' => [
        'title' => 'Hur det fungerar',
        'subtitle' => 'Avancerade algoritmer analyserar din FACEIT-data för att ge dig precisa insikter',
        'pis' => [
            'title' => 'Player Impact Score (PIS)',
            'combat' => [
                'title' => 'Strid (35%)',
                'description' => 'K/D, ADR och headshot-procent, logaritmiskt normaliserat',
            ],
            'game_sense' => [
                'title' => 'Spelförståelse (25%)',
                'description' => 'Entry, clutch och sniper-förmågor, avancerade kombinationer',
            ],
            'utility' => [
                'title' => 'Verktyg (15%)',
                'description' => 'Stöd och verktygsutnyttjande, viktad effektivitet',
            ],
            'consistency' => [
                'title' => 'Konsistens + Erfarenhet (25%)',
                'description' => 'Vinstprocent, streak och datatillförlitlighet',
            ],
            'level_coefficient' => [
                'title' => 'Kritisk nivåkoefficient:',
                'description' => 'En nivå 10-spelare med 1.0 K/D bedöms högre än en nivå 2-spelare med 1.5 K/D, eftersom hen spelar mot starkare motståndare.',
            ],
        ],
        'roles' => [
            'title' => 'Intelligent rolltilldelning',
            'calculations_title' => 'Rollpoängberäkningar',
            'priority_title' => 'Tilldelningsprioritet',
            'entry_fragger' => [
                'title' => 'Entry Fragger',
                'criteria' => 'Specifika kriterer: Entry-rate > 25% OCH Entry-framgång > 55%',
            ],
            'support' => [
                'title' => 'Stöd',
                'criteria' => 'Specifika kriterer: Flash > 0.4/runda OCH Flash-framgång > 50%',
            ],
            'awper' => [
                'title' => 'AWPer',
                'criteria' => 'Specifika kriterer: Sniper-rate > 15%',
            ],
            'priority_items' => [
                'awper' => 'AWPer (om sniper > 15%)',
                'entry' => 'Entry Fragger (om entry > 25% + framgång > 55%)',
                'support' => 'Stöd (om flash > 0.4 + framgång > 50%)',
                'clutcher' => 'Clutcher (om 1v1 > 40%)',
                'fragger' => 'Fragger (om K/D > 1.3 + ADR > 85)',
                'lurker' => 'Lurker (standard, om inga andra kriterier)',
            ],
        ],
        'maps' => [
            'title' => 'Kartanalysalgoritm',
            'normalization' => [
                'title' => 'Logaritmisk normalisering',
            ],
            'weighting' => [
                'title' => 'Avancerad viktning',
                'win_rate' => 'Vinstprocent:',
                'consistency' => 'Konsistens:',
            ],
            'reliability' => [
                'title' => 'Tillförlitlighetsfaktor',
            ],
        ],
        'predictions' => [
            'title' => 'Matchförutsägelser',
            'team_strength' => [
                'title' => 'Lagstyrka',
                'average_score' => [
                    'title' => 'Viktat medelvärde',
                    'description' => 'Medelvärde av 5 PIS-poäng + rollbalansbonus',
                ],
                'role_balance' => [
                    'title' => 'Rollbalans',
                    'description' => 'Ett lag med Entry Fragger + Stöd + AWPer + Clutcher + Fragger får en betydande bonus jämfört med ett lag med 5 fraggers.',
                ],
            ],
            'probability' => [
                'title' => 'Sannolikhetsberäkning',
                'match_winner' => [
                    'title' => 'Matchvinnare',
                    'description' => 'Ju större styrkedifferens, desto mer exakt förutsägelse',
                ],
                'predicted_mvp' => [
                    'title' => 'Förutsedd MVP',
                    'description' => 'Spelaren med',
                    'description_end' => 'blir den förutsedda MVP:n bland de 10 deltagarna',
                    'highest_score' => 'högsta PIS-poängen',
                ],
                'confidence' => [
                    'title' => 'Säkerhetsnivå',
                    'description' => 'Baserat på styrkedifferens: Mycket hög (>3), Hög (>2), Medel (>1), Låg (<1)',
                ],
            ],
        ],
    ],
    'contact' => [
        'title' => 'Kontakt',
        'subtitle' => 'Detta är ett projekt utvecklat med passion. Välkommen att kontakta mig för feedback eller förslag.',
    ],
    'disclaimer' => [
        'text' => 'Faceit Scope är inte associerat med FACEIT Ltd. Detta projekt använder FACEIT:s offentliga API i enlighet med dess användarvillkor. Förutsägelsealgoritmer baseras på statistisk analys och garanterar inte matchresultat.',
    ],
];
EOF

# ===============================
# ADVANCED.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: advanced.php${NC}"
cat > "${TARGET_DIR}/advanced.php" << 'EOF'
<?php
return [
    'title' => 'Avancerad statistik - Faceit Scope',
    'loading' => [
        'title' => 'Analyserar',
        'default_text' => 'Hämtar data',
        'messages' => [
            'player_data' => 'Hämtar spelardata',
            'analyzing_stats' => 'Analyserar statistik',
            'calculating_performance' => 'Beräknar prestanda',
            'generating_charts' => 'Genererar diagram',
            'finalizing' => 'Slutför...',
        ],
    ],
    'player' => [
        'current_elo' => 'Nuvarande ELO',
        'rank' => 'Rank',
        'level' => 'Nivå :level',
        'faceit_button' => 'FACEIT',
        'compare_button' => 'Jämför',
    ],
    'sections' => [
        'overview' => 'Översikt',
        'combat_performance' => 'Stridsprestanda',
        'graphical_analysis' => 'Grafisk analys',
        'map_analysis' => 'Kartanalys',
        'achievements' => 'Prestationer',
        'recent_form' => 'Senaste form',
        'quick_actions' => 'Snabbåtgärder',
    ],
    'stats' => [
        'matches' => 'Matcher',
        'win_rate' => 'Vinstprocent',
        'kd_ratio' => 'K/D-förhållande',
        'headshots' => 'Headshots',
        'kr_ratio' => 'K/R-förhållande',
        'entry_rate' => 'Entry-rate',
        'clutch_master' => 'Clutch-mästare',
        'total_clutches' => 'Totala clutches',
        'entry_fragger' => 'Entry fragger',
        'success_rate' => 'Framgångsgrad',
        'support_master' => 'Stödmästare',
        'flash_success' => 'Flash-framgång',
        'performance_radar' => 'Prestandaradar',
        'map_distribution' => 'Kartfördelning',
    ],
    'detailed_stats' => [
        '1v1_win_rate' => '1v1 vinstprocent',
        '1v2_win_rate' => '1v2 vinstprocent',
        'entry_rate' => 'Entry-rate',
        'total_entries' => 'Totala entries',
        'successful_entries' => 'Framgångsrika entries',
        'flashes_per_round' => 'Flash/runda',
        'utility_success' => 'Verktygsframgång',
        'total_flash_assists' => 'Totala flash-assists',
    ],
    'achievements' => [
        'ace' => 'Ace (5K)',
        'quadro' => 'Quadro (4K)',
        'triple' => 'Triple (3K)',
        'current_streak' => 'Nuvarande streak',
        'longest_streak' => 'Längsta streak',
    ],
    'recent_results' => [
        'title' => 'Senaste resultat',
        'last_matches' => 'Senaste :count matcherna',
        'no_results' => 'Inga senaste resultat',
        'victory' => 'Vinst',
        'defeat' => 'Förlust',
        'match_number' => 'Match :number',
    ],
    'actions' => [
        'compare_player' => 'Jämför denna spelare',
        'download_report' => 'Ladda ner rapport',
        'view_progression' => 'Visa progression',
    ],
    'map_modal' => [
        'matches_played' => 'Spelade :matches matcher',
        'victories' => ':winrate% vinster',
        'combat' => 'Strid',
        'multi_kills' => 'Multi-kills',
        'entry_performance' => 'Entry-prestanda',
        'clutch_performance' => 'Clutch-prestanda',
        'utility_performance' => 'Verktygs-prestanda',
        'sniper_performance' => 'Sniper-prestanda',
        'close' => 'Stäng',
        'share' => 'Dela',
        'view_details' => 'Visa detaljer',
        'total_kills' => 'Totala kills',
        'total_deaths' => 'Totala dödsfall',
        'total_assists' => 'Totala assists',
        'kills_per_round' => 'Kills/runda',
        'deaths_per_round' => 'Dödsfall/runda',
        'opening_kill_ratio' => 'Öppnings-kill-förhållande',
        'aces' => 'Ace (5K)',
        'quadros' => 'Quadro (4K)',
        'triples' => 'Triple (3K)',
        'avg_aces_per_match' => 'Genomsnittliga Ace/match',
        'avg_4k_per_match' => 'Genomsnittliga 4K/match',
        'avg_3k_per_match' => 'Genomsnittliga 3K/match',
        'total_entries' => 'Totala entries',
        'success_rate' => 'Framgångsgrad',
        'successes_attempts' => ':wins framgångar / :total försök',
        'entry_wins_per_match' => 'Entry-vinster/match',
        'entry_attempts' => 'Entry-försök',
        'enemies_flashed' => 'Fiender flashade',
        'flash_per_round' => 'Flash/runda',
        '1v1_rate' => '1v1-rate',
        '1v2_rate' => '1v2-rate',
        'victories' => ':wins/:total vinster',
        '1v3_wins' => '1v3-vinster',
        '1v4_wins' => '1v4-vinster',
        '1v5_wins' => '1v5-vinster',
        'total_clutches' => 'Totala clutches',
        'flash_success' => 'Flash-framgång',
        'successful_flashes' => ':successes/:total framgångar',
        'flashes_per_round' => 'Flash/runda',
        'utility_damage' => 'Verktygsskada',
        'utility_success' => 'Verktygsframgång',
        'total_flashes' => 'Totala flash',
        'sniper_kills' => 'Sniper-kills',
        'sniper_k_per_round' => 'Sniper-kills/runda',
        'avg_sniper_k_per_match' => 'Genomsnittliga sniper-kills/match',
        'sniper_kill_rate' => 'Sniper-kill-rate',
        'total_damage' => 'Total skada',
        'utility_usage_per_round' => 'Verktygsutnyttjande/runda',
        'awp_expert' => 'AWP-expert!',
    ],
    'errors' => [
        'no_player' => 'Ingen spelare angiven',
        'player_not_found' => 'Spelare hittades inte',
        'loading_error' => 'Fel vid laddning av statistik',
        'no_export_data' => 'Ingen data att exportera',
        'back_home' => 'Tillbaka till startsidan',
    ],
    'notifications' => [
        'report_downloaded' => 'Rapport nedladdad framgångsrikt!',
        'link_copied' => 'Länk kopierad till urklipp!',
    ],
    'map_stats' => [
        'no_map_data' => 'Ingen kartdata',
        'share_title' => 'Min statistik på :map - Faceit Scope',
        'share_text' => 'Kolla in min prestanda på :map i CS2!',
    ],
];
EOF

# ===============================
# AUTH.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: auth.php${NC}"
cat > "${TARGET_DIR}/auth.php" << 'EOF'
<?php
return [
    'buttons' => [
        'login' => 'Logga in',
        'logout' => 'Logga ut',
        'profile' => 'Min profil',
        'stats' => 'Min statistik',
        'user_menu' => 'Användarmeny',
    ],
    'status' => [
        'connected' => 'Ansluten',
        'welcome' => 'Välkommen :nickname!',
        'logout_success' => 'Framgångsrikt utloggad',
        'profile_unavailable' => 'Profildata otillgänglig',
    ],
    'errors' => [
        'popup_blocked' => 'Kan inte öppna popup. Kontrollera om popups är blockerade.',
        'login_popup' => 'FACEIT inloggnings-popup fel: :error',
        'login_failed' => 'Inloggningsfel: :error',
        'logout_failed' => 'Fel vid utloggning',
        'unknown_error' => 'Okänt fel',
        'auth_init' => 'Autentiseringsinitiering fel: ',
        'auth_check' => 'Autentiseringskontroll fel: ',
    ],
    'console' => [
        'auth_status' => 'Autentiseringsstatus: ',
        'popup_opened' => 'FACEIT popup öppnad',
        'auth_result' => 'Autentiseringsresultat mottaget: ',
        'ui_updated' => 'UI uppdaterad: ',
        'service_loaded' => '🔐 FACEIT autentiseringstjänst laddad',
    ],
];
EOF

# ===============================
# COMMON.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: common.php${NC}"
cat > "${TARGET_DIR}/common.php" << 'EOF'
<?php
return [
    'loading' => 'Laddar...',
    'error' => 'Fel',
    'success' => 'Framgång',
    'retry' => 'Försök igen',
    'cancel' => 'Avbryt',
    'confirm' => 'Bekräfta',
    'close' => 'Stäng',
    'search' => 'Sök',
    'filter' => 'Filtrera',
    'sort' => 'Sortera',
    'refresh' => 'Uppdatera',
    'save' => 'Spara',
    'delete' => 'Ta bort',
    'edit' => 'Redigera',
    'view' => 'Visa',
    'today' => 'Idag',
    'yesterday' => 'Igår',
    'days_ago' => ':count dagar sedan',
    'weeks_ago' => ':count veckor sedan',
    'months_ago' => ':count månader sedan',
    'no_data' => 'Ingen data',
    'server_error' => 'Serverfel. Försök igen senare.',
    'network_error' => 'Anslutningsfel. Kontrollera din internetanslutning.',
];
EOF

# ===============================
# COMPARISON.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: comparison.php${NC}"
cat > "${TARGET_DIR}/comparison.php" << 'EOF'
<?php
return [
    'title' => 'Spelarjämförelse - Faceit Scope',
    'hero' => [
        'title' => 'Spelarjämförelse',
        'subtitle' => 'Jämför prestandan hos två CS2-spelare',
    ],
    'search' => [
        'player1' => 'Spelare 1',
        'player2' => 'Spelare 2',
        'placeholder' => 'Faceit smeknamn...',
        'button' => 'Starta jämförelse',
        'loading' => 'Analyserar',
        'loading_text' => 'Jämför spelare',
        'errors' => [
            'both_players' => 'Ange två smeknamn',
            'different_players' => 'Ange två olika smeknamn',
        ]
    ],
    'loading' => [
        'title' => 'Analyserar',
        'messages' => [
            'player1_data' => 'Hämtar data för spelare 1',
            'player2_data' => 'Hämtar data för spelare 2',
            'analyzing_stats' => 'Analyserar statistik',
            'calculating_scores' => 'Beräknar prestandapoäng',
            'comparing_roles' => 'Jämför spelroller',
            'generating_report' => 'Genererar slutrapport'
        ]
    ],
    'tabs' => [
        'overview' => 'Översikt',
        'detailed' => 'Detaljerad statistik',
        'maps' => 'Kartor'
    ],
    'winner' => [
        'analysis_complete' => 'Analys klar',
        'wins_analysis' => ':winner vinner AI-analysen',
        'confidence' => 'Säkerhet: :percentage%',
        'performance_score' => 'Prestandapoäng',
        'matches' => 'Matcher'
    ],
    'overview' => [
        'performance_scores' => [
            'title' => 'Prestandapoäng',
            'elo_impact' => 'ELO-påverkan',
            'combat_performance' => 'Stridsprestanda',
            'experience' => 'Erfarenhet',
            'advanced_stats' => 'Avancerad statistik'
        ],
        'key_stats' => [
            'title' => 'Nyckelstatistik',
            'kd_ratio' => 'K/D-förhållande',
            'win_rate' => 'Vinstprocent',
            'headshots' => 'Headshots',
            'adr' => 'ADR',
            'entry_success' => 'Entry-framgång',
            'clutch_1v1' => 'Clutch 1v1'
        ],
        'calculation_info' => [
            'title' => 'Hur beräknas poängen?',
            'elo_impact' => [
                'title' => 'ELO-påverkan (35%)',
                'description' => 'ELO-nivån är den viktigaste faktorn eftersom den direkt återspeglar spelnivån mot motståndare av jämn styrka.'
            ],
            'combat_performance' => [
                'title' => 'Stridsprestanda (25%)',
                'description' => 'Kombinerar K/D, vinstprocent, ADR och Faceit-nivå för att bedöma stridseffektivitet.'
            ],
            'experience' => [
                'title' => 'Erfarenhet (20%)',
                'description' => 'Antal spelade matcher, multiplikator baserad på ackumulerad erfarenhet.'
            ],
            'advanced_stats' => [
                'title' => 'Avancerad statistik (20%)',
                'description' => 'Headshots, entry- och clutch-förmågor för djupgående spelstilsanalys.'
            ]
        ]
    ],
    'detailed' => [
        'categories' => [
            'general_performance' => [
                'title' => 'Allmän prestanda',
                'stats' => [
                    'total_matches' => 'Totala matcher',
                    'win_rate' => 'Vinstprocent',
                    'wins' => 'Vinster',
                    'avg_kd' => 'Genomsnittligt K/D-förhållande',
                    'adr' => 'ADR (Skada per runda)'
                ]
            ],
            'combat_precision' => [
                'title' => 'Strid och precision',
                'stats' => [
                    'avg_headshots' => 'Genomsnittliga headshots',
                    'total_headshots' => 'Totala headshots',
                    'total_kills' => 'Kills (utökad statistik)',
                    'total_damage' => 'Total skada'
                ]
            ],
            'entry_fragging' => [
                'title' => 'Entry fragging',
                'stats' => [
                    'entry_rate' => 'Entry-rate',
                    'entry_success' => 'Entry-framgång',
                    'total_entries' => 'Totala försök',
                    'successful_entries' => 'Framgångsrika entries'
                ]
            ],
            'clutch_situations' => [
                'title' => 'Clutch-situationer',
                'stats' => [
                    '1v1_win_rate' => '1v1 vinstprocent',
                    '1v2_win_rate' => '1v2 vinstprocent',
                    '1v1_situations' => '1v1-situationer',
                    '1v1_wins' => '1v1-vinster',
                    '1v2_situations' => '1v2-situationer',
                    '1v2_wins' => '1v2-vinster'
                ]
            ],
            'utility_support' => [
                'title' => 'Verktyg och stöd',
                'stats' => [
                    'flash_success' => 'Flash-framgång',
                    'flashes_per_round' => 'Flash per runda',
                    'total_flashes' => 'Totala flash',
                    'successful_flashes' => 'Framgångsrika flash',
                    'enemies_flashed_per_round' => 'Fiender flashade per runda',
                    'total_enemies_flashed' => 'Totala fiender flashade',
                    'utility_success' => 'Verktygsframgång',
                    'utility_damage_per_round' => 'Verktygsskada per runda',
                    'total_utility_damage' => 'Total verktygsskada'
                ]
            ],
            'sniper_special' => [
                'title' => 'Sniper och specialvapen',
                'stats' => [
                    'sniper_kill_rate' => 'Sniper-kill-rate',
                    'sniper_kills_per_round' => 'Sniper-kills per runda',
                    'total_sniper_kills' => 'Totala sniper-kills'
                ]
            ],
            'streaks_consistency' => [
                'title' => 'Streaks och konsistens',
                'stats' => [
                    'current_streak' => 'Nuvarande streak',
                    'longest_streak' => 'Längsta streak'
                ]
            ]
        ],
        'legend' => 'Gröna värden indikerar spelaren som presterar bättre i den statistiken'
    ],
    'maps' => [
        'no_common_maps' => [
            'title' => 'Inga gemensamma kartor',
            'description' => 'De två spelarna har inga gemensamma kartor med tillräcklig data.'
        ],
        'dominates' => ':player dominerar',
        'win_rate' => 'Vinstprocent (:matches matcher)',
        'kd_ratio' => 'K/D-förhållande',
        'headshots' => 'Headshots',
        'adr' => 'ADR',
        'mvps' => 'MVP',
        'summary' => [
            'title' => 'Kartsammanfattning',
            'maps_dominated' => 'Dominerade kartor',
            'best_map' => 'Bästa karta',
            'none' => 'Ingen'
        ]
    ],
    'roles' => [
        'entry_fragger' => [
            'name' => 'Entry Fragger',
            'description' => 'Specialiserad på att attackera positioner'
        ],
        'support' => [
            'name' => 'Stöd',
            'description' => 'Lagstödsmästare'
        ],
        'clutcher' => [
            'name' => 'Clutcher',
            'description' => 'Expert på svåra situationer'
        ],
        'fragger' => [
            'name' => 'Fragger',
            'description' => 'Elimineringsspecialist'
        ],
        'versatile' => [
            'name' => 'Mångsidig',
            'description' => 'Balanserad spelare'
        ]
    ],
    'error' => [
        'title' => 'Fel',
        'default_message' => 'Ett fel uppstod under jämförelsen',
        'retry' => 'Försök igen',
        'player_not_found' => 'Spelare ":player" hittades inte',
        'stats_error' => 'Fel vid hämtning av statistik: :status'
    ]
];
EOF

# ===============================
# CONTACT.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: contact.php${NC}"
cat > "${TARGET_DIR}/contact.php" << 'EOF'
<?php
return [
    'title' => 'Kontakt - Faceit Scope',
    'hero' => [
        'title' => 'Kontakta oss',
    ],
    'sidebar' => [
        'developer' => [
            'title' => 'Utvecklare',
            'name_label' => 'Namn',
            'name_value' => 'XTBMZ',
        ],
        'response' => [
            'title' => 'Svar',
            'average_delay' => 'Genomsnittlig fördröjning',
            'delay_value' => '24 timmar',
        ],
    ],
    'form' => [
        'type' => [
            'label' => 'Meddelandetyp',
            'required' => '*',
            'placeholder' => 'Välj typ',
            'options' => [
                'bug' => 'Rapportera bugg',
                'suggestion' => 'Förslag',
                'question' => 'Fråga',
                'feedback' => 'Feedback',
                'other' => 'Annat',
            ],
        ],
        'subject' => [
            'label' => 'Ämne',
            'required' => '*',
        ],
        'email' => [
            'label' => 'E-post',
            'required' => '*',
        ],
        'pseudo' => [
            'label' => 'Faceit användarnamn',
            'optional' => '(valfritt)',
        ],
        'message' => [
            'label' => 'Meddelande',
            'required' => '*',
            'character_count' => 'tecken',
        ],
        'submit' => [
            'send' => 'Skicka',
            'sending' => 'Skickar...',
        ],
        'privacy_note' => 'Din data används endast för att behandla din förfrågan',
    ],
    'messages' => [
        'success' => [
            'title' => 'Meddelande skickat framgångsrikt',
            'ticket_id' => 'Ärende-ID: ',
        ],
        'error' => [
            'title' => 'Skickningsfel',
            'connection' => 'Anslutningsfel. Försök igen.',
            'generic' => 'Ett fel uppstod. ',
        ],
    ],
];
EOF

# ===============================
# ERRORS.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: errors.php${NC}"
cat > "${TARGET_DIR}/errors.php" << 'EOF'
<?php
return [
    'page_not_found' => 'Sidan hittades inte',
    'server_error' => 'Serverfel',
    'unauthorized' => 'Ej behörig',
    'forbidden' => 'Åtkomst nekad',
    'too_many_requests' => 'För många förfrågningar',
];
EOF

# ===============================
# FOOTER.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: footer.php${NC}"
cat > "${TARGET_DIR}/footer.php" << 'EOF'
<?php
return [
    'about' => 'Om oss',
    'privacy' => 'Integritet',
    'contact' => 'Kontakt',
    'data_provided' => 'Data tillhandahållen av FACEIT API.',
];
EOF

# ===============================
# FRIENDS.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: friends.php${NC}"
cat > "${TARGET_DIR}/friends.php" << 'EOF'
<?php
return [
    'title' => 'Mina FACEIT-vänner',
    'subtitle' => 'Upptäck prestandan hos din spelkrets',
    'load_more' => 'Visa fler :count',
    'stats' => [
        'total' => 'Totalt',
        'active_7d' => 'Aktiva (7 dagar)',
        'average_elo' => 'Genomsnittlig ELO',
        'best' => 'Bästa',
    ],
    'search_placeholder' => 'Sök vänner...',
    'activity_filter' => [
        'all' => 'All aktivitet',
        'recent' => 'Senaste (7 dagar)',
        'month' => 'Denna månad',
        'inactive' => 'Inaktiva (30 dagar+)',
    ],
    'sort_by' => [
        'elo' => 'ELO',
        'activity' => 'Aktivitet',
        'name' => 'Namn',
        'level' => 'Nivå',
    ],
    'loading' => [
        'title' => 'Laddar vänner...',
        'connecting' => 'Ansluter...',
        'fetching_friends' => 'Hämtar vänlista...',
        'loading_all' => 'Laddar alla vänner...',
        'finalizing' => 'Slutför...',
    ],
    'empty' => [
        'title' => 'Inga vänner hittades',
        'description' => 'Du har inga vänner på FACEIT än',
        'action' => 'Gå till FACEIT',
    ],
    'error' => [
        'title' => 'Laddningsfel',
        'not_authenticated' => 'Inte autentiserad',
        'missing_data' => 'Användardata saknas',
        'load_failed' => 'Kunde inte ladda dina vänner. Kontrollera anslutningen.',
        'server_error' => 'Serverfel. Försök igen senare.',
    ],
    'modal' => [
        'title' => 'Vändetaljer',
        'last_activity' => 'Senaste aktivitet',
        'elo_faceit' => 'FACEIT ELO',
        'view_faceit' => 'Visa på FACEIT',
        'view_stats' => 'Visa statistik',
    ],
    'activity' => [
        'today' => 'Idag',
        'yesterday' => 'Igår',
        'days_ago' => ':count dagar sedan',
        'weeks_ago' => ':count veckor sedan',
        'weeks_ago_plural' => ':count veckor sedan',
        'months_ago' => ':count månader sedan',
        'no_recent' => 'Ingen senaste aktivitet',
    ],
    'count' => ':count vänner',
    'filtered_count' => '(visar :count)',
    'load_more' => 'Visa fler :count',
    'success_rate' => ':percentage% framgång',
    'friends_loaded' => 'Laddade :loaded vänner av :total',
];
EOF

# ===============================
# HOME.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: home.php${NC}"
cat > "${TARGET_DIR}/home.php" << 'EOF'
<?php
return [
    'title' => 'Faceit Scope - Analysera din FACEIT-statistik',
    'hero' => [
        'subtitle' => 'Analysera din prestanda på FACEIT med avancerade algoritmer och artificiell intelligens. Upptäck dina styrkor och förbättra dina färdigheter.',
        'features' => [
            'detailed_stats' => 'Detaljerad statistik',
            'artificial_intelligence' => 'Artificiell intelligens',
            'predictive_analysis' => 'Prediktiv analys',
        ]
    ],
    'search' => [
        'title' => 'Börja analysera',
        'subtitle' => 'Sök efter en spelare eller analysera en match för att upptäcka detaljerade insikter',
        'player' => [
            'title' => 'Sök spelare',
            'description' => 'Analysera spelarprestanda',
            'placeholder' => 'FACEIT spelarnamn...',
            'button' => 'Sök',
            'loading' => 'Söker...',
        ],
        'match' => [
            'title' => 'Analysera match',
            'description' => 'AI-förutsägelser och djupanalys',
            'placeholder' => 'Match-ID eller URL...',
            'button' => 'Analysera',
            'loading' => 'Analyserar...',
        ],
        'errors' => [
            'empty_player' => 'Ange ett spelarnamn',
            'empty_match' => 'Ange ett match-ID eller URL',
            'player_not_found' => 'Spelare ":player" hittades inte på FACEIT',
            'no_cs_stats' => 'Spelare ":player" har aldrig spelat CS2/CS:GO på FACEIT',
            'no_stats_available' => 'Ingen tillgänglig statistik för ":player"',
            'match_not_found' => 'Ingen match hittades för detta ID eller URL',
            'invalid_format' => 'Ogiltigt format för match-ID eller URL. Giltiga exempel:\n• 1-73d82823-9d7b-477a-88c4-5ba16045f051\n• https://www.faceit.com/en/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
            'too_many_requests' => 'För många förfrågningar. Vänta.',
            'access_forbidden' => 'Åtkomst nekad. API-nyckelproblem.',
            'generic_player' => 'Fel vid sökning av ":player". Kontrollera anslutningen.',
            'generic_match' => 'Fel vid hämtning av match. Kontrollera ID eller URL.',
        ]
    ],
    'features' => [
        'title' => 'Funktioner',
        'subtitle' => 'Kraftfulla verktyg för att analysera och förbättra din prestanda',
        'advanced_stats' => [
            'title' => 'Avancerad statistik',
            'description' => 'Analysera din prestanda per karta, spåra ditt K/D, headshots och upptäck dina bästa/sämsta kartor med våra algoritmer.',
        ],
        'ai' => [
            'title' => 'Artificiell intelligens',
            'description' => 'Matchförutsägelser, nyckelspelareidentifiering, rollanalys och personaliserade rekommendationer baserade på din data.',
        ],
        'lobby_analysis' => [
            'title' => 'Lobbyanalys',
            'description' => 'Upptäck matchsammansättning, styrkor och få detaljerade matchresultatförutsägelser.',
        ]
    ],
    'how_it_works' => [
        'title' => 'Hur det fungerar',
        'subtitle' => 'Vetenskapligt tillvägagångssätt för FACEIT-prestandaanalys',
        'steps' => [
            'data_collection' => [
                'title' => 'Datainsamling',
                'description' => 'Vi använder endast det officiella FACEIT API:et för att få all din statistik på ett transparent och lagligt sätt.',
            ],
            'algorithmic_analysis' => [
                'title' => 'Algoritmisk analys',
                'description' => 'Våra algoritmer analyserar din data genom normalisering, viktning och säkerhetsberäkningar för precisa insikter.',
            ],
            'personalized_insights' => [
                'title' => 'Personaliserade insikter',
                'description' => 'Få detaljerade analyser, förutsägelser och rekommendationer för att förbättra din spelprestanda.',
            ]
        ]
    ]
];
EOF

# ===============================
# LANGUAGE.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: language.php${NC}"
cat > "${TARGET_DIR}/language.php" << 'EOF'
<?php
return [
    'changed_successfully' => 'Språk ändrat framgångsrikt',
    'french' => 'Français',
    'english' => 'English',
    'spanish' => 'Español',
    'portuguese' => 'Português',
    'russian' => 'Русский',
    'italian' => 'Italiano',
    'chinese' => '中文',
    'polish' => 'Polski',
    'ukrainian' => 'Українська',
    'swedish' => 'Svenska',
];
EOF

# ===============================
# LEADERBOARDS.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: leaderboards.php${NC}"
cat > "${TARGET_DIR}/leaderboards.php" << 'EOF'
<?php
return [
    'title' => 'CS2 Globala rankning - Faceit Scope',
    'hero' => [
        'title' => 'CS2 Rankning',
        'subtitle' => 'Toppspelare visas i realtid via FACEIT API',
    ],
    'stats' => [
        'players' => 'Spelare',
        'average_elo' => 'Genomsnittlig ELO',
        'country' => 'Land',
        'level' => 'Nivå',
    ],
    'filters' => [
        'region' => 'Region',
        'country' => 'Land',
        'limit' => 'Gräns',
        'refresh' => 'Uppdatera',
        'search' => 'Sök',
        'regions' => [
            'EU' => '🌍 Europa',
            'NA' => '🌎 Nordamerika',
            'SA' => '🌎 Sydamerika',
            'AS' => '🌏 Asien',
            'AF' => '🌍 Afrika',
            'OC' => '🌏 Oceanien',
        ],
        'countries' => [
            'all' => 'Alla',
        ],
        'limits' => [
            'top20' => 'Topp 20',
            'top50' => 'Topp 50',
            'top100' => 'Topp 100',
        ],
        'refreshing' => 'Uppdaterar...',
        'close' => 'Stäng',
    ],
    'search' => [
        'title' => 'Sök spelare',
        'placeholder' => 'FACEIT spelarnamn...',
        'button' => 'Sök',
        'searching' => 'Söker...',
        'searching_for' => 'Söker efter :player...',
        'errors' => [
            'empty_name' => 'Ange ett spelarnamn',
            'not_found' => 'Spelare ":player" hittades inte',
            'no_cs2_profile' => 'Spelare ":player" har ingen CS2-profil',
            'timeout' => 'Sökningen är för långsam, försök igen...',
        ],
    ],
    'loading' => [
        'title' => 'Laddar...',
        'progress' => 'Ansluter till FACEIT API',
        'players_enriched' => ':count spelare berikade...',
        'details' => 'Laddar...',
    ],
    'error' => [
        'title' => 'Laddningsfel',
        'default_message' => 'Ett fel uppstod',
        'retry' => 'Försök igen',
        'no_players' => 'Inga spelare hittades för denna rankning',
    ],
    'leaderboard' => [
        'title' => 'Global rankning',
        'updated_now' => 'Precis uppdaterad',
        'updated_on' => 'Uppdaterad :date kl :time',
        'table' => [
            'rank' => '#',
            'player' => 'Spelare',
            'stats' => '',
            'elo' => 'ELO',
            'level' => 'Nivå',
            'form' => 'Form',
            'actions' => 'Åtgärder',
        ],
        'pagination' => [
            'previous' => 'Föregående',
            'next' => 'Nästa',
            'page' => 'Sida :page',
            'players' => 'Spelare :start-:end',
        ],
        'region_names' => [
            'EU' => 'Europa',
            'NA' => 'Nordamerika',
            'SA' => 'Sydamerika',
            'AS' => 'Asien',
            'AF' => 'Afrika',
            'OC' => 'Oceanien',
        ],
        'country_names' => [
            'FR' => 'Frankrike',
            'DE' => 'Tyskland',
            'GB' => 'Storbritannien',
            'ES' => 'Spanien',
            'IT' => 'Italien',
            'US' => 'USA',
            'CA' => 'Kanada',
            'BR' => 'Brasilien',
            'RU' => 'Ryssland',
            'PL' => 'Polen',
            'SE' => 'Sverige',
            'DK' => 'Danmark',
            'NO' => 'Norge',
            'FI' => 'Finland',
            'NL' => 'Nederländerna',
            'BE' => 'Belgien',
            'CH' => 'Schweiz',
            'AT' => 'Österrike',
            'CZ' => 'Tjeckien',
            'UA' => 'Ukraina',
            'TR' => 'Turkiet',
            'CN' => 'Kina',
            'KR' => 'Sydkorea',
            'JP' => 'Japan',
        ],
    ],
    'player' => [
        'position_region' => 'Position i :region',
        'stats_button' => 'Statistik',
        'compare_button' => 'Jämför',
        'view_stats' => 'Visa statistik',
        'private_stats' => 'Privat',
        'level_short' => 'Nivå :level',
    ],
    'form' => [
        'excellent' => 'Utmärkt',
        'good' => 'Bra',
        'average' => 'Genomsnittlig',
        'poor' => 'Dålig',
        'unknown' => 'Okänd',
    ],
];
EOF

# ===============================
# MESSAGES.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: messages.php${NC}"
cat > "${TARGET_DIR}/messages.php" << 'EOF'
<?php
return [
    'welcome' => 'Välkommen',
    'goodbye' => 'Hej då',
    'thank_you' => 'Tack',
    'please_wait' => 'Vänta',
    'operation_successful' => 'Åtgärd lyckades',
    'operation_failed' => 'Åtgärd misslyckades',
];
EOF

# ===============================
# NAVIGATION.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: navigation.php${NC}"
cat > "${TARGET_DIR}/navigation.php" << 'EOF'
<?php
return [
    'home' => 'Hem',
    'friends' => 'Vänner',
    'comparison' => 'Jämförelse',
    'leaderboards' => 'Rankning',
    'tournaments' => 'Turneringar',
    'profile' => 'Profil',
    'login' => 'Logga in',
    'logout' => 'Logga ut',
    'settings' => 'Inställningar',
    'about' => 'Om oss',
    'contact' => 'Kontakt',
    'privacy' => 'Integritet',
];
EOF

# ===============================
# PRIVACY.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: privacy.php${NC}"
cat > "${TARGET_DIR}/privacy.php" << 'EOF'
<?php
return [
    'title' => 'Integritetspolicy - Faceit Scope',
    'header' => [
        'title' => 'Integritetspolicy',
        'subtitle' => 'Faceit Scope-tillägg',
        'last_updated' => 'Senast uppdaterad: 23 juli 2025',
    ],
    'introduction' => [
        'title' => '1. Introduktion',
        'content' => 'Faceit Scope är ett webbläsartillägg som analyserar CS2-matcher på FACEIT för att visa statistik och förutsägelser. Vi respekterar din integritet och är engagerade i att skydda din personliga data.',
    ],
    'data_collected' => [
        'title' => '2. Insamlad data',
        'temporary_data' => [
            'title' => '2.1 Data som bearbetas tillfälligt (lagras inte)',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'Offentliga FACEIT-användarnamn:',
                    'description' => 'Spelnamn som redan visas offentligt på FACEIT, tillfälligt lästa för analys',
                ],
                'public_stats' => [
                    'title' => 'Offentlig spelstatistik:',
                    'description' => 'K/D, vinstprocent, spelade kartor (via FACEIT offentligt API)',
                ],
                'match_ids' => [
                    'title' => 'Match-ID:n:',
                    'description' => 'Extraherade från URL:er för att identifiera matchen att analysera',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 Data som lagras lokalt (endast tillfällig cache)',
            'items' => [
                'analysis_results' => [
                    'title' => 'Analysresultat:',
                    'description' => 'Lagras på din enhet i högst 5 minuter för att undvika duplicerade API-anrop',
                ],
                'user_preferences' => [
                    'title' => 'Användarpreferenser:',
                    'description' => 'Tilläggsinställningar (aktivera/inaktivera notifieringar)',
                ],
            ],
        ],
        'important_note' => 'Viktigt: Vi samlar inte in eller sparar personligt identifierbar data. All bearbetad data är redan offentlig på FACEIT.',
    ],
    'data_usage' => [
        'title' => '3. Dataanvändning',
        'description' => 'Insamlad data används endast för att:',
        'items' => [
            'display_stats' => 'Visa spelarstatistik i FACEIT-gränssnittet',
            'predictions' => 'Beräkna vinnande lagförutsägelser',
            'map_recommendations' => 'Rekommendera bästa/sämsta kartor för lag',
            'performance' => 'Förbättra prestanda genom tillfällig caching',
        ],
    ],
    'data_sharing' => [
        'title' => '4. Datadelning',
        'no_third_party' => [
            'title' => '4.1 Ingen delning med tredje part',
            'items' => [
                'no_selling' => 'Vi säljer ingen data till tredje part',
                'no_transfer' => 'Vi överför ingen personlig data',
                'local_analysis' => 'All analys utförs lokalt i din webbläsare',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 FACEIT API',
            'items' => [
                'public_api' => 'Tillägget använder endast det officiella offentliga FACEIT API:et',
                'no_private_data' => 'Samlar inte in privat eller känslig data',
                'public_stats' => 'All använd statistik är offentligt tillgänglig',
            ],
        ],
    ],
    'security' => [
        'title' => '5. Säkerhet och lagring',
        'local_storage' => [
            'title' => '5.1 Endast lokal lagring',
            'items' => [
                'local_only' => 'All data lagras lokalt på din enhet',
                'no_server_transmission' => 'Ingen data överförs till våra servrar',
                'auto_delete' => 'Cache raderas automatiskt efter 5 minuter',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 Begränsad åtkomst',
            'items' => [
                'faceit_only' => 'Tillägget får endast åtkomst till FACEIT-sidor du besöker',
                'no_other_access' => 'Får inte åtkomst till andra webbplatser eller personlig data',
                'no_tracking' => 'Spårar inte din webbläsning',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. Dina rättigheter',
        'data_control' => [
            'title' => '6.1 Datakontroll',
            'items' => [
                'clear_cache' => 'Du kan rensa cache när som helst via tilläggets popup',
                'uninstall' => 'Du kan avinstallera tillägget för att ta bort all data',
                'disable_notifications' => 'Du kan inaktivera notifieringar i inställningarna',
            ],
        ],
        'public_data' => [
            'title' => '6.2 Offentlig data',
            'items' => [
                'already_public' => 'All analyserad data är redan offentlig på FACEIT',
                'no_private_info' => 'Tillägget avslöjar ingen privat information',
                'no_personal_data' => 'Samlar inte in personligt identifierbar data',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Cookies och spårningsteknologi',
        'description' => 'Faceit Scope-tillägget:',
        'does_not_use' => [
            'title' => 'Använder inte:',
            'items' => [
                'no_cookies' => 'Cookies',
                'no_ad_tracking' => 'Annonsspårning',
                'no_behavioral_analysis' => 'Beteendeanalys',
            ],
        ],
        'uses_only' => [
            'title' => 'Använder endast:',
            'items' => [
                'local_storage' => 'Webbläsarens lokala lagring',
                'temp_cache' => 'Tillfällig cache (max 5 minuter)',
                'public_api' => 'FACEIT offentligt API',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. Uppdateringar av denna policy',
        'content' => 'Vi kan uppdatera denna integritetspolicy. Ändringar kommer att publiceras på denna sida och du kommer att meddelas via tilläggsuppdatering om nödvändigt.',
    ],
    'contact' => [
        'title' => '9. Kontakt',
        'description' => 'För frågor om denna integritetspolicy:',
        'website' => 'Webbplats: ',
        'email' => 'E-post: ',
    ],
    'compliance' => [
        'title' => '10. Regelefterlevnad',
        'description' => 'Detta tillägg följer:',
        'items' => [
            'gdpr' => 'Allmänna dataskyddsförordningen (GDPR)',
            'chrome_store' => 'Chrome Web Store-policy',
            'faceit_terms' => 'FACEIT API användarvillkor',
        ],
    ],
];
EOF

# ===============================
# TOURNAMENTS.PHP
# ===============================
echo -e "${PURPLE}📝 Traduction: tournaments.php${NC}"
cat > "${TARGET_DIR}/tournaments.php" << 'EOF'
<?php
return [
    'title' => 'CS2-turneringar - FACEIT Stats Pro',
    'hero' => [
        'title' => 'CS2-turneringar',
        'subtitle' => 'Upptäck officiella CS2-turneringar på FACEIT, följ de bästa esport-evenemangen i realtid',
        'features' => [
            'ongoing' => 'Pågående turneringar',
            'upcoming' => 'Kommande evenemang',
            'premium' => 'Premium-turneringar',
        ]
    ],
    'filters' => [
        'tabs' => [
            'ongoing' => 'Pågående',
            'upcoming' => 'Kommande',
            'past' => 'Avslutade',
            'featured' => 'Premium',
        ],
        'search' => [
            'placeholder' => 'Sök turneringar...',
            'button' => 'Sök',
        ],
        'stats' => [
            'ongoing' => 'Pågående',
            'upcoming' => 'Kommande',
            'prize_pools' => 'Prispooler',
            'participants' => 'Deltagare',
        ]
    ],
    'championship' => [
        'badges' => [
            'premium' => 'Premium',
            'ongoing' => 'Pågående',
            'upcoming' => 'Kommande',
            'finished' => 'Avslutad',
            'cancelled' => 'Inställd',
        ],
        'info' => [
            'participants' => 'Deltagare',
            'prize_pool' => 'Prispool',
            'registrations' => 'Registreringar',
            'organizer' => 'Arrangör',
            'status' => 'Status',
            'region' => 'Region',
            'level' => 'Nivå',
            'slots' => 'Platser',
        ],
        'actions' => [
            'details' => 'Detaljer',
            'view_faceit' => 'Visa på FACEIT',
            'view_matches' => 'Visa matcher',
            'results' => 'Resultat',
            'close' => 'Stäng',
        ]
    ],
    'modal' => [
        'loading' => [
            'title' => 'Laddar detaljer...',
            'subtitle' => 'Hämtar turneringsinformation',
        ],
        'error' => [
            'title' => 'Laddningsfel',
            'subtitle' => 'Kunde inte ladda turneringsdetaljer',
        ],
        'sections' => [
            'description' => 'Beskrivning',
            'information' => 'Information',
            'matches' => 'Turneringsmatcher',
            'results' => 'Turneringsresultat',
            'default_description' => 'Denna turnering är en del av officiella CS2-tävlingar arrangerade av FACEIT.',
        ],
        'matches' => [
            'loading' => 'Laddar matcher...',
            'no_matches' => 'Inga tillgängliga matcher för denna turnering',
            'error' => 'Fel vid laddning av matcher',
            'status' => [
                'finished' => 'Avslutad',
                'ongoing' => 'Pågående',
                'upcoming' => 'Kommande',
            ]
        ],
        'results' => [
            'loading' => 'Laddar resultat...',
            'no_results' => 'Inga tillgängliga resultat för denna turnering',
            'error' => 'Fel vid laddning av resultat',
            'position' => 'Position',
        ]
    ],
    'pagination' => [
        'previous' => 'Föregående',
        'next' => 'Nästa',
        'page' => 'Sida',
    ],
    'empty_state' => [
        'title' => 'Inga turneringar hittades',
        'subtitle' => 'Försök ändra dina filter eller sök efter något annat',
        'reset_button' => 'Återställ filter',
    ],
    'errors' => [
        'search' => 'Sökfel',
        'loading' => 'Fel vid laddning av turneringar',
        'api' => 'API-fel',
        'network' => 'Anslutningsfel',
    ]
];
EOF

echo ""
echo -e "${GREEN}🎉 Traduction suédoise terminée avec succès !${NC}"
echo -e "${BLUE}📁 Fichiers créés dans : $TARGET_DIR${NC}"
echo ""
echo -e "${YELLOW}📋 Résumé des fichiers traduits :${NC}"
ls -la "$TARGET_DIR" | grep -E "\.php$" | wc -l | xargs echo "Total des fichiers : "
echo ""
echo -e "${GREEN}✅ Tous les fichiers ont été traduits en suédois${NC}"
echo -e "${BLUE}🚀 Prêt à être utilisé dans votre application Laravel${NC}"
echo ""

# Vérifier que tous les fichiers ont été créés correctement
echo -e "${YELLOW}🔍 Vérification des fichiers créés :${NC}"
for file in "${FILES_TO_TRANSLATE[@]}"; do
    if [ -f "${TARGET_DIR}/${file}" ]; then
        echo -e "${GREEN}✅ $file${NC}"
    else
        echo -e "${RED}❌ $file (manquant)${NC}"
    fi
done

echo ""
echo -e "${BLUE}📖 Instructions d'utilisation :${NC}"
echo -e "${YELLOW}1.${NC} Copiez ce script dans votre projet Laravel"
echo -e "${YELLOW}2.${NC} Rendez-le exécutable : ${GREEN}chmod +x create_swedish_translations.sh${NC}"
echo -e "${YELLOW}3.${NC} Exécutez le script : ${GREEN}./create_swedish_translations.sh${NC}"
echo -e "${YELLOW}4.${NC} Ajoutez 'sv' à votre configuration locale Laravel"
echo ""