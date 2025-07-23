@extends('layouts.app')

@section('title', 'À propos - Faceit Scope')

@section('content')
<!-- Hero Section -->
<div class="relative py-16" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-white mb-4">
                    À propos
                </h1>
                <div class="w-20 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            <p class="text-lg text-gray-400 max-w-3xl mx-auto font-light leading-relaxed">
                Faceit Scope analyse vos performances FACEIT grâce à des algorithmes avancés 
                et l'intelligence artificielle. Un projet solo développé avec passion.
            </p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        <!-- Section Projet -->
        <div class="mb-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-6">Le projet</h2>
                    <div class="space-y-6 text-base text-gray-300 leading-relaxed">
                        <p>
                            <span class="font-semibold text-white">Faceit Scope</span> est né d'une frustration : 
                            l'interface FACEIT ne permet pas d'analyser en profondeur ses performances.
                        </p>
                        <p>
                            Développé entièrement par <span class="font-semibold text-faceit-orange">XTBMZ</span>, 
                            ce projet utilise exclusivement l'API officielle FACEIT pour récupérer 
                            toutes les données de manière transparente et légale.
                        </p>
                        <p>
                            Aucune donnée n'est inventée ou modifiée. Tout provient directement 
                            des serveurs FACEIT et est analysé par nos algorithmes propriétaires.
                        </p>
                    </div>
                </div>
                <div class="bg-gray-800 rounded-3xl p-8 border border-gray-700" style="background: linear-gradient(145deg, #2d2d2d 0%, #181818 100%);">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="text-xl font-bold text-white mb-2">{{ $stats['status'] }}</div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider">Statut</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-faceit-orange mb-2">{{ $stats['developer'] }}</div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider">Développeur</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-white mb-2">100%</div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider">API FACEIT</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-white mb-2">{{ $stats['algorithms_active'] }}</div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider">Algorithmes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Algorithmes -->
        <div class="mb-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-4">Comment ça fonctionne</h2>
                <div class="w-20 h-1 bg-faceit-orange mx-auto mb-6"></div>
                <p class="text-lg text-gray-300 max-w-3xl mx-auto">
                    Des algorithmes sophistiqués analysent vos données FACEIT pour vous donner des insights précis
                </p>
            </div>

            <div class="space-y-16">
                <!-- Algorithme 1: Analyse des cartes -->
                <div class="rounded-3xl p-8 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <h3 class="text-xl font-bold text-white mb-6 text-center">Analyse des meilleures/pires cartes</h3>
                    
                    <div class="grid lg:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <span class="text-gray-900 font-bold text-lg">1</span>
                            </div>
                            <h4 class="font-semibold text-white mb-3 text-base">Normalisation</h4>
                            <p class="text-gray-300 text-sm leading-relaxed">
                                Conversion de toutes les statistiques sur une échelle de 0 à 1 pour permettre la comparaison
                            </p>
                            <div class="mt-4 text-xs font-mono bg-gray-700 text-gray-200 p-3 rounded-lg border border-gray-600">
                                K/D : 0.8→1.6 = 0→1<br>
                                Win Rate : 40%→60% = 0→1
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <span class="text-gray-900 font-bold text-lg">2</span>
                            </div>
                            <h4 class="font-semibold text-white mb-3 text-base">Pondération</h4>
                            <p class="text-gray-300 text-sm leading-relaxed">
                                Attribution d'un poids différent à chaque statistique selon son importance
                            </p>
                            <div class="mt-4 text-xs bg-gray-700 text-gray-200 p-3 rounded-lg border border-gray-600 space-y-1">
                                <div>Win Rate: <span class="font-semibold text-white">2.0</span></div>
                                <div>K/D Ratio: <span class="font-semibold text-white">1.8</span></div>
                                <div>ADR: <span class="font-semibold text-white">1.6</span></div>
                                <div>Headshots: <span class="font-semibold text-white">0.8</span></div>
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <span class="text-gray-900 font-bold text-lg">3</span>
                            </div>
                            <h4 class="font-semibold text-white mb-3 text-base">Confiance</h4>
                            <p class="text-gray-300 text-sm leading-relaxed">
                                Pénalisation des cartes avec peu de matchs pour éviter les biais statistiques
                            </p>
                            <div class="mt-4 text-xs font-mono bg-gray-700 text-gray-200 p-3 rounded-lg border border-gray-600">
                                confiance = min(1, log10(matches + 1))
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Algorithme 2: Analyse de lobby -->
                <div>
                    <h3 class="text-2xl font-bold text-white mb-6 text-center">Analyse de lobby (10 joueurs)</h3>
                    
                    <div class="grid lg:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-semibold text-white mb-4">Player Impact Score (PIS)</h4>
                            <div class="space-y-4">
                                <div class="bg-gray-700 p-4 rounded-2xl border border-gray-600" style="background-color: #262626;">
                                    <h5 class="font-semibold text-white mb-3">Correction par niveau FACEIT</h5>
                                    <p class="text-gray-300 text-sm mb-3">
                                        Un Level 10 avec 1.1 K/D vaut plus qu'un Level 3 avec 1.4 K/D
                                    </p>
                                    <code class="text-xs font-mono bg-gray-700 text-gray-200 px-3 py-2 rounded border border-gray-600">
                                        correction = 1 + log10(level) / 2
                                    </code>
                                </div>
                                
                                <div class="bg-gray-700 p-4 rounded-2xl border border-gray-600" style="background-color: #262626;">
                                    <h5 class="font-semibold text-white mb-3">Attribution des rôles</h5>
                                    <div class="space-y-2 text-sm text-gray-300">
                                        <div>Entry Rate élevé → <span class="font-semibold text-white">Entry Fragger</span></div>
                                        <div>Flash Success → <span class="font-semibold text-white">Support</span></div>
                                        <div>1vX Win Rate → <span class="font-semibold text-white">Clutcher</span></div>
                                        <div>Sniper Rate → <span class="font-semibold text-white">AWPer</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xl font-semibold text-white mb-4">Prédictions générées</h4>
                            <div class="space-y-3">
                                <div class="flex items-center p-3 rounded-2xl border border-gray-700" style="background-color: #202020;">
                                    <div class="w-3 h-3 bg-white rounded-full mr-4"></div>
                                    <span class="text-white font-medium">Équipe gagnante avec probabilités</span>
                                </div>
                                <div class="flex items-center p-3 rounded-2xl border border-gray-700" style="background-color: #202020;">
                                    <div class="w-3 h-3 bg-white rounded-full mr-4"></div>
                                    <span class="text-white font-medium">MVP prédit basé sur PIS et rôle</span>
                                </div>
                                <div class="flex items-center p-3 rounded-2xl border border-gray-700" style="background-color: #202020;">
                                    <div class="w-3 h-3 bg-white rounded-full mr-4"></div>
                                    <span class="text-white font-medium">Joueurs clés identifiés par rôle</span>
                                </div>
                                <div class="flex items-center p-3 rounded-2xl border border-gray-700" style="background-color: #202020;">
                                    <div class="w-3 h-3 bg-white rounded-full mr-4"></div>
                                    <span class="text-white font-medium">Facteurs déterminants du match</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Contact -->
        <div class="text-center">
            <h2 class="text-4xl font-bold text-white mb-4">Contact</h2>
            <div class="w-24 h-1 bg-faceit-orange mx-auto mb-6"></div>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                Un projet solo développé avec passion. N'hésitez pas à me contacter pour vos retours ou suggestions.
            </p>
            
            <div class="flex justify-center space-x-8">
                <div class="rounded-2xl px-8 py-6 flex items-center border border-gray-700" style="background-color: #222;">
                    <i class="fab fa-steam text-2xl mr-4 text-gray-300"></i>
                    <div class="text-left">
                        <div class="text-gray-400">XTBMZ</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer disclaimer -->
<div class="py-8 border-t border-gray-700" style="background: linear-gradient(180deg, #0d0d0d 0%, #000000 100%);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-gray-400 text-sm leading-relaxed">
            <strong>Disclaimer:</strong> Faceit Scope n'est pas affilié à FACEIT Ltd. 
            Ce projet utilise l'API publique FACEIT dans le respect de leurs conditions d'utilisation. 
            Les algorithmes de prédiction sont basés sur des analyses statistiques et ne garantissent pas les résultats des matchs.
        </p>
    </div>
</div>
@endsection