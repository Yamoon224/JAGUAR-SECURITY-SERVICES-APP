<x-auth-layout :size="4">
    @push('links')
    <meta property="og:site_name" content="JAGUAR SECURITY SERVICES SARL" />
    <meta property="og:title" content="RECRUTEMENT JSS-SARL" />
    <meta property="og:image" content="{{ asset('images/recruitment.jpg') }}" />
    <meta property="og:description" content="Confirmation de la soumission du dossier" />
    @endpush
    <div class="text-center mb-4">
        <h6>RECRUTEMENT</h6>
        <h6 style="font-size: 12px">N° DOSSIER : {{ $id }}</h6>
        <p class="mb-0 text-sm text-success">Candidature enregistrée avec succès</p>
        <p class="mb-0"><a href="{{ route('register') }}" class="text-dark">Formulaire</a></p>
    </div>
</x-auth-layout>
