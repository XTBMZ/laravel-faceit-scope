@extends('layouts.app')

@section('title', 'Politique de Confidentialité - Faceit Scope')

@section('content')
<!-- Header minimaliste -->
<div class="py-20" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);">
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-white mb-2">Politique de Confidentialité</h1>
            <p class="text-gray-400 mb-4">Extension Faceit Scope</p>
            <p class="text-sm text-gray-500">Dernière mise à jour : 23 juillet 2025</p>
        </div>
    </div>
</div>

<!-- Contenu principal -->
<div style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="max-w-5xl mx-auto px-6 py-16">
        <div class="prose prose-lg max-w-none">
            
            <!-- 1. Introduction -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">1. Introduction</h2>
                <p class="text-gray-300 leading-relaxed">
                    Faceit Scope est une extension de navigateur qui analyse les matchs FACEIT CS2 pour afficher des statistiques et prédictions. 
                    Nous respectons votre vie privée et nous nous engageons à protéger vos données personnelles.
                </p>
            </div>

            <!-- 2. Données collectées -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">2. Données collectées</h2>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">2.1 Données temporairement traitées (non stockées)</h3>
                    <ul class="space-y-3 text-gray-300">
                        <li><strong class="text-white">Pseudonymes FACEIT publics :</strong> Pseudonymes de gaming déjà affichés publiquement sur FACEIT, lus temporairement pour l'analyse</li>
                        <li><strong class="text-white">Statistiques de jeu publiques :</strong> K/D, win rate, cartes jouées (via API publique FACEIT)</li>
                        <li><strong class="text-white">IDs de match :</strong> Extraits de l'URL pour identifier les matchs à analyser</li>
                    </ul>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">2.2 Données stockées localement (cache temporaire uniquement)</h3>
                    <ul class="space-y-3 text-gray-300">
                        <li><strong class="text-white">Résultats d'analyses :</strong> Stockés 5 minutes maximum sur votre appareil pour éviter les appels API répétitifs</li>
                        <li><strong class="text-white">Préférences utilisateur :</strong> Paramètres de l'extension (notifications activées/désactivées)</li>
                    </ul>
                </div>
                
                <div class="bg-gray-800 border-l-4 border-gray-600 p-6 rounded-r-lg">
                    <p class="text-gray-200 font-medium">
                        Important : Aucune donnée personnelle identifiable n'est collectée ou conservée. 
                        Toutes les données traitées sont déjà publiques sur FACEIT.
                    </p>
                </div>
            </div>

            <!-- 3. Utilisation des données -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">3. Utilisation des données</h2>
                <p class="text-gray-300 mb-4">Les données collectées sont utilisées exclusivement pour :</p>
                <ul class="space-y-2 text-gray-300">
                    <li>Afficher les statistiques des joueurs dans l'interface FACEIT</li>
                    <li>Calculer les prédictions d'équipes gagnantes</li>
                    <li>Recommander les meilleures/pires cartes par équipe</li>
                    <li>Améliorer les performances via la mise en cache temporaire</li>
                </ul>
            </div>

            <!-- 4. Partage des données -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">4. Partage des données</h2>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">4.1 Aucun partage avec des tiers</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>Nous ne vendons aucune donnée à des tiers</li>
                        <li>Nous ne transférons aucune donnée personnelle</li>
                        <li>Toutes les analyses sont effectuées localement dans votre navigateur</li>
                    </ul>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">4.2 API FACEIT</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>L'extension utilise uniquement l'API publique officielle de FACEIT</li>
                        <li>Aucune donnée privée ou sensible n'est collectée</li>
                        <li>Toutes les statistiques utilisées sont publiquement accessibles</li>
                    </ul>
                </div>
            </div>

            <!-- 5. Sécurité et conservation -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">5. Sécurité et conservation</h2>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">5.1 Stockage local uniquement</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>Toutes les données sont stockées localement sur votre appareil</li>
                        <li>Aucune donnée n'est transmise à nos serveurs</li>
                        <li>Le cache est automatiquement supprimé après 5 minutes</li>
                    </ul>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">5.2 Accès limité</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>L'extension accède uniquement aux pages FACEIT que vous visitez</li>
                        <li>Aucun accès à d'autres sites web ou données personnelles</li>
                        <li>Aucun suivi de votre navigation</li>
                    </ul>
                </div>
            </div>

            <!-- 6. Vos droits -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">6. Vos droits</h2>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">6.1 Contrôle des données</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>Vous pouvez vider le cache à tout moment via la popup de l'extension</li>
                        <li>Vous pouvez désinstaller l'extension pour supprimer toutes les données</li>
                        <li>Vous pouvez désactiver les notifications dans les paramètres</li>
                    </ul>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">6.2 Données publiques</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>Toutes les données analysées sont déjà publiques sur FACEIT</li>
                        <li>L'extension ne révèle aucune information privée</li>
                        <li>Aucune donnée personnelle identifiable n'est collectée</li>
                    </ul>
                </div>
            </div>

            <!-- 7. Cookies et technologies de suivi -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">7. Cookies et technologies de suivi</h2>
                <p class="text-gray-300 mb-6">L'extension Faceit Scope :</p>
                <div class="grid md:grid-cols-2 gap-12">
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">N'utilise pas :</h4>
                        <ul class="space-y-2 text-gray-300">
                            <li>Aucun cookie</li>
                            <li>Aucun suivi publicitaire</li>
                            <li>Aucune analyse comportementale</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Utilise uniquement :</h4>
                        <ul class="space-y-2 text-gray-300">
                            <li>Stockage local du navigateur</li>
                            <li>Cache temporaire (5 minutes maximum)</li>
                            <li>API publique FACEIT</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 8. Mises à jour de cette politique -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">8. Mises à jour de cette politique</h2>
                <p class="text-gray-300">
                    Nous pouvons mettre à jour cette politique de confidentialité. Les modifications seront publiées sur cette page 
                    avec une nouvelle date et notifiées via une mise à jour de l'extension si nécessaire.
                </p>
            </div>

            <!-- 9. Contact -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">9. Contact</h2>
                <p class="text-gray-300 mb-4">Pour toute question concernant cette politique de confidentialité :</p>
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-gray-300"><strong class="text-white">Site web :</strong> https://faceitscope.com</p>
                    </div>
                    <div>
                        <p class="text-gray-300"><strong class="text-white">Email :</strong> support@faceitscope.com</p>
                    </div>
                </div>
            </div>

            <!-- 10. Conformité réglementaire -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">10. Conformité réglementaire</h2>
                <p class="text-gray-300 mb-4">Cette extension respecte :</p>
                <ul class="space-y-2 text-gray-300">
                    <li>Le Règlement Général sur la Protection des Données (RGPD)</li>
                    <li>Les politiques du Chrome Web Store</li>
                    <li>Les conditions d'utilisation de l'API FACEIT</li>
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection