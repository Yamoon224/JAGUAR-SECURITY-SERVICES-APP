<x-auth-layout :cover="asset('images/recruitment_og.jpg')" :wide="true">
    @push('links')
    <meta property="og:site_name" content="JAGUAR SECURITY SERVICES SARL" />
    <meta property="og:title" content="{{ $recruitment->title ?? 'RECRUTEMENT — JAGUAR SECURITY SERVICES' }}" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:image" content="{{ asset('images/recruitment_og.jpg') }}" />
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($recruitment->description ?? 'Aucun recrutement en cours'), 160) }}" />
    <style>
        .recruit-head .eyebrow {
            letter-spacing: .12em;
            font-size: .72rem;
            font-weight: 600;
            color: #dc3545;
        }
        .recruit-notice {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-left: 4px solid #dc3545;
            border-radius: .35rem;
        }
        /* Contenu riche saisi via l'éditeur (description de la campagne) */
        .recruit-desc { color: #343a40; font-size: .9rem; line-height: 1.55; }
        .recruit-desc :last-child { margin-bottom: 0; }
        .recruit-desc p { margin-bottom: .5rem; }
        .recruit-desc ul, .recruit-desc ol { margin-bottom: .5rem; padding-left: 1.25rem; }
        .recruit-desc h1, .recruit-desc h2, .recruit-desc h3,
        .recruit-desc h4, .recruit-desc h5, .recruit-desc h6 {
            font-size: .95rem; font-weight: 700; margin: .75rem 0 .35rem;
        }
        .recruit-section-title {
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6c757d;
        }
        .form-control-file-hint { font-size: .75rem; color: #6c757d; }
    </style>
    @endpush

    <div class="recruit-head text-center mb-4">
        <div class="eyebrow mb-1">JAGUAR SECURITY SERVICES</div>
        <h4 class="fw-bold mb-1">@lang('lang.recruitment', ['param'=>''])</h4>
        <p class="text-muted small mb-0">Déposez votre candidature en quelques minutes.</p>
    </div>

    @if($recruitment)
        <div class="recruit-notice p-3 mb-4">
            <div class="fw-semibold mb-2">{{ $recruitment->title }}</div>
            <div class="recruit-desc">
                {!! $recruitment->description ?: 'Recrutement en cours.' !!}
            </div>
            @if($recruitment->start_date || $recruitment->end_date)
            <div class="small text-muted mt-2">
                <i class="bx bx-calendar"></i>
                @if($recruitment->start_date) du {{ \Carbon\Carbon::parse($recruitment->start_date)->format('d/m/Y') }} @endif
                @if($recruitment->end_date) au {{ \Carbon\Carbon::parse($recruitment->end_date)->format('d/m/Y') }} @endif
            </div>
            @endif
        </div>

        @if ($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('applicants.store') }}" enctype="multipart/form-data" novalidate>
            @csrf
            <input type="hidden" name="recruitment_id" value="{{ $recruitment->id }}">

            <div class="recruit-section-title mb-2">Vos informations</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="firstname" class="form-label">@lang('lang.firstname') <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                        <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" value="{{ old('firstname') }}" placeholder="@lang('lang.firstname')" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">@lang('lang.name') <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-id-card"></i></span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="@lang('lang.name')" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">@lang('lang.phone_id') <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-phone"></i></span>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Ex. 6XX XX XX XX" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="address" class="form-label">@lang('lang.addresses') <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-map"></i></span>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" placeholder="Ville, commune, quartier" required>
                    </div>
                </div>
                <div class="col-12">
                    <label for="affiliate" class="form-label">@lang('lang.affiliate')</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-group"></i></span>
                        <input type="text" class="form-control @error('affiliate') is-invalid @enderror" id="affiliate" name="affiliate" value="{{ old('affiliate') }}" placeholder="Nom du père / de la mère">
                    </div>
                </div>
            </div>

            <div class="recruit-section-title mb-2">Vos documents</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="photo" class="form-label">@lang('lang.photo')</label>
                    <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                    <div class="form-control-file-hint mt-1">Photo d'identité (JPG/PNG).</div>
                </div>
                <div class="col-md-6">
                    <label for="application_file" class="form-label">@lang('lang.application_file')</label>
                    <input type="file" class="form-control @error('path') is-invalid @enderror" id="application_file" name="path" accept=".pdf,.doc,.docx">
                    <div class="form-control-file-hint mt-1">CV / dossier (PDF ou Word).</div>
                </div>
            </div>

            <p class="text-muted small">
                En envoyant ce formulaire, vous acceptez que JAGUAR SECURITY SERVICES traite vos données
                dans le cadre de ce recrutement.
            </p>

            <div class="d-grid">
                <button class="btn btn-dark btn-lg">
                    <i class="bx bx-paper-plane"></i> @lang('lang.submit')
                </button>
            </div>
        </form>
    @else
        <div class="text-center py-4">
            <i class="bx bx-time-five" style="font-size: 3rem; color: #adb5bd;"></i>
            <h6 class="mt-3 mb-1">Aucun recrutement en cours</h6>
            <p class="text-muted small mb-0">
                Aucune campagne de recrutement n'est ouverte pour le moment.
                Revenez consulter cette page prochainement.
            </p>
        </div>
    @endif
</x-auth-layout>
