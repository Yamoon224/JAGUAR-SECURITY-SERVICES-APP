<div class="modal fade" id="fueling{{ $fueling->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-edit-alt"></i> @lang('lang.fueling', ['param'=>''])</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('fuelings.update', $fueling->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label mb-1">@lang('lang.fueling_date') *</label>
                            <div class="position-relative input-icon mb-3">
                                <input type="datetime-local" class="form-control" name="fueled_at" value="{{ \Carbon\Carbon::parse($fueling->fueled_at)->format('Y-m-d\TH:i') }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-calendar'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="number" class="form-control" name="volume" placeholder="@lang('lang.fuel_volume') *" value="{{ $fueling->volume }}" min="0.01" step="0.01" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-droplet'></i></span>
                            </div>
                            <select class="form-select mb-3" name="fuel_type" required>
                                <option value="essence" {{ $fueling->fuel_type == 'essence' ? 'selected' : '' }}>Essence</option>
                                <option value="gasoil" {{ $fueling->fuel_type == 'gasoil' ? 'selected' : '' }}>Gasoil</option>
                            </select>
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="beneficiary_matricule" placeholder="@lang('lang.beneficiary_matricule') *" value="{{ $fueling->beneficiary_matricule }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-id-card'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="beneficiary_function" placeholder="@lang('lang.beneficiary_function') *" value="{{ $fueling->beneficiary_function }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="station_name" placeholder="@lang('lang.station_name') *" value="{{ $fueling->station_name }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-map'></i></span>
                            </div>
                            <select class="form-select mb-3" name="vehicle_type" required>
                                <option value="voiture" {{ $fueling->vehicle_type == 'voiture' ? 'selected' : '' }}>Voiture</option>
                                <option value="moto" {{ $fueling->vehicle_type == 'moto' ? 'selected' : '' }}>Moto</option>
                            </select>
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" name="voucher_number" placeholder="@lang('lang.voucher_number')" value="{{ $fueling->voucher_number }}">
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-receipt'></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success"><i class="bx bx-check"></i> @lang('lang.submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
