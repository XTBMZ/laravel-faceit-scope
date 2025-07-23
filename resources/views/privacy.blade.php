@extends('layouts.app')

@section('title', __('privacy.title'))

@section('content')
<!-- Header minimaliste -->
<div class="py-20" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);">
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-white mb-2">{{ __('privacy.header.title') }}</h1>
            <p class="text-gray-400 mb-4">{{ __('privacy.header.subtitle') }}</p>
            <p class="text-sm text-gray-500">{{ __('privacy.header.last_updated') }}</p>
        </div>
    </div>
</div>

<!-- Contenu principal -->
<div style="background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);">
    <div class="max-w-5xl mx-auto px-6 py-16">
        <div class="prose prose-lg max-w-none">
            
            <!-- 1. Introduction -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">{{ __('privacy.introduction.title') }}</h2>
                <p class="text-gray-300 leading-relaxed">
                    {{ __('privacy.introduction.content') }}
                </p>
            </div>

            <!-- 2. Données collectées -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">{{ __('privacy.data_collected.title') }}</h2>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">{{ __('privacy.data_collected.temporary_data.title') }}</h3>
                    <ul class="space-y-3 text-gray-300">
                        <li><strong class="text-white">{{ __('privacy.data_collected.temporary_data.items.faceit_usernames.title') }}</strong> {{ __('privacy.data_collected.temporary_data.items.faceit_usernames.description') }}</li>
                        <li><strong class="text-white">{{ __('privacy.data_collected.temporary_data.items.public_stats.title') }}</strong> {{ __('privacy.data_collected.temporary_data.items.public_stats.description') }}</li>
                        <li><strong class="text-white">{{ __('privacy.data_collected.temporary_data.items.match_ids.title') }}</strong> {{ __('privacy.data_collected.temporary_data.items.match_ids.description') }}</li>
                    </ul>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">{{ __('privacy.data_collected.local_data.title') }}</h3>
                    <ul class="space-y-3 text-gray-300">
                        <li><strong class="text-white">{{ __('privacy.data_collected.local_data.items.analysis_results.title') }}</strong> {{ __('privacy.data_collected.local_data.items.analysis_results.description') }}</li>
                        <li><strong class="text-white">{{ __('privacy.data_collected.local_data.items.user_preferences.title') }}</strong> {{ __('privacy.data_collected.local_data.items.user_preferences.description') }}</li>
                    </ul>
                </div>
                
                <div class="bg-gray-800 border-l-4 border-gray-600 p-6 rounded-r-lg">
                    <p class="text-gray-200 font-medium">
                        {{ __('privacy.data_collected.important_note') }}
                    </p>
                </div>
            </div>

            <!-- 3. Utilisation des données -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">{{ __('privacy.data_usage.title') }}</h2>
                <p class="text-gray-300 mb-4">{{ __('privacy.data_usage.description') }}</p>
                <ul class="space-y-2 text-gray-300">
                    <li>{{ __('privacy.data_usage.items.display_stats') }}</li>
                    <li>{{ __('privacy.data_usage.items.predictions') }}</li>
                    <li>{{ __('privacy.data_usage.items.map_recommendations') }}</li>
                    <li>{{ __('privacy.data_usage.items.performance') }}</li>
                </ul>
            </div>

            <!-- 4. Partage des données -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">{{ __('privacy.data_sharing.title') }}</h2>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">{{ __('privacy.data_sharing.no_third_party.title') }}</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>{{ __('privacy.data_sharing.no_third_party.items.no_selling') }}</li>
                        <li>{{ __('privacy.data_sharing.no_third_party.items.no_transfer') }}</li>
                        <li>{{ __('privacy.data_sharing.no_third_party.items.local_analysis') }}</li>
                    </ul>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">{{ __('privacy.data_sharing.faceit_api.title') }}</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>{{ __('privacy.data_sharing.faceit_api.items.public_api') }}</li>
                        <li>{{ __('privacy.data_sharing.faceit_api.items.no_private_data') }}</li>
                        <li>{{ __('privacy.data_sharing.faceit_api.items.public_stats') }}</li>
                    </ul>
                </div>
            </div>

            <!-- 5. Sécurité et conservation -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">{{ __('privacy.security.title') }}</h2>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">{{ __('privacy.security.local_storage.title') }}</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>{{ __('privacy.security.local_storage.items.local_only') }}</li>
                        <li>{{ __('privacy.security.local_storage.items.no_server_transmission') }}</li>
                        <li>{{ __('privacy.security.local_storage.items.auto_delete') }}</li>
                    </ul>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">{{ __('privacy.security.limited_access.title') }}</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>{{ __('privacy.security.limited_access.items.faceit_only') }}</li>
                        <li>{{ __('privacy.security.limited_access.items.no_other_access') }}</li>
                        <li>{{ __('privacy.security.limited_access.items.no_tracking') }}</li>
                    </ul>
                </div>
            </div>

            <!-- 6. Vos droits -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">{{ __('privacy.user_rights.title') }}</h2>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">{{ __('privacy.user_rights.data_control.title') }}</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>{{ __('privacy.user_rights.data_control.items.clear_cache') }}</li>
                        <li>{{ __('privacy.user_rights.data_control.items.uninstall') }}</li>
                        <li>{{ __('privacy.user_rights.data_control.items.disable_notifications') }}</li>
                    </ul>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-medium text-white mb-4">{{ __('privacy.user_rights.public_data.title') }}</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>{{ __('privacy.user_rights.public_data.items.already_public') }}</li>
                        <li>{{ __('privacy.user_rights.public_data.items.no_private_info') }}</li>
                        <li>{{ __('privacy.user_rights.public_data.items.no_personal_data') }}</li>
                    </ul>
                </div>
            </div>

            <!-- 7. Cookies et technologies de suivi -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">{{ __('privacy.cookies.title') }}</h2>
                <p class="text-gray-300 mb-6">{{ __('privacy.cookies.description') }}</p>
                <div class="grid md:grid-cols-2 gap-12">
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">{{ __('privacy.cookies.does_not_use.title') }}</h4>
                        <ul class="space-y-2 text-gray-300">
                            <li>{{ __('privacy.cookies.does_not_use.items.no_cookies') }}</li>
                            <li>{{ __('privacy.cookies.does_not_use.items.no_ad_tracking') }}</li>
                            <li>{{ __('privacy.cookies.does_not_use.items.no_behavioral_analysis') }}</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">{{ __('privacy.cookies.uses_only.title') }}</h4>
                        <ul class="space-y-2 text-gray-300">
                            <li>{{ __('privacy.cookies.uses_only.items.local_storage') }}</li>
                            <li>{{ __('privacy.cookies.uses_only.items.temp_cache') }}</li>
                            <li>{{ __('privacy.cookies.uses_only.items.public_api') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 8. Mises à jour de cette politique -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">{{ __('privacy.policy_updates.title') }}</h2>
                <p class="text-gray-300">
                    {{ __('privacy.policy_updates.content') }}
                </p>
            </div>

            <!-- 9. Contact -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">{{ __('privacy.contact.title') }}</h2>
                <p class="text-gray-300 mb-4">{{ __('privacy.contact.description') }}</p>
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-gray-300"><strong class="text-white">{{ __('privacy.contact.website') }}</strong> https://faceitscope.com</p>
                    </div>
                    <div>
                        <p class="text-gray-300"><strong class="text-white">{{ __('privacy.contact.email') }}</strong> support@faceitscope.com</p>
                    </div>
                </div>
            </div>

            <!-- 10. Conformité réglementaire -->
            <div class="mb-16">
                <h2 class="text-2xl font-semibold text-white mb-6">{{ __('privacy.compliance.title') }}</h2>
                <p class="text-gray-300 mb-4">{{ __('privacy.compliance.description') }}</p>
                <ul class="space-y-2 text-gray-300">
                    <li>{{ __('privacy.compliance.items.gdpr') }}</li>
                    <li>{{ __('privacy.compliance.items.chrome_store') }}</li>
                    <li>{{ __('privacy.compliance.items.faceit_terms') }}</li>
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection