<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<!-- Restore the persisted theme/personalization before paint to avoid a flash -->
	<script>
		(function () {
			try {
				var saved = localStorage.getItem('jss-admin-theme');
				if (saved) { document.documentElement.className = saved; }
			} catch (e) {}
		})();
	</script>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="JAGUAR SECURITY SERVICES OFFICIAL WEBSITE">
	<!--favicon-->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png"/>
	<!--plugins-->
	<link rel="stylesheet" href="{{ asset('assets/admin/plugins/notifications/css/lobibox.min.css') }}" />
	<link href="{{ asset('assets/admin/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/admin/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/admin/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/admin/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/admin/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
	<!-- loader-->
	<link href="{{ asset('assets/admin/css/pace.min.css') }}" rel="stylesheet"/>
	<script src="{{ asset('assets/admin/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/admin/css/bootstrap-extended.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{ asset('assets/admin/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/admin/css/icons.css') }}" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{ asset('assets/admin/css/dark-theme.css') }}"/>
	<link rel="stylesheet" href="{{ asset('assets/admin/css/semi-dark.css') }}"/>
	<link rel="stylesheet" href="{{ asset('assets/admin/css/header-colors.css') }}"/>
	<title>{{ config('app.name', "JSS SARL") }}</title>

	<!-- Select2 : recherche dans les listes déroulantes (employés, etc.) -->
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

	<style>
		/* --- Bouton de fermeture des modaux : bien visible, rouge --- */
		.btn-close,
		.modal .btn-close {
			--bs-btn-close-bg: none;
			opacity: 1;
			width: 1.7rem;
			height: 1.7rem;
			padding: 0;
			background-color: #dc3545;
			border-radius: 50%;
			background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3e%3cpath d='M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854z'/%3e%3c/svg%3e");
			background-position: center;
			background-repeat: no-repeat;
			background-size: 0.95rem;
			box-shadow: none;
			flex: 0 0 auto;
		}
		.btn-close:hover,
		.modal .btn-close:hover {
			background-color: #b02a37;
			transform: rotate(90deg);
			opacity: 1;
		}
		.btn-close:focus {
			box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, .4);
			opacity: 1;
		}

		/* --- Select2 : cohérence avec les champs Bootstrap --- */
		.select2-container { width: 100% !important; }
		.select2-container .select2-selection--single {
			height: calc(1.5em + 0.75rem + 2px);
			padding: 0.30rem 0.75rem;
			border: 1px solid #ced4da;
			border-radius: 0.25rem;
		}
		.select2-container--open { z-index: 1060; }
		.select2-dropdown { z-index: 1065; }
	</style>

	@stack('css-view')
	<script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<x-sidebar-admin></x-sidebar-admin>
		<!--end sidebar wrapper -->
        
		<!--start header -->
		<x-header-admin></x-header-admin>
		<!--end header -->

		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
                {{ $slot }}
			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		 <div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button-->
		  <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">Copyright &copy; JSS - {{ date('Y') }} | All right reserved.</p>
		</footer>
	</div>
	<!--end wrapper-->

	<!-- search modal -->
    <div class="modal" id="SearchModal" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
		  <div class="modal-content">
			<div class="modal-header gap-2">
				<div class="position-relative popup-search w-100">
					<input class="form-control form-control-lg ps-5 border border-3 border-primary" type="search" placeholder="@lang('lang.search')...">
					<span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-4"><i class='bx bx-search'></i></span>
				</div>
			  	<button type="button" class="btn-close d-md-none" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="search-list">
				   	<p class="mb-1">@lang('lang.employee_management')</p>
				   	<div class="list-group">
					  	<a href="{{ route('employees.index', app()->getLocale()) }}" class="list-group-item list-group-item-action active align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-group fs-4'></i>@lang('lang.employee', ['param'=>'s'])</a>
					  	<a href="{{ route('leaves.index') }}" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-user-minus'></i>@lang('lang.leaf', ['param'=>'s'])</a>
					  	<a href="{{ route('suspensions.index') }}" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-user-minus'></i>@lang('lang.suspension', ['param'=>'s'])</a>
					  	<a href="{{ route('dotations.index') }}" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-user-plus'></i>@lang('lang.dotation', ['param'=>'s'])</a>
					  	<a href="{{ route('applicants.index') }}" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-group'></i>@lang('lang.applicant', ['param'=>'s'])</a>
					  	<a href="ecommerce-add-new-products.html" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-user-x'></i>@lang('lang.licenciement', ['param'=>'s'])</a>
					  	<a href="{{ route('affectations.index') }}" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-user-check'></i>@lang('lang.affectation', ['param'=>'s'])</a>
				   	</div>
				   	
					<p class="mb-1 mt-3">@lang('lang.accountable')</p>
				   	<div class="list-group">
						<a href="{{ route('customers.index') }}" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-buildings'></i>@lang('lang.customer', ['param'=>'s'])</a>
                		<a href="{{ route('bills.index') }}" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-blanket'></i>@lang('lang.bill', ['param'=>'s'])</a>
				   </div>

				   	<p class="mb-1 mt-3">@lang('lang.logistic', ['param'=>''])</p>
				   	<div class="list-group">
						<a href="{{ route('categories.index') }}" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-user-x'></i>@lang('lang.category', ['param'=>'s'])</a>
                		<a href="{{ route('equipments.index') }}" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-user-x'></i>@lang('lang.equipment', ['param'=>'s'])</a>
				   	</div>

				   	<p class="mb-1 mt-3">@lang('lang.secretary')</p>
				   	<div class="list-group">
						<a href="{{ route('mails.index') }}" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-envelope'></i>@lang('lang.mail', ['param'=>'s'])</a>
						<a href="{{ route('meets.index') }}" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-calendar-week'></i>@lang('lang.meet', ['param'=>'s'])</a>
						<a href="{{ route('groups', app()->getLocale()) }}" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-group'></i>@lang('lang.group', ['param'=>'s'])</a>
						<a href="{{ route('users.index') }}" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bx-sm bx-group'></i>@lang('lang.user', ['param'=>'s'])</a>
				   	</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
    <!-- end search modal -->

	<!--start switcher-->
	<div class="switcher-wrapper">
		<div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i></div>
		<div class="switcher-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-uppercase">@lang('lang.personalization')</h5>
				<button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
			</div>
			<hr/>
			<h6 class="mb-0">@lang('lang.styles_theme')</h6>
			<hr/>
			<div class="d-flex align-items-center justify-content-between">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode" checked>
					<label class="form-check-label" for="lightmode">@lang('lang.light')</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
					<label class="form-check-label" for="darkmode">@lang('lang.dark')</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark">
					<label class="form-check-label" for="semidark">@lang('lang.semi_dark')</label>
				</div>
			</div>
			<hr/>
			<div class="form-check">
				<input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
				<label class="form-check-label" for="minimaltheme">@lang('lang.minimal_theme')</label>
			</div>
			<hr/>
			<h6 class="mb-0">@lang('lang.header_color')</h6>
			<hr/>
			<div class="header-colors-indigators">
				<div class="row row-cols-auto g-3">
					<div class="col">
						<div class="indigator headercolor1" id="headercolor1"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor2" id="headercolor2"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor3" id="headercolor3"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor4" id="headercolor4"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor5" id="headercolor5"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor6" id="headercolor6"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor7" id="headercolor7"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor8" id="headercolor8"></div>
					</div>
				</div>
			</div>
			<hr/>
			<h6 class="mb-0">@lang('lang.sidebar_color')</h6>
			<hr/>
			<div class="header-colors-indigators">
				<div class="row row-cols-auto g-3">
					<div class="col">
						<div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
	<!--plugins-->
	<script src="{{ asset('assets/admin/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('assets/admin/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ asset('assets/admin/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<script src="{{ asset('assets/admin/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
	<script src="{{ asset('assets/admin/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('assets/admin/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

	<script src="{{ asset('assets/admin/plugins/notifications/js/lobibox.min.js') }}"></script>
	<script src="{{ asset('assets/admin/plugins/notifications/js/notifications.min.js') }}"></script>
	<script src="{{ asset('assets/js/notify.js') }}"></script>
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	
	@stack('js-view')
	<!--app JS-->
	<script src="{{ asset('assets/admin/js/app.js') }}"></script>

	<!-- Select2 -->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script>
		(function () {
			function initEmployeeSelects(scope) {
				var $scope = scope ? $(scope) : $(document);
				$scope.find('select[name="employee_id"]').each(function () {
					var $select = $(this);
					if ($select.hasClass('select2-hidden-accessible')) return;
					var $modal = $select.closest('.modal');
					// Normalise l'option "placeholder" (sans attribut value) pour
					// que Select2 affiche bien le texte d'invite.
					var $first = $select.find('option:first');
					if ($first.length && !$first.is('[value]')) { $first.attr('value', ''); }
					$select.select2({
						theme: 'bootstrap-5',
						width: '100%',
						placeholder: ($select.find('option').first().text() || '').trim() || '—',
						dropdownParent: $modal.length ? $modal : $('body'),
						language: {
							noResults: function () { return 'Aucun résultat'; },
							searching: function () { return 'Recherche…'; },
							inputTooShort: function () { return 'Saisissez pour rechercher'; }
						}
					});
				});
			}

			$(function () {
				initEmployeeSelects();
				// Les modaux d'édition sont rendus à la volée : on (ré)initialise à l'ouverture.
				$(document).on('shown.bs.modal', function (event) {
					initEmployeeSelects(event.target);
				});
			});
		})();
	</script>

	<!-- Personnalisation (thème / couleurs) : persistance locale -->
	<script>
		(function () {
			var KEY = 'jss-admin-theme';
			var save = function () {
				try { localStorage.setItem(KEY, document.documentElement.className); } catch (e) {}
			};

			var controls = document.querySelectorAll(
				'#lightmode, #darkmode, #semidark, #minimaltheme,'
				+ '.switcher-body .indigator, .dark-mode, .dark-mode-icon'
			);
			controls.forEach(function (el) {
				el.addEventListener('click', function () { setTimeout(save, 60); });
			});

			// Refléter le thème actif dans les boutons radio du panneau.
			var cls = ' ' + document.documentElement.className + ' ';
			var radios = { 'dark-theme': 'darkmode', 'semi-dark': 'semidark', 'minimal-theme': 'minimaltheme', 'light-theme': 'lightmode' };
			Object.keys(radios).forEach(function (name) {
				if (cls.indexOf(' ' + name + ' ') !== -1) {
					var radio = document.getElementById(radios[name]);
					if (radio) radio.checked = true;
				}
			});
		})();
	</script>
</body>

</html>