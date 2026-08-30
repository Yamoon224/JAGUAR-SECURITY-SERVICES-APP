<x-admin-layout>
@push('css-view')
<link href="{{ asset('assets/admin/plugins/fullcalendar/css/main.min.css') }}" rel="stylesheet" />
<style>
    #appointmentCalendar { --fc-border-color: #e9ecef; }
    #appointmentCalendar .fc-toolbar-title { font-size: 1.15rem; }
    #appointmentCalendar .fc-event { cursor: pointer; border: none; padding: 2px 4px; }
    #appointmentCalendar .fc-daygrid-event { white-space: normal; }
    @media (max-width: 575.98px) {
        #appointmentCalendar .fc-toolbar { flex-direction: column; gap: .5rem; }
    }
</style>
@endpush

<div class="col">
    <div class="d-sm-flex align-items-center flex-wrap gap-2">
        <h6 class="mb-0 text-uppercase">@lang('lang.appointment', ['param'=>'s'])</h6>
        <div class="ms-auto d-flex flex-wrap gap-2">
            <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#appointment-add"><i class="bx bx-calendar-plus"></i> @lang('lang.new_appointment')</a>
            <a class="btn btn-sm btn-danger" href="{{ route('appointments.report') }}" target="_blank"><i class="bx bx-printer"></i> PDF @lang('lang.appointment', ['param'=>'s'])</a>
        </div>
    </div>
    <hr/>

    <div class="card border-dark border-bottom border-3 border-0">
        <div class="card-body">
            <div id="appointmentCalendar"></div>

            @forelse ($appointments as $item)
                <x-appointment-edit :appointment="$item" />
            @empty
            @endforelse
        </div>
    </div>
</div>

<x-appointment-add />

@push('js-view')
<script src="{{ asset('assets/admin/plugins/fullcalendar/js/main.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/fullcalendar/locales/fr.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('appointmentCalendar');
        if (!el || typeof FullCalendar === 'undefined') return;

        var pad = function (n) { return (n < 10 ? '0' : '') + n; };
        var ymd = function (d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); };
        var hm  = function (d) { return pad(d.getHours()) + ':' + pad(d.getMinutes()); };

        var calendar = new FullCalendar.Calendar(el, {
            initialView: 'dayGridMonth',
            locale: 'fr',
            firstDay: 1,
            height: 'auto',
            nowIndicator: true,
            editable: {{ isRightAccess([1, 4, 7]) ? 'true' : 'false' }},
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            slotMinTime: '06:00:00',
            slotMaxTime: '21:00:00',
            events: @json($events),

            dateClick: function (info) {
                var form = document.querySelector('#appointment-add form');
                if (form) {
                    var dateField = form.querySelector('[name="expected_at"]');
                    if (dateField) dateField.value = info.dateStr.substring(0, 10);
                    if (info.date && info.view.type !== 'dayGridMonth') {
                        var st = form.querySelector('[name="start_time"]');
                        var et = form.querySelector('[name="end_time"]');
                        if (st) st.value = hm(info.date);
                        var endDate = new Date(info.date.getTime() + 30 * 60000);
                        if (et) et.value = hm(endDate);
                    }
                }
                new bootstrap.Modal(document.getElementById('appointment-add')).show();
            },

            eventClick: function (info) {
                var modal = document.getElementById('appointment' + info.event.id);
                if (modal) new bootstrap.Modal(modal).show();
            },

            eventDrop: reschedule,
            eventResize: reschedule
        });

        calendar.render();

        function reschedule(info) {
            var e = info.event;
            var body = new URLSearchParams();
            body.set('_method', 'PUT');
            body.set('expected_at', ymd(e.start));
            body.set('start_time', hm(e.start));
            body.set('end_time', hm(e.end || new Date(e.start.getTime() + 30 * 60000)));
            body.set('visitor', e.extendedProps.visitor || e.title);
            body.set('phone', e.extendedProps.phone || '');
            body.set('company', e.extendedProps.company || '');

            fetch("{{ url('appointments') }}/" + e.id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: body.toString()
            }).then(function (r) {
                if (!r.ok) info.revert();
            }).catch(function () {
                info.revert();
            });
        }
    });
</script>
@endpush
</x-admin-layout>
