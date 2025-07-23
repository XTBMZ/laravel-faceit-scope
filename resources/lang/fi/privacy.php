<?php
return [
    'title' => 'Tietosuojakäytäntö - Faceit Scope',
    'header' => [
        'title' => 'Tietosuojakäytäntö',
        'subtitle' => 'Faceit Scope -laajennus',
        'last_updated' => 'Viimeksi päivitetty: 23. heinäkuuta 2025',
    ],
    'introduction' => [
        'title' => '1. Johdanto',
        'content' => 'Faceit Scope on selainlaajennus, joka analysoi CS2-otteluita FACEITissa näyttääkseen tilastoja ja ennusteita. Kunnioitamme yksityisyyttäsi ja sitoudumme suojelemaan henkilötietojasi.',
    ],
    'data_collected' => [
        'title' => '2. Kerätyt tiedot',
        'temporary_data' => [
            'title' => '2.1 Väliaikaisesti käsitellyt tiedot (ei tallennettu)',
            'items' => [
                'faceit_usernames' => [
                    'title' => 'FACEIT julkiset käyttäjänimet:',
                    'description' => 'Pelinimimerkit, jotka ovat jo julkisesti näkyvissä FACEITissa, luettu väliaikaisesti analysointia varten',
                ],
                'public_stats' => [
                    'title' => 'Julkiset pelitilastot:',
                    'description' => 'K/D, voittoprosentti, pelatut kartat (FACEIT julkisen API:n kautta)',
                ],
                'match_ids' => [
                    'title' => 'Ottelun ID:t:',
                    'description' => 'Poimittu URL:eista analysoitavien otteluiden tunnistamiseksi',
                ],
            ],
        ],
        'local_data' => [
            'title' => '2.2 Paikallisesti tallennetut tiedot (vain väliaikainen välimuisti)',
            'items' => [
                'analysis_results' => [
                    'title' => 'Analyysitulokset:',
                    'description' => 'Tallennettu laitteellesi enintään 5 minuutiksi toistuvien API-kutsujen välttämiseksi',
                ],
                'user_preferences' => [
                    'title' => 'Käyttäjäasetukset:',
                    'description' => 'Laajennuksen asetukset (ota käyttöön/poista käytöstä ilmoitukset)',
                ],
            ],
        ],
        'important_note' => 'Tärkeää: Henkilökohtaisia tunnistetietoja ei kerätä tai tallenneta. Kaikki käsitellyt tiedot ovat jo julkisia FACEITissa.',
    ],
    'data_usage' => [
        'title' => '3. Tietojen käyttö',
        'description' => 'Kerättyjä tietoja käytetään vain:',
        'items' => [
            'display_stats' => 'Pelaajatilastojen näyttämiseen FACEIT-käyttöliittymässä',
            'predictions' => 'Voittajajoukkue-ennusteiden laskemiseen',
            'map_recommendations' => 'Parhaan/huonoimman kartan suosittelemiseen joukkueille',
            'performance' => 'Suorituskyvyn parantamiseen väliaikaisen välimuistin avulla',
        ],
    ],
    'data_sharing' => [
        'title' => '4. Tietojen jakaminen',
        'no_third_party' => [
            'title' => '4.1 Ei jakamista kolmansien osapuolten kanssa',
            'items' => [
                'no_selling' => 'Emme myy tietoja kolmansille osapuolille',
                'no_transfer' => 'Emme siirrä henkilötietoja',
                'local_analysis' => 'Kaikki analyysit suoritetaan paikallisesti selaimessasi',
            ],
        ],
        'faceit_api' => [
            'title' => '4.2 FACEIT API',
            'items' => [
                'public_api' => 'Laajennus käyttää vain virallista FACEIT julkista API:a',
                'no_private_data' => 'Yksityisiä tai arkaluonteisia tietoja ei kerätä',
                'public_stats' => 'Kaikki käytetyt tilastot ovat julkisesti saatavilla',
            ],
        ],
    ],
    'security' => [
        'title' => '5. Turvallisuus ja säilytys',
        'local_storage' => [
            'title' => '5.1 Vain paikallinen tallennus',
            'items' => [
                'local_only' => 'Kaikki tiedot tallennetaan paikallisesti laitteellesi',
                'no_server_transmission' => 'Tietoja ei siirretä palvelimillemme',
                'auto_delete' => 'Välimuisti poistetaan automaattisesti 5 minuutin kuluttua',
            ],
        ],
        'limited_access' => [
            'title' => '5.2 Rajallinen pääsy',
            'items' => [
                'faceit_only' => 'Laajennus pääsee käsiksi vain vierailemiisi FACEIT-sivuihin',
                'no_other_access' => 'Ei pääsyä muihin verkkosivustoihin tai henkilötietoihin',
                'no_tracking' => 'Ei selaustesi seurantaa',
            ],
        ],
    ],
    'user_rights' => [
        'title' => '6. Oikeutesi',
        'data_control' => [
            'title' => '6.1 Tietojen hallinta',
            'items' => [
                'clear_cache' => 'Voit tyhjentää välimuistin milloin tahansa laajennuksen popup-ikkunan kautta',
                'uninstall' => 'Voit poistaa laajennuksen asentamatta kaikki tiedot',
                'disable_notifications' => 'Voit poistaa ilmoitukset käytöstä asetuksissa',
            ],
        ],
        'public_data' => [
            'title' => '6.2 Julkiset tiedot',
            'items' => [
                'already_public' => 'Kaikki analysoidut tiedot ovat jo julkisia FACEITissa',
                'no_private_info' => 'Laajennus ei paljasta yksityisiä tietoja',
                'no_personal_data' => 'Henkilökohtaisia tunnisteita ei kerätä',
            ],
        ],
    ],
    'cookies' => [
        'title' => '7. Evästeet ja seurantateknologiat',
        'description' => 'Faceit Scope -laajennus:',
        'does_not_use' => [
            'title' => 'EI käytä:',
            'items' => [
                'no_cookies' => 'Ei evästeitä',
                'no_ad_tracking' => 'Ei mainosten seurantaa',
                'no_behavioral_analysis' => 'Ei käyttäytymisanalyysia',
            ],
        ],
        'uses_only' => [
            'title' => 'Käyttää VAIN:',
            'items' => [
                'local_storage' => 'Selaimen paikallista tallennusta',
                'temp_cache' => 'Väliaikaista välimuistia (max. 5 min)',
                'public_api' => 'FACEIT julkista API:a',
            ],
        ],
    ],
    'policy_updates' => [
        'title' => '8. Tämän käytännön päivitykset',
        'content' => 'Voimme päivittää tätä tietosuojakäytäntöä. Muutokset julkaistaan tällä sivulla, ja sinulle ilmoitetaan laajennuspäivityksen kautta tarvittaessa.',
    ],
    'contact' => [
        'title' => '9. Yhteystiedot',
        'description' => 'Kysymyksiä tästä tietosuojakäytännöstä:',
        'website' => 'Verkkosivusto:',
        'email' => 'Sähköposti:',
    ],
    'compliance' => [
        'title' => '10. Säädösten noudattaminen',
        'description' => 'Tämä laajennus noudattaa:',
        'items' => [
            'gdpr' => 'Yleistä tietosuoja-asetusta (GDPR)',
            'chrome_store' => 'Chrome Web Store -käytäntöjä',
            'faceit_terms' => 'FACEIT API -käyttöehtoja',
        ],
    ],
];
