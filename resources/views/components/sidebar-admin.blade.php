<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">JSS Sarl</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
     </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ getDashboardRoute() }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                <div class="menu-title">@lang('lang.dashboard')</div>
            </a>
        </li>
        <li class="menu-label">@lang('lang.features')</li>
        @if(isRightAccess([1, 4, 7]))
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-group'></i></div>
                <div class="menu-title">@lang('lang.employee', ['param'=>"s"])</div>
            </a>
            <ul>
                @if(isRightAccess([1]))
                <li><a href="{{ route('administrators') }}"><i class='bx bx-table'></i>@lang('lang.administrator', ['param'=>'s'])</a></li>
                <li><a href="{{ route('agents') }}"><i class='bx bx-table'></i>@lang('lang.agent', ['param'=>'s'])</a></li>
                <li><a href="{{ route('employees.index', app()->getLocale()) }}"><i class='bx bx-table'></i>@lang('lang.employee', ['param'=>'s'])</a></li>
                @endif
                
                @if(isRightAccess([1, 4, 7]))
                <li> <a href="{{ route('applicants.index') }}"><i class='bx bx-group'></i>@lang('lang.applicant', ['param'=>'s'])</a></li>
                <li> <a href="{{ route('recruitments.index') }}"><i class='bx bx-group'></i>@lang('lang.recruitment', ['param'=>'s'])</a></li>
                @endif
                
                @if(isRightAccess([1]))
                <li> <a href="{{ route('applicants.hires') }}"><i class='bx bx-group'></i>@lang('lang.hire', ['param'=>'s'])</a></li>
                <li> <a href="{{ route('affectations.index') }}"><i class='bx bx-user-check'></i>@lang('lang.affectation', ['param'=>'s'])</a></li>
                @endif
            </ul>
        </li>
        @endif
        @if(isRightAccess([1, 3, 7]))
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-group'></i></div>
                <div class="menu-title">@lang('lang.archive', ['param'=>"s"])</div>
            </a>
            <ul>
                <li> <a href="{{ route('leaves.index') }}"><i class='bx bx-user-minus'></i>@lang('lang.leaf', ['param'=>'s'])</a></li>
                <li> <a href="{{ route('suspensions.index') }}"><i class='bx bx-user-minus'></i>@lang('lang.suspension', ['param'=>'s'])</a></li>
                <li> <a href="{{ route('licenciements.index') }}"><i class='bx bx-user-x'></i>@lang('lang.licenciement', ['param'=>'s'])</a></li>
            </ul>
        </li>
        @endif
        
        @if(isRightAccess([1, 2]))
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-wallet-alt'></i></div>
                <div class="menu-title">@lang('lang.accountable', ['param'=>"s"])</div>
            </a>
            <ul>
                <li> <a href="{{ route('customers.index') }}"><i class='bx bx-buildings'></i>@lang('lang.customer', ['param'=>'s'])</a></li>
                <li> <a href="{{ route('bills.index') }}"><i class='bx bx-blanket'></i>@lang('lang.bill', ['param'=>'s'])</a></li>
                <li> <a href="{{ route('payments.index') }}"><i class='bx bx-blanket'></i>@lang('lang.payment', ['param'=>'s'])</a></li>
            </ul>
        </li>
        @endif
        
        @if(isRightAccess([1, 7]))
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-layer'></i></div>
                <div class="menu-title">@lang('lang.logistic', ['param'=>"s"])</div>
            </a>
            <ul>
                <li> <a href="{{ route('dotations.index') }}"><i class='bx bx-user-plus'></i>@lang('lang.material_dotation')</a></li>
                <li> <a href="{{ route('purchases.index') }}"><i class='bx bx-cart'></i>@lang('lang.material_supply')</a></li>
                <li> <a href="{{ route('fuelings.index') }}"><i class='bx bx-gas-pump'></i>@lang('lang.fueling', ['param'=>'s'])</a></li>
                <li> <a href="{{ route('equipments.index') }}"><i class='bx bx-customize'></i>@lang('lang.equipment', ['param'=>'s']) @lang('lang.available')</a></li>
                <li> <a href="{{ route('equipments.deteriorated') }}"><i class='bx bx-error'></i>@lang('lang.deteriorated_equipment')</a></li>
                <li> <a href="{{ route('stocks.index') }}"><i class='bx bx-archive'></i>@lang('lang.logistic_archive')</a></li>
            </ul>
        </li>
        @endif
        
        @if(isRightAccess([1, 2, 4, 7]))
        <li>
            <a href="{{ route('meets.index') }}">
                <div class="parent-icon"><i class='bx bx-calendar-check'></i></div>
                <div class="menu-title">@lang('lang.meet', ['param'=>'s'])</div>
            </a>
        </li>
        @endif
        
        @if(isRightAccess([1, 4, 7]))
        <li>
            <a href="{{ route('mails.index') }}">
                <div class="parent-icon"><i class='bx bx-mail-send'></i></div>
                <div class="menu-title">@lang('lang.mail', ['param'=>'s'])</div>
            </a>
        </li>
        <li>
            <a href="{{ route('appointments.index') }}">
                <div class="parent-icon"><i class='bx bx-calendar'></i></div>
                <div class="menu-title">@lang('lang.appointment', ['param'=>'s'])</div>
            </a>
        </li>
        @endif
        
        @if(isRightAccess([1]))
        <li class="menu-label">@lang('lang.administration')</li>
        <li>
            <a href="{{ route('groups', app()->getLocale()) }}">
                <div class="parent-icon"><i class='bx bx-group'></i></div>
                <div class="menu-title">@lang('lang.group', ['param'=>'s'])</div>
            </a>
        </li>
        <li>
            <a href="{{ route('users.index') }}">
                <div class="parent-icon"><i class='bx bx-group'></i></div>
                <div class="menu-title">@lang('lang.user', ['param'=>'s'])</div>
            </a>
        </li>
        <li>
            <a href="{{ route('categories.index') }}">
                <div class="parent-icon"><i class='bx bx-bookmarks'></i></div>
                <div class="menu-title">@lang('lang.category', ['param'=>'s'])</div>
            </a>
        </li>
        @endif
    </ul>
    <!--end navigation-->
</div>