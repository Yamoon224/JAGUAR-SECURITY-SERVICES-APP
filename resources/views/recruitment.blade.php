<x-auth-layout :size="2">
    @push('links')
    <meta property="og:site_name" content="{{ 'JAGUAR SECURITY SERVICES SARL' }}" />
    <meta property="og:title" content="{{ $recruitment->title ?? 'RECRUTEMENT JSS-SARL' }}" />
    <meta property="og:url" content="https://recrutement.jss-gn.com/fr" />
    <meta property="og:image" content="{{ asset('images/recruitment_og.png') }}?v={{ time() }}" />
    <meta property="og:description" content="{{ $recruitment->description ?? 'Aucun recrutement en cours' }}" />
    @endpush
    <div class="text-center mb-4">
        <h6 class="" style="font-size: 12px">{{ $recruitment->title ?? 'RECRUTEMENT JSS-SARL' }}</h6>
        {!! $recruitment->description ?? '<p class="mb-0 text-sm text-danger" style="font-size: 10px">Aucun recrutement en cours</p>' !!}
        @if($recruitment && $recruitment->path)
        <!--<p><img src="{{ $recruitment->path }}" class="img-rounded img-thumbnail"></p>-->
        @endif
    </div>
    @if($recruitment)
    <form method="POST" action="{{ route('applicants.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="recruitment_id" value="{{ $recruitment->id }}">
        <div class="row">
            <div class="col-md-6 col-xs-12 mb-2">
                <label for="firstname">@lang('lang.firstname') <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="@lang('lang.firstname')" required>
            </div>
            <div class="col-md-6 col-xs-12 mb-2">
                <label for="name">@lang('lang.name') <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="@lang('lang.name')" required>
            </div>
            <div class="col-md-6 col-xs-12 mb-2">
                <label for="phone">@lang('lang.phone_id') <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="@lang('lang.phone_id')" required>
            </div>
            <div class="col-md-6 col-xs-12 mb-2">
                <label for="address">@lang('lang.addresses') <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="address" name="address" placeholder="@lang('lang.addresses')" required>
            </div>
            <div class="col-12 mb-2">
                <label for="affiliate">@lang('lang.affiliate') <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="affiliate" name="affiliate" placeholder="@lang('lang.affiliate')" required>
            </div>
            <div class="col-12">
                <label for="address">@lang('lang.photo')</label>
                <div class="position-relative input-icon mb-2">
                    <input type="file" class="form-control" name="photo">
                    <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-image'></i></span>
                </div>
            </div>
            <div class="col-12">
                <label for="application_file" class="form-label">@lang('lang.application_file')</label>
                <div class="position-relative input-icon mb-2">
                    <input type="file" class="form-control" id="application_file" name="path">
                    <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-file'></i></span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-grid">
                    <button class="btn btn-dark">@lang('lang.submit')</button>
                </div>
            </div>
        </div>
    </form>
    @endif
</x-auth-layout>
