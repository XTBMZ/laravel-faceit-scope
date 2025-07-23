@extends('layouts.app')

@section('title', 'Politique de Confidentialité - Faceit Scope')

@section('content')
<!-- Header simple et professionnel -->
<div class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-white mb-4">Politique de Confidentialité</h1>
            <p class="text-lg text-gray-400">Extension Faceit Scope</p>
            <div class="mt-4 text-sm text-gray-500">
                Dernière mise à jour : {{ now()->format('d/m/Y') }}
            </div>
        </div>
    </div>
</div>

<!-- Contenu principal -->
<div class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-lg p-8 space-y-12">
            
            <!-- 1. Introduction -->
            <section>
                <h2 class="text-2xl font-semibold text-white mb-4">1. Introduction</h2>
                <div class="prose prose-gray prose-invert max-w-none">
                    <p class="text-gray-300 leading-relaxed">
                        Faceit Scope est une extension de navigateur qui analyse les matchs FACEIT CS2 pour afficher des statistiques et prédictions. 
                        Nous respectons votre vie privée et nous nous engageons à protéger vos données personnelles.
                    </p>
                </div>
            </section>

            <!-- 2. Données collectées -->
            <section>
                <h2 class="text-2xl font-semibold text-white mb-6">2. Données collectées</h2>
                
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-white mb-3">2.1 Données temporairement traitées (non stockées)</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li>• <strong>Pseudonymes FACEIT publics</strong> : Pseudonymes de gaming déjà affichés publiquement sur FACEIT, lus temporairement pour l'analyse</li>
                            <li>• <strong>Statistiques de jeu publiques</strong> : K/D, win rate, cartes jouées (via API publique FACEIT)</li>
                            <li>• <strong>IDs de match</strong> : Extraits de l'URL pour identifier les matchs à analyser</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-white mb-3">2.2 Données stockées localement (cache temporaire uniquement)</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li>• <strong>Résultats d'analyses</strong> : Stockés 5 minutes maximum sur votre appareil pour éviter les appels API répétitifs</li>
                            <li>• <strong>Préférences utilisateur</strong> : Paramètres de l'extension (notifications activées/désactivées)</li>
                        </ul>
                    </div>
                    
                    <div class="bg-blue-900/20 border border-blue-700 rounded-lg p-4">
                        <p class="text-blue-200 font-medium">
                            <strong>Important :</strong> Aucune donnée personnelle identifiable n'est collectée ou conservée. 
                            Toutes les données traitées sont déjà publiques sur FACEIT.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 3. Utilisation des données -->
            <section>
                <h2 class="text-2xl font-semibold text-white mb-4">3. Utilisation des données</h2>
                <p class="text-gray-300 mb-4">Les données collectées sont utilisées exclusivement pour :</p>
                <ul class="space-y-2 text-gray-300">
                    <li>• Afficher les statistiques des joueurs dans l'interface FACEIT</li>
                    <li>• Calculer les prédictions d'équipes gagnantes</li>
                    <li>• Recommander les meilleures/pires cartes par équipe</li>
                    <li>• Améliorer les performances via la mise en cache temporaire</li>
                </ul>
            </section>

            <!-- 4. Partage des données -->
            <section>
                <h2 class="text-2xl font-semibold text-white mb-6">4. Partage des données</h2>
                
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-white mb-3">4.1 Aucun partage avec des tiers</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li>• Nous ne vendons aucune donnée à des tiers</li>
                            <li>• Nous ne transférons aucune donnée personnelle</li>
                            <li>• Toutes les analyses sont effectuées localement dans votre navigateur</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-white mb-3">4.2 API FACEIT</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li>• L'extension utilise uniquement l'API publique officielle de FACEIT</li>
                            <li>• Aucune donnée privée ou sensible n'est collectée</li>
                            <li>• Toutes les statistiques utilisées sont publiquement accessibles</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 5. Sécurité et conservation -->
            <section>
                <h2 class="text-2xl font-semibold text-white mb-6">5. Sécurité et conservation</h2>
                
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-white mb-3">5.1 Stockage local uniquement</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li>• Toutes les données sont stockées localement sur votre appareil</li>
                            <li>• Aucune donnée n'est transmise à nos serveurs</li>
                            <li>• Le cache est automatiquement supprimé après 5 minutes</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-white mb-3">5.2 Accès limité</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li>• L'extension accède uniquement aux pages FACEIT que vous visitez</li>
                            <li>• Aucun accès à d'autres sites web ou données personnelles</li>
                            <li>• Aucun suivi de votre navigation</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 6. Vos droits -->
            <section>
                <h2 class="text-2xl font-semibold text-white mb-6">6. Vos droits</h2>
                
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-white mb-3">6.1 Contrôle des données</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li>• Vous pouvez vider le cache à tout moment via la popup de l'extension</li>
                            <li>• Vous pouvez désinstaller l'extension pour supprimer toutes les données</li>
                            <li>• Vous pouvez désactiver les notifications dans les paramètres</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-white mb-3">6.2 Données publiques</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li>• Toutes les données analysées sont déjà publiques sur FACEIT</li>
                            <li>• L'extension ne révèle aucune information privée</li>
                            <li>• Aucune donnée personnelle identifiable n'est collectée</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 7. Cookies et technologies de suivi -->
            <section>
                <h2 class="text-2xl font-semibold text-white mb-4">7. Cookies et technologies de suivi</h2>
                <p class="text-gray-300 mb-4">L'extension Faceit Scope :</p>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-red-400 font-medium mb-2">N'utilise PAS :</h4>
                        <ul class="space-y-1 text-gray-300 text-sm">
                            <li>• Aucun cookie</li>
                            <li>• Aucun suivi publicitaire</li>
                            <li>• Aucune analyse comportementale</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-green-400 font-medium mb-2">Utilise UNIQUEMENT :</h4>
                        <ul class="space-y-1 text-gray-300 text-sm">
                            <li>• Stockage local du navigateur</li>
                            <li>• Cache temporaire (5 min max)</li>
                            <li>• API publique FACEIT</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 8. Mises à jour de cette politique -->
            <section>
                <h2 class="text-2xl font-semibold text-white mb-4">8. Mises à jour de cette politique</h2>
                <p class="text-gray-300">
                    Nous pouvons mettre à jour cette politique de confidentialité. Les modifications seront publiées sur cette page 
                    avec une nouvelle date et notifiées via une mise à jour de l'extension si nécessaire.
                </p>
            </section>

            <!-- 9. Contact -->
            <section>
                <h2 class="text-2xl font-semibold text-white mb-4">9. Contact</h2>
                <p class="text-gray-300 mb-4">Pour toute question concernant cette politique de confidentialité :</p>
                <ul class="space-y-2 text-gray-300">
                    <li>• <strong>Site web :</strong> https://faceitscope.com</li>
                    <li>• <strong>Email :</strong> contact@faceitscope.com</li>
                </ul>
            </section>

            <!-- 10. Conformité réglementaire -->
            <section>
                <h2 class="text-2xl font-semibold text-white mb-4">10. Conformité réglementaire</h2>
                <p class="text-gray-300 mb-4">Cette extension respecte :</p>
                <ul class="space-y-2 text-gray-300">
                    <li>• Le Règlement Général sur la Protection des Données (RGPD)</li>
                    <li>• Les politiques du Chrome Web Store</li>
                    <li>• Les conditions d'utilisation de l'API FACEIT</li>
                </ul>
            </section>

        </div>
    </div>
</div>

<!-- Footer simple -->
<div class="py-8 border-t border-gray-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-gray-500 text-sm">
            <strong>Faceit Scope</strong> - Extension Chrome pour l'analyse de matchs FACEIT CS2
        </p>
    </div>
</div>
@endsection