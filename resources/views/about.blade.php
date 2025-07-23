@extends('layouts.app')

@section('title', __('about.title'))

@section('content')
<!-- Hero Section -->
<div class="relative py-16" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-white mb-4">
                    {{ __('about.hero.title') }}
                </h1>
                <div class="w-20 h-1 bg-faceit-orange mx-auto"></div>
            </div>
            <p class="text-lg text-gray-400 max-w-3xl mx-auto font-light leading-relaxed">
                {{ __('about.hero.subtitle') }}
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
                    <h2 class="text-3xl font-bold text-white mb-6">{{ __('about.project.title') }}</h2>
                    <div class="space-y-6 text-base text-gray-300 leading-relaxed">
                        <p>
                            <span class="font-semibold text-white">Faceit Scope</span> {{ __('about.project.description_1') }}
                        </p>
                        <p>
                            {{ __('about.project.description_2') }} <span class="font-semibold text-faceit-orange">XTBMZ</span>, 
                            {{ __('about.project.description_3') }}
                        </p>
                        <p>
                            {{ __('about.project.description_4') }}
                        </p>
                    </div>
                </div>
                <div class="bg-gray-800 rounded-3xl p-8 border border-gray-700" style="background: linear-gradient(145deg, #2d2d2d 0%, #181818 100%);">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="text-xl font-bold text-faceit-orange mb-2">{{ $stats['developer'] }}</div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider">{{ __('about.project.stats.developer') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-white mb-2">100%</div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider">{{ __('about.project.stats.api') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Algorithmes mise à jour -->
        <div class="mb-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-4">{{ __('about.how_it_works.title') }}</h2>
                <div class="w-20 h-1 bg-faceit-orange mx-auto mb-6"></div>
                <p class="text-lg text-gray-300 max-w-3xl mx-auto">
                    {{ __('about.how_it_works.subtitle') }}
                </p>
            </div>

            <div class="space-y-16">
                <!-- Algorithme 1: Player Impact Score (PIS) -->
                <div class="rounded-3xl p-8 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <h3 class="text-xl font-bold text-white mb-6 text-center">{{ __('about.how_it_works.pis.title') }}</h3>
                    
                    <div class="grid lg:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="w-14 h-14 bg-red-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-crosshairs text-red-400 text-lg"></i>
                            </div>
                            <h4 class="font-semibold text-white mb-3 text-base">{{ __('about.how_it_works.pis.combat.title') }}</h4>
                            <p class="text-gray-300 text-sm leading-relaxed mb-3">
                                {{ __('about.how_it_works.pis.combat.description') }}
                            </p>
                            <div class="text-xs font-mono bg-gray-700 text-gray-200 p-3 rounded-lg border border-gray-600">
                                kdScore = min((kd - 0.5) / 1.5, 1)<br>
                                adrScore = min((adr - 60) / 80, 1)<br>
                                hsScore = min((hs - 30) / 30, 1)
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="w-14 h-14 bg-purple-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-brain text-purple-400 text-lg"></i>
                            </div>
                            <h4 class="font-semibold text-white mb-3 text-base">{{ __('about.how_it_works.pis.game_sense.title') }}</h4>
                            <p class="text-gray-300 text-sm leading-relaxed mb-3">
                                {{ __('about.how_it_works.pis.game_sense.description') }}
                            </p>
                            <div class="text-xs bg-gray-700 text-gray-200 p-3 rounded-lg border border-gray-600 space-y-1">
                                <div>entryEfficiency = entryRate × entrySuccess</div>
                                <div>clutchScore = (1v1 × 2) + 1v2</div>
                                <div>sniperScore = sniperRate × 5</div>
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="w-14 h-14 bg-blue-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-sun text-blue-400 text-lg"></i>
                            </div>
                            <h4 class="font-semibold text-white mb-3 text-base">{{ __('about.how_it_works.pis.utility.title') }}</h4>
                            <p class="text-gray-300 text-sm leading-relaxed mb-3">
                                {{ __('about.how_it_works.pis.utility.description') }}
                            </p>
                            <div class="text-xs font-mono bg-gray-700 text-gray-200 p-3 rounded-lg border border-gray-600">
                                utilityEfficiency = flashSuccess × flashesPerRound<br>
                                + utilitySuccess × 0.5
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="w-14 h-14 bg-green-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-line text-green-400 text-lg"></i>
                            </div>
                            <h4 class="font-semibold text-white mb-3 text-base">{{ __('about.how_it_works.pis.consistency.title') }}</h4>
                            <p class="text-gray-300 text-sm leading-relaxed mb-3">
                                {{ __('about.how_it_works.pis.consistency.description') }}
                            </p>
                            <div class="text-xs bg-gray-700 text-gray-200 p-3 rounded-lg border border-gray-600 space-y-1">
                                <div>consistency = (winRate/100) × log10(matches+1)</div>
                                <div>experience = min(log10(matches+1)/3, 1)</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 bg-faceit-orange/10 border border-faceit-orange/30 rounded-xl p-4">
                        <h5 class="font-semibold text-faceit-orange mb-2">{{ __('about.how_it_works.pis.level_coefficient.title') }}</h5>
                        <div class="text-sm text-gray-300">
                            <span class="font-mono">levelCoeff = 0.5 + (level/10)^1.5 × 1.0 + (elo-800)/2400 × 1.0</span><br>
                            {{ __('about.how_it_works.pis.level_coefficient.description') }}
                        </div>
                    </div>
                </div>

                <!-- Algorithme 2: Attribution des rôles -->
                <div class="rounded-3xl p-8 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <h3 class="text-xl font-bold text-white mb-6 text-center">{{ __('about.how_it_works.roles.title') }}</h3>
                    
                    <div class="grid lg:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-lg font-semibold text-white mb-4">{{ __('about.how_it_works.roles.calculations_title') }}</h4>
                            <div class="space-y-3">
                                <div class="bg-red-500/10 border border-red-500/30 p-4 rounded-xl">
                                    <h5 class="font-semibold text-red-400 mb-2">{{ __('about.how_it_works.roles.entry_fragger.title') }}</h5>
                                    <div class="text-xs font-mono text-gray-300">
                                        score = (entryRate × 200) + (entrySuccess × 100) + (offensivePotential × 15)
                                    </div>
                                    <div class="text-xs text-gray-400 mt-2">
                                        {{ __('about.how_it_works.roles.entry_fragger.criteria') }}
                                    </div>
                                </div>
                                
                                <div class="bg-blue-500/10 border border-blue-500/30 p-4 rounded-xl">
                                    <h5 class="font-semibold text-blue-400 mb-2">{{ __('about.how_it_works.roles.support.title') }}</h5>
                                    <div class="text-xs font-mono text-gray-300">
                                        score = (flashesPerRound × 150) + (flashSuccess × 100) + (utilitySuccess × 50)
                                    </div>
                                    <div class="text-xs text-gray-400 mt-2">
                                        {{ __('about.how_it_works.roles.support.criteria') }}
                                    </div>
                                </div>
                                
                                <div class="bg-purple-500/10 border border-purple-500/30 p-4 rounded-xl">
                                    <h5 class="font-semibold text-purple-400 mb-2">{{ __('about.how_it_works.roles.awper.title') }}</h5>
                                    <div class="text-xs font-mono text-gray-300">
                                        score = (sniperRate × 500) + (adr × 0.3) + ((kd-1) × 30)
                                    </div>
                                    <div class="text-xs text-gray-400 mt-2">
                                        {{ __('about.how_it_works.roles.awper.criteria') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold text-white mb-4">{{ __('about.how_it_works.roles.priority_title') }}</h4>
                            <div class="space-y-3">
                                <div class="flex items-center p-3 bg-faceit-elevated/30 rounded-lg">
                                    <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xs mr-3">1</div>
                                    <span class="text-white font-medium">{{ __('about.how_it_works.roles.priority_items.awper') }}</span>
                                </div>
                                <div class="flex items-center p-3 bg-faceit-elevated/30 rounded-lg">
                                    <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white font-bold text-xs mr-3">2</div>
                                    <span class="text-white font-medium">{{ __('about.how_it_works.roles.priority_items.entry') }}</span>
                                </div>
                                <div class="flex items-center p-3 bg-faceit-elevated/30 rounded-lg">
                                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xs mr-3">3</div>
                                    <span class="text-white font-medium">{{ __('about.how_it_works.roles.priority_items.support') }}</span>
                                </div>
                                <div class="flex items-center p-3 bg-faceit-elevated/30 rounded-lg">
                                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-xs mr-3">4</div>
                                    <span class="text-white font-medium">{{ __('about.how_it_works.roles.priority_items.clutcher') }}</span>
                                </div>
                                <div class="flex items-center p-3 bg-faceit-elevated/30 rounded-lg">
                                    <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-xs mr-3">5</div>
                                    <span class="text-white font-medium">{{ __('about.how_it_works.roles.priority_items.fragger') }}</span>
                                </div>
                                <div class="flex items-center p-3 bg-faceit-elevated/30 rounded-lg">
                                    <div class="w-6 h-6 bg-gray-500 rounded-full flex items-center justify-center text-white font-bold text-xs mr-3">6</div>
                                    <span class="text-white font-medium">{{ __('about.how_it_works.roles.priority_items.lurker') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Algorithme 3: Analyse des cartes -->
                <div class="rounded-3xl p-8 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <h3 class="text-xl font-bold text-white mb-6 text-center">{{ __('about.how_it_works.maps.title') }}</h3>
                    
                    <div class="grid lg:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <span class="text-blue-400 font-bold text-lg">1</span>
                            </div>
                            <h4 class="font-semibold text-white mb-3">{{ __('about.how_it_works.maps.normalization.title') }}</h4>
                            <div class="text-xs bg-gray-700 text-gray-200 p-3 rounded-lg border border-gray-600 space-y-1">
                                <div>winRate: (winRate - 30) / 40</div>
                                <div>kd: log10(kd × 10) / log10(20)</div>
                                <div>adr: (adr - 50) / 100</div>
                                <div>hs: (hs - 25) / 35</div>
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <span class="text-green-400 font-bold text-lg">2</span>
                            </div>
                            <h4 class="font-semibold text-white mb-3">{{ __('about.how_it_works.maps.weighting.title') }}</h4>
                            <div class="text-xs bg-gray-700 text-gray-200 p-3 rounded-lg border border-gray-600 space-y-1">
                                <div><span class="text-green-400">{{ __('about.how_it_works.maps.weighting.win_rate') }}</span> 40%</div>
                                <div><span class="text-orange-400">K/D:</span> 25%</div>
                                <div><span class="text-blue-400">ADR:</span> 20%</div>
                                <div><span class="text-purple-400">Headshots:</span> 10%</div>
                                <div><span class="text-gray-400">{{ __('about.how_it_works.maps.weighting.consistency') }}</span> 5%</div>
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <span class="text-yellow-400 font-bold text-lg">3</span>
                            </div>
                            <h4 class="font-semibold text-white mb-3">{{ __('about.how_it_works.maps.reliability.title') }}</h4>
                            <div class="text-xs bg-gray-700 text-gray-200 p-3 rounded-lg border border-gray-600">
                                reliability = min(log10(matches + 1) / log10(11), 1)<br><br>
                                <span class="text-yellow-400">finalScore = compositeScore × reliability</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Algorithme 4: Prédictions de match -->
                <div class="rounded-3xl p-8 border border-gray-700" style="background: linear-gradient(135deg, #2a2a2a 0%, #151515 100%);">
                    <h3 class="text-xl font-bold text-white mb-6 text-center">{{ __('about.how_it_works.predictions.title') }}</h3>
                    
                    <div class="grid lg:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-lg font-semibold text-white mb-4">{{ __('about.how_it_works.predictions.team_strength.title') }}</h4>
                            <div class="space-y-4">
                                <div class="bg-faceit-elevated/30 p-4 rounded-xl">
                                    <h5 class="font-semibold text-white mb-2">{{ __('about.how_it_works.predictions.team_strength.average_score.title') }}</h5>
                                    <div class="text-sm text-gray-300 mb-2">
                                        {{ __('about.how_it_works.predictions.team_strength.average_score.description') }}
                                    </div>
                                    <div class="text-xs font-mono text-gray-300">
                                        teamStrength = avgPIS + (roleBalance × 2)
                                    </div>
                                </div>
                                
                                <div class="bg-faceit-elevated/30 p-4 rounded-xl">
                                    <h5 class="font-semibold text-white mb-2">{{ __('about.how_it_works.predictions.team_strength.role_balance.title') }}</h5>
                                    <div class="text-xs text-gray-300">
                                        {{ __('about.how_it_works.predictions.team_strength.role_balance.description') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold text-white mb-4">{{ __('about.how_it_works.predictions.probability.title') }}</h4>
                            <div class="space-y-4">
                                <div class="bg-faceit-elevated/30 p-4 rounded-xl">
                                    <h5 class="font-semibold text-white mb-2">{{ __('about.how_it_works.predictions.probability.match_winner.title') }}</h5>
                                    <div class="text-xs font-mono text-gray-300 mb-2">
                                        probabilité = 50 + (différence_force × 8)
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ __('about.how_it_works.predictions.probability.match_winner.description') }}
                                    </div>
                                </div>
                                
                                <div class="bg-faceit-elevated/30 p-4 rounded-xl">
                                    <h5 class="font-semibold text-white mb-2">{{ __('about.how_it_works.predictions.probability.predicted_mvp.title') }}</h5>
                                    <div class="text-xs text-gray-300">
                                        {{ __('about.how_it_works.predictions.probability.predicted_mvp.description') }} <span class="text-faceit-orange font-semibold">{{ __('about.how_it_works.predictions.probability.predicted_mvp.highest_score') }}</span> {{ __('about.how_it_works.predictions.probability.predicted_mvp.description_end') }}
                                    </div>
                                </div>
                                
                                <div class="bg-faceit-elevated/30 p-4 rounded-xl">
                                    <h5 class="font-semibold text-white mb-2">{{ __('about.how_it_works.predictions.probability.confidence.title') }}</h5>
                                    <div class="text-xs text-gray-300">
                                        {{ __('about.how_it_works.predictions.probability.confidence.description') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Contact -->
        <div class="text-center">
            <h2 class="text-4xl font-bold text-white mb-4">{{ __('about.contact.title') }}</h2>
            <div class="w-24 h-1 bg-faceit-orange mx-auto mb-6"></div>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                {{ __('about.contact.subtitle') }}
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
            <strong>Disclaimer:</strong> {{ __('about.disclaimer.text') }}
        </p>
    </div>
</div>
@endsection