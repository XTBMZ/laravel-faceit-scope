<?php
return [
    'title' => 'Faceit Scope - Analysoi FACEIT-tilastosi',
    'hero' => [
        'subtitle' => 'Analysoi suoritustasi FACEITissä kehittyneiden algoritmien ja tekoälyn avulla. Löydä vahvuutesi ja paranna taitojasi.',
        'features' => [
            'detailed_stats' => 'Yksityiskohtaiset tilastot',
            'artificial_intelligence' => 'Tekoäly',
            'predictive_analysis' => 'Ennustava analyysi',
        ]
    ],
    'search' => [
        'title' => 'Aloita analyysi',
        'subtitle' => 'Hae pelaajaa tai analysoi ottelua löytääksesi yksityiskohtaisia oivalluksia',
        'player' => [
            'title' => 'Hae pelaajaa',
            'description' => 'Analysoi pelaajan suorituskykyä',
            'placeholder' => 'FACEIT pelaajan nimi...',
            'button' => 'Hae',
            'loading' => 'Haetaan...',
        ],
        'match' => [
            'title' => 'Analysoi ottelu',
            'description' => 'AI-ennusteet ja syvä analyysi',
            'placeholder' => 'Ottelun ID tai URL...',
            'button' => 'Analysoi',
            'loading' => 'Analysoidaan...',
        ],
        'errors' => [
            'empty_player' => 'Anna pelaajan nimi',
            'empty_match' => 'Anna ottelun ID tai URL',
            'player_not_found' => 'Pelaajaa ":player" ei löytynyt FACEITista',
            'no_cs_stats' => 'Pelaaja ":player" ei ole koskaan pelannut CS2/CS:GO:ta FACEITissa',
            'no_stats_available' => 'Ei tilastoja saatavilla pelaajalle ":player"',
            'match_not_found' => 'Ottelua ei löytynyt tälle ID:lle tai URL:lle',
            'invalid_format' => 'Virheellinen ottelun ID tai URL-muoto. Kelvolliset esimerkit:\n• 1-73d82823-9d7b-477a-88c4-5ba16045f051\n• https://www.faceit.com/en/cs2/room/1-73d82823-9d7b-477a-88c4-5ba16045f051',
            'too_many_requests' => 'Liikaa pyyntöjä. Odota hetki.',
            'access_forbidden' => 'Pääsy kielletty. API-avainongelma.',
            'generic_player' => 'Virhe haettaessa ":player". Tarkista yhteys.',
            'generic_match' => 'Virhe ottelun haussa. Tarkista ID tai URL.',
        ]
    ],
    'features' => [
        'title' => 'Ominaisuudet',
        'subtitle' => 'Tehokkaita työkaluja suorituksesi analysointiin ja parantamiseen',
        'advanced_stats' => [
            'title' => 'Edistyneet tilastot',
            'description' => 'Analysoi suoritustasi karttoittain, seuraa K/D:täsi, pääosumiasi ja löydä parhaat/huonoimmat karttasi algoritmiemme avulla.',
        ],
        'ai' => [
            'title' => 'Tekoäly',
            'description' => 'Ottelu-ennusteet, avainpelaajien tunnistaminen, roolianalyysi ja henkilökohtaiset suositukset tietojesi perusteella.',
        ],
        'lobby_analysis' => [
            'title' => 'Lobby-analyysi',
            'description' => 'Löydä ottelun kokoonpano, edut ja saa yksityiskohtaisia ennusteita ottelun tuloksille.',
        ]
    ],
    'how_it_works' => [
        'title' => 'Miten se toimii',
        'subtitle' => 'Tieteellinen lähestymistapa FACEIT-suoritusanalyysiin',
        'steps' => [
            'data_collection' => [
                'title' => 'Tiedonkeruu',
                'description' => 'Käytämme vain virallista FACEIT API:a saadaksemme kaikki tilastosi läpinäkyvällä ja laillisella tavalla.',
            ],
            'algorithmic_analysis' => [
                'title' => 'Algoritminen analyysi',
                'description' => 'Algoritmimme analysoivat tietojasi normalisoinnin, painotuksen ja luotettavuuslaskentojen kautta tarkkojen oivallusten saamiseksi.',
            ],
            'personalized_insights' => [
                'title' => 'Henkilökohtaiset oivallukset',
                'description' => 'Saa yksityiskohtaisia analyysejä, ennusteita ja suosituksia pelisuorituksesi parantamiseksi.',
            ]
        ]
    ]
];
