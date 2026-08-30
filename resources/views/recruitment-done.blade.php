<x-auth-layout :cover="asset('images/recruitment_og.jpg')">
    @push('links')
    <meta property="og:site_name" content="JAGUAR SECURITY SERVICES SARL" />
    <meta property="og:title" content="Candidature envoyée — JAGUAR SECURITY SERVICES" />
    <meta property="og:image" content="{{ asset('images/recruitment_og.jpg') }}" />
    <meta property="og:description" content="Confirmation de la soumission du dossier" />
    @endpush

    <div class="text-center py-3">
        <i class="bx bx-check-circle" style="font-size: 3.25rem; color: #198754;"></i>
        <h5 class="fw-bold mt-3 mb-1">Candidature envoyée</h5>
        <p class="text-muted small">Votre dossier a bien été enregistré. Notre équipe RH vous recontactera.</p>

        @if($reference)
        <div class="recruit-notice p-3 my-3 text-start" style="background:#f8f9fa;border:1px solid #e9ecef;border-left:4px solid #198754;border-radius:.35rem;">
            <div class="small text-muted">Référence de votre dossier</div>
            <div class="fw-semibold">{{ $reference }}</div>
        </div>
        <p class="text-muted small mb-4">Conservez cette référence pour tout suivi.</p>
        @endif

        <a href="{{ route('home') }}" class="btn btn-outline-dark">
            <i class="bx bx-arrow-back"></i> Nouvelle candidature
        </a>
    </div>
</x-auth-layout>
