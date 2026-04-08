<x-guest-layout>
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">Vérification email</h2>
    <p class="text-sm text-gray-500 text-center mb-6">Merci pour votre inscription ! Veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 rounded-xl bg-green-50 border border-green-200 text-sm text-green-700 font-medium">
            Un nouveau lien de vérification a été envoyé à votre adresse email.
        </div>
    @endif

    <div class="flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center">
                Renvoyer l'email de vérification
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:text-indigo-600 font-medium transition">
                Se déconnecter
            </button>
        </form>
    </div>
</x-guest-layout>
