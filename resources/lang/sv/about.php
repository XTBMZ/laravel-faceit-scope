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
