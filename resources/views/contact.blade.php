@extends('layouts.app')

@section('title', __('contact.title'))

@section('content')

<div class="relative py-14" style="background: linear-gradient(135deg, #1a1a1a 0%, #242424 100%);">
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center">
            <h1 class="text-4xl font-light text-white mb-4 tracking-wide">
                {{ __('contact.hero.title') }}
            </h1>
            <div class="w-16 h-px bg-faceit-orange mx-auto mb-3"></div>
        </div>
    </div>
</div>


<div class="pb-14 pt-6" style="background: linear-gradient(180deg, #1e1e1e 0%, #191919 100%);">
    <div class="max-w-6xl mx-auto px-6">

        
        <div id="flashMessages" class="mb-8"></div>

        <div class="grid lg:grid-cols-10 gap-12">
            
            
            <div class="lg:col-span-2 lg:-ml-8">
                <div class="space-y-8">
                    
                    
                    <div class="p-8 rounded-lg border border-gray-700/50" style="background: rgba(255, 255, 255, 0.02);">
                        <h3 class="text-xl font-light text-white mb-6 tracking-wide">{{ __('contact.sidebar.developer.title') }}</h3>
                        <div class="space-y-4 text-gray-400">
                            <div class="flex items-center justify-between">
                                <span class="text-sm uppercase tracking-wider">{{ __('contact.sidebar.developer.name_label') }}</span>
                                <span class="text-white font-medium">{{ __('contact.sidebar.developer.name_value') }}</span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="p-8 rounded-lg border border-gray-700/50" style="background: rgba(255, 255, 255, 0.02);">
                        <h3 class="text-xl font-light text-white mb-6 tracking-wide">{{ __('contact.sidebar.response.title') }}</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-gray-400">
                                <span class="text-sm uppercase tracking-wider">{{ __('contact.sidebar.response.average_delay') }}</span>
                                <span class="text-white font-medium">{{ __('contact.sidebar.response.delay_value') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-8">
                <div class="p-10 rounded-xl border border-gray-700/50" style="background: rgba(255, 255, 255, 0.03);">
                    <form id="contactForm" class="space-y-8">
                        @csrf
                        
                        
                        <div class="grid md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm uppercase tracking-widest text-gray-400 mb-4">
                                    {{ __('contact.form.type.label') }} <span class="text-faceit-orange">{{ __('contact.form.type.required') }}</span>
                                </label>
                                <select 
                                    id="type" 
                                    name="type" 
                                    required
                                    class="w-full bg-gray-800/50 border border-gray-600 rounded-lg text-white px-4 py-3 focus:outline-none focus:border-faceit-orange focus:ring-1 focus:ring-faceit-orange transition-all appearance-none"
                                >
                                    <option value="" class="bg-gray-900">{{ __('contact.form.type.placeholder') }}</option>
                                    <option value="bug" class="bg-gray-900">{{ __('contact.form.type.options.bug') }}</option>
                                    <option value="suggestion" class="bg-gray-900">{{ __('contact.form.type.options.suggestion') }}</option>
                                    <option value="question" class="bg-gray-900">{{ __('contact.form.type.options.question') }}</option>
                                    <option value="feedback" class="bg-gray-900">{{ __('contact.form.type.options.feedback') }}</option>
                                    <option value="other" class="bg-gray-900">{{ __('contact.form.type.options.other') }}</option>
                                </select>
                                <div class="text-red-400 text-sm mt-2 hidden" id="type-error"></div>
                            </div>

                            <div>
                                <label class="block text-sm uppercase tracking-widest text-gray-400 mb-4">
                                    {{ __('contact.form.subject.label') }} <span class="text-faceit-orange">{{ __('contact.form.subject.required') }}</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="subject" 
                                    name="subject" 
                                    required
                                    maxlength="255"
                                    class="w-full bg-gray-800/50 border border-gray-600 rounded-lg text-white px-4 py-3 placeholder-gray-500 focus:outline-none focus:border-faceit-orange focus:ring-1 focus:ring-faceit-orange transition-all"
                                >
                                <div class="text-red-400 text-sm mt-2 hidden" id="subject-error"></div>
                            </div>
                        </div>

                        
                        <div class="grid md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm uppercase tracking-widest text-gray-400 mb-4">
                                    {{ __('contact.form.email.label') }} <span class="text-faceit-orange">{{ __('contact.form.email.required') }}</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    required
                                    class="w-full bg-gray-800/50 border border-gray-600 rounded-lg text-white px-4 py-3 placeholder-gray-500 focus:outline-none focus:border-faceit-orange focus:ring-1 focus:ring-faceit-orange transition-all"
                                >
                                <div class="text-red-400 text-sm mt-2 hidden" id="email-error"></div>
                            </div>
                            
                            <div>
                                <label class="block text-sm uppercase tracking-widest text-gray-400 mb-4">
                                    {{ __('contact.form.pseudo.label') }} <span class="text-gray-600 text-xs lowercase normal-case">{{ __('contact.form.pseudo.optional') }}</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="pseudo" 
                                    name="pseudo" 
                                    maxlength="100"
                                    class="w-full bg-gray-800/50 border border-gray-600 rounded-lg text-white px-4 py-3 placeholder-gray-500 focus:outline-none focus:border-faceit-orange focus:ring-1 focus:ring-faceit-orange transition-all"
                                >
                                <div class="text-red-400 text-sm mt-2 hidden" id="pseudo-error"></div>
                            </div>
                        </div>

                        
                        <div>
                            <label class="block text-sm uppercase tracking-widest text-gray-400 mb-4">
                                {{ __('contact.form.message.label') }} <span class="text-faceit-orange">{{ __('contact.form.message.required') }}</span>
                            </label>
                            <textarea 
                                id="message" 
                                name="message" 
                                required
                                rows="7"
                                minlength="10"
                                maxlength="5000"
                                class="w-full bg-gray-800/50 border border-gray-600 rounded-lg text-white px-4 py-4 placeholder-gray-500 focus:outline-none focus:border-faceit-orange focus:ring-1 focus:ring-faceit-orange transition-all resize-none"
                            ></textarea>
                            <div class="flex justify-between items-center mt-3">
                                <div class="text-red-400 text-sm hidden" id="message-error"></div>
                                <div class="text-xs text-gray-500">
                                    <span id="message-count">0</span>/5000 {{ __('contact.form.message.character_count') }}
                                </div>
                            </div>
                        </div>

                        
                        <div class="pt-6">
                            <button 
                                type="submit" 
                                id="submitButton"
                                class="group relative overflow-hidden bg-faceit-orange hover:bg-faceit-orange/90 text-white px-8 py-4 rounded-lg font-medium transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed min-w-[140px]"
                            >
                                <span class="relative z-10 text-sm uppercase tracking-wide" id="submitText">
                                    {{ __('contact.form.submit.send') }}
                                </span>
                                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                            </button>
                        </div>

                        
                        <div class="text-xs text-gray-500 pt-4 border-t border-gray-700/50">
                            <div class="flex items-center">
                                <div class="w-1 h-1 bg-gray-500 rounded-full mr-2"></div>
                                {{ __('contact.form.privacy_note') }}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>

window.translations = {!! json_encode([
    'contact' => __('contact'),
]) !!};

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitButton = document.getElementById('submitButton');
    const submitText = document.getElementById('submitText');
    const messageTextarea = document.getElementById('message');
    const messageCount = document.getElementById('message-count');

    
    messageTextarea.addEventListener('input', function() {
        const count = this.value.length;
        messageCount.textContent = count;
        
        if (count > 5000) {
            messageCount.parentElement.classList.add('text-red-400');
            messageCount.parentElement.classList.remove('text-gray-500');
        } else {
            messageCount.parentElement.classList.remove('text-red-400');
            messageCount.parentElement.classList.add('text-gray-500');
        }
    });

    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        clearErrors();
        
        submitButton.disabled = true;
        submitText.textContent = window.translations.contact.form.submit.sending;
        
        try {
            const formData = new FormData(form);
            
            const response = await fetch('{{ route("contact.submit") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showSuccessMessage(result.message, result.ticket_id);
                form.reset();
                messageCount.textContent = '0';
            } else {
                if (result.errors) {
                    showValidationErrors(result.errors);
                } else {
                    showErrorMessage(result.message || window.translations.contact.messages.error.generic);
                }
            }
            
        } catch (error) {
            showErrorMessage(window.translations.contact.messages.error.connection);
        } finally {
            submitButton.disabled = false;
            submitText.textContent = window.translations.contact.form.submit.send;
        }
    });

    function clearErrors() {
        const errorElements = document.querySelectorAll('[id$="-error"]');
        errorElements.forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.classList.remove('border-red-500', 'ring-red-500');
            input.classList.add('border-gray-600');
        });
    }

    function showValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const errorElement = document.getElementById(field + '-error');
            const inputElement = document.getElementById(field);
            
            if (errorElement && inputElement) {
                errorElement.textContent = errors[field][0];
                errorElement.classList.remove('hidden');
                inputElement.classList.remove('border-gray-600');
                inputElement.classList.add('border-red-500', 'ring-1', 'ring-red-500');
            }
        });
    }

    function showSuccessMessage(message, ticketId) {
        const flashContainer = document.getElementById('flashMessages');
        flashContainer.innerHTML = `
            <div class="p-8 rounded-xl border border-green-600/30" style="background: rgba(34, 197, 94, 0.05);">
                <div class="text-center">
                    <div class="inline-block p-3 rounded-full bg-green-500/20 mb-4">
                        <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                    </div>
                    <h4 class="text-white text-xl font-light mb-2 tracking-wide">${window.translations.contact.messages.success.title}</h4>
                    <p class="text-green-200 mb-2">${message}</p>
                    <p class="text-green-300 text-sm">${window.translations.contact.messages.success.ticket_id} <span class="font-medium">${ticketId}</span></p>
                </div>
            </div>
        `;
        
        flashContainer.scrollIntoView({ behavior: 'smooth' });
    }

    function showErrorMessage(message) {
        const flashContainer = document.getElementById('flashMessages');
        flashContainer.innerHTML = `
            <div class="p-8 rounded-xl border border-red-600/30" style="background: rgba(239, 68, 68, 0.05);">
                <div class="text-center">
                    <div class="inline-block p-3 rounded-full bg-red-500/20 mb-4">
                        <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                    </div>
                    <h4 class="text-white text-xl font-light mb-2 tracking-wide">${window.translations.contact.messages.error.title}</h4>
                    <p class="text-red-200">${message}</p>
                </div>
            </div>
        `;
        
        flashContainer.scrollIntoView({ behavior: 'smooth' });
    }
});
</script>
@endpush