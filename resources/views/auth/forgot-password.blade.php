<x-guest-layout>
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">Mot de passe oublié</h2>
    <p class="text-sm text-gray-500 text-center mb-6">Entrez votre adresse email et nous vous enverrons un lien de réinitialisation.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Adresse email" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="vous@exemple.fr" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center">
            Envoyer le lien de réinitialisation
        </x-primary-button>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Retour à la connexion</a>
        </p>
    </form>
</x-guest-layout>
