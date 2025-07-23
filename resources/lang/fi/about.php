<?php
return [
    'title' => 'Tietoja meistä - Faceit Scope',
    'hero' => [
        'title' => 'Tietoja meistä',
        'subtitle' => 'Faceit Scope analysoi suoritustasi FACEITissä kehittyneiden algoritmien ja tekoälyn avulla. Tämä on intohimolla kehitetty projekti.',
    ],
    'project' => [
        'title' => 'Projektin esittely',
        'description_1' => 'Mahdollistaa syvällisen suoritusanalyysin.',
        'description_2' => 'Täysin kehittänyt',
        'description_3' => ', tämä projekti käyttää vain virallista FACEIT API:a saadakseen kaikki tiedot läpinäkyvällä ja laillisella tavalla.',
        'description_4' => 'Kaikki tulee suoraan FACEIT-palvelimilta ja analysoidaan omilla algoritmeillämme.',
        'stats' => [
            'developer' => 'Kehittäjä',
            'api' => 'FACEIT API',
        ],
    ],
    'how_it_works' => [
        'title' => 'Miten se toimii',
        'subtitle' => 'Kehittyneet algoritmit analysoivat FACEIT-tietojasi antaakseen tarkkoja oivalluksia',
        'pis' => [
            'title' => 'Pelaajan vaikutuspisteet (PIS)',
            'combat' => [
                'title' => 'Taistelu (35%)',
                'description' => 'K/D, ADR ja headshot-prosentti logaritmisella normalisoinnilla',
            ],
            'game_sense' => [
                'title' => 'Peliymmärrys (25%)',
                'description' => 'Entry-kyky, clutch ja sniper-kyky kehittyneellä yhdistelmällä',
            ],
            'utility' => [
                'title' => 'Utility (15%)',
                'description' => 'Tuki ja utility-käyttö painotetuilla tehokkuuksilla',
            ],
            'consistency' => [
                'title' => 'Johdonmukaisuus + Kokemus (25%)',
                'description' => 'Voittoprosentti, voittoputket ja datan luotettavuus',
            ],
            'level_coefficient' => [
                'title' => 'Ratkaiseva taso-kerroin:',
                'description' => 'Tason 10 pelaaja 1.0 K/D:llä on paremmin arvioitu kuin tason 2 pelaaja 1.5 K/D:llä, koska hän kohtaa vahvempia vastustajia.',
            ],
        ],
        'roles' => [
            'title' => 'Älykäs roolinjako',
            'calculations_title' => 'Roolin pistemäärän laskenta',
            'priority_title' => 'Jaon prioriteetti',
            'entry_fragger' => [
                'title' => 'Entry Fragger',
                'criteria' => 'Erityiset kriteerit: Entry rate > 25% JA Entry success > 55%',
            ],
            'support' => [
                'title' => 'Tuki',
                'criteria' => 'Erityiset kriteerit: Flash > 0.4/kierros JA Flash success > 50%',
            ],
            'awper' => [
                'title' => 'AWPer',
                'criteria' => 'Erityiset kriteerit: Sniper rate > 15%',
            ],
            'priority_items' => [
                'awper' => 'AWPer (jos sniper > 15%)',
                'entry' => 'Entry Fragger (jos entry > 25% + success > 55%)',
                'support' => 'Tuki (jos flash > 0.4 + success > 50%)',
                'clutcher' => 'Clutcher (jos 1v1 > 40%)',
                'fragger' => 'Fragger (jos K/D > 1.3 + ADR > 85)',
                'lurker' => 'Lurker (oletus, jos ei muita kriteerejä)',
            ],
        ],
        'maps' => [
            'title' => 'Kartta-analyysi algoritmi',
            'normalization' => [
                'title' => 'Logaritminen normalisointi',
            ],
            'weighting' => [
                'title' => 'Kehittynyt painotus',
                'win_rate' => 'Voittoprosentti:',
                'consistency' => 'Johdonmukaisuus:',
            ],
            'reliability' => [
                'title' => 'Luotettavuustekijä',
            ],
        ],
        'predictions' => [
            'title' => 'Ottelu-ennusteet',
            'team_strength' => [
                'title' => 'Joukkueen vahvuus',
                'average_score' => [
                    'title' => 'Painotettu keskiarvo',
                    'description' => '5 PIS-pistemäärän keskiarvo + roolitasapaino bonus',
                ],
                'role_balance' => [
                    'title' => 'Roolitasapaino',
                    'description' => 'Joukkue, jossa on Entry + Tuki + AWPer + Clutcher + Fragger saa merkittävän bonuksen verrattuna joukkueeseen, jossa on 5 fraggeria.',
                ],
            ],
            'probability' => [
                'title' => 'Todennäköisyyslaskenta',
                'match_winner' => [
                    'title' => 'Ottelun voittaja',
                    'description' => 'Mitä suurempi vahvuusero, sitä tarkempi ennuste',
                ],
                'predicted_mvp' => [
                    'title' => 'Ennustettu MVP',
                    'description' => 'Pelaaja, jolla on',
                    'description_end' => 'tulee olemaan ennustettu MVP 10 osallistujan joukossa',
                    'highest_score' => 'korkein PIS-pistemäärä',
                ],
                'confidence' => [
                    'title' => 'Luottamustaso',
                    'description' => 'Perustuu vahvuuseroon: Erittäin korkea (>3), Korkea (>2), Keskitaso (>1), Matala (<1)',
                ],
            ],
        ],
    ],
    'contact' => [
        'title' => 'Yhteystiedot',
        'subtitle' => 'Tämä on intohimolla kehitetty projekti. Voit ottaa yhteyttä palautetta tai ehdotuksia varten.',
    ],
    'disclaimer' => [
        'text' => 'Faceit Scope ei ole yhteydessä FACEIT Ltd:hen. Tämä projekti käyttää FACEIT:n julkista API:a heidän käyttöehtojensa mukaisesti. Ennustealgoritmit perustuvat tilastolliseen analyysiin eivätkä takaa ottelutulosten tarkkuutta.',
    ],
];
