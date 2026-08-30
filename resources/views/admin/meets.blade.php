<x-admin-layout>

<div class="d-sm-flex align-items-center flex-wrap gap-2 mb-1">
    <div>
        <h5 class="mb-0 text-uppercase">@lang('lang.meet', ['param'=>'s'])</h5>
        <small class="text-muted">{{ $meets->count() }} @lang('lang.meet', ['param'=>'s'])</small>
    </div>
    <div class="ms-auto d-flex flex-wrap gap-2">
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#meet-add"><i class="bx bx-calendar-plus"></i> @lang('lang.new_meet')</a>
        <a class="btn btn-sm btn-danger" href="{{ route('prints.meets.report') }}" target="_blank"><i class="bx bx-printer"></i> PDF @lang('lang.meet', ['param'=>'s'])</a>
    </div>
</div>
<hr/>

<div class="row g-3 row-cols-1 row-cols-md-2 row-cols-xl-3">
    @forelse ($meets as $item)
    <div class="col d-flex">
        <div class="card border-bottom border-dark border-3 radius-15 h-100 w-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-start gap-2 mb-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-dark text-white flex-shrink-0" style="width:40px;height:40px">
                        <i class="bx bx-calendar-check"></i>
                    </span>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $item->object }}</h6>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</small>
                    </div>
                </div>

                <div class="small text-muted flex-grow-1">{{ \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($item->points))), 220) }}</div>

                <div class="d-flex align-items-center gap-2 mt-3">
                    @if($item->file_path)
                    <a href="{{ asset('storage/'.$item->file_path) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                        <i class="bx bxs-file-pdf text-danger"></i> @lang('lang.file_path')
                    </a>
                    @endif
                    <div class="ms-auto d-flex gap-1">
                        <a data-bs-toggle="modal" data-bs-target="#meet{{ $item->id }}" class="btn btn-sm btn-outline-primary" title="@lang('lang.edit')"><i class="bx bx-edit-alt"></i></a>
                        <x-meet-edit :meet="$item" />
                        <form action="{{ route('meets.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="@lang('lang.delete')" onclick="return confirm('Confirmez-Vous cette suppression')"><i class="bx bx-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center text-muted py-5">
            <i class="bx bx-calendar-check" style="font-size:3rem"></i>
            <h6 class="mt-3 mb-1">Aucune réunion enregistrée</h6>
            <p class="small mb-0">Créez un compte rendu de réunion pour le retrouver ici.</p>
        </div>
    </div>
    @endforelse
</div>

<x-meet-add />

@push('js-view')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    (function () {
        if (typeof CKEDITOR === 'undefined') return;

        CKEDITOR.disableAutoInline = true;
        var config = {
            height: 220,
            removePlugins: 'elementspath',
            removeButtons: 'Anchor,Styles,Subscript,Superscript,Image',
            toolbar: [
                { name: 'clipboard', items: ['Undo', 'Redo'] },
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
                { name: 'links', items: ['Link', 'Unlink'] },
                { name: 'insert', items: ['Table', 'HorizontalRule'] }
            ],
            language: 'fr'
        };

        var build = function (modal) {
            modal.querySelectorAll('textarea.ckeditor-points').forEach(function (ta) {
                if (ta.id && !CKEDITOR.instances[ta.id]) {
                    CKEDITOR.replace(ta.id, config);
                }
            });
        };
        var teardown = function (modal) {
            modal.querySelectorAll('textarea.ckeditor-points').forEach(function (ta) {
                var editor = ta.id && CKEDITOR.instances[ta.id];
                if (editor) { editor.updateElement(); editor.destroy(true); }
            });
        };

        document.addEventListener('shown.bs.modal', function (e) { build(e.target); });
        document.addEventListener('hidden.bs.modal', function (e) { teardown(e.target); });

        // Synchronise le contenu de l'éditeur vers le textarea avant l'envoi.
        document.addEventListener('submit', function (e) {
            e.target.querySelectorAll('textarea.ckeditor-points').forEach(function (ta) {
                var editor = ta.id && CKEDITOR.instances[ta.id];
                if (!editor) return;
                editor.updateElement();
                if (!ta.value.replace(/<[^>]*>/g, '').trim()) {
                    e.preventDefault();
                    alert("L'ordre du jour est obligatoire.");
                }
            });
        }, true);

        // Laisse les boîtes de dialogue CKEditor recevoir le focus dans une modale Bootstrap 5.
        document.addEventListener('focusin', function (e) {
            if (e.target.closest('.cke_dialog, .cke_dialog_background_cover')) {
                e.stopImmediatePropagation();
            }
        }, true);
    })();
</script>
@endpush
</x-admin-layout>
