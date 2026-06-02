<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Galery;
use Illuminate\View\View;
use App\Models\Affectation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function welcome($locale): View
    {
        app()->setLocale($locale ?? app()->getLocale());
        return view('welcome');
    }

    public function about($locale): View
    {
        app()->setLocale($locale ?? app()->getLocale());
        return view('about');
    }

    public function services($locale): View
    {
        app()->setLocale($locale ?? app()->getLocale());
        return view('services');
    }

    public function team($locale): View
    {
        app()->setLocale($locale ?? app()->getLocale());
        $count = Galery::count();
        $galeries = Galery::orderByDesc('id')->paginate(9);
        return view('team', compact('galeries', 'count'));
    }

    public function contacts($locale): View
    {
        app()->setLocale($locale ?? app()->getLocale());
        return view('contacts');
    }

    public function shops($locale): View
    {
        app()->setLocale($locale ?? app()->getLocale());
        return view('shops');
    }

    public function admin()
    {
        return view('admin.dashboard');
    }

    public function dash(Request $request)
    {
        
        $employees = Employee::with([ 'payments' => fn ($stmt) => $stmt->where(['month_id'=>$request->month_id, 'year_id'=>$request->year]) ])
            ->where(['deleted'=>0, 'hastopay'=>1])
            ->whereMonth('created_at', '<=', $request->month_id)
            ->whereYear('created_at', '<=', $request->year)
            ->get();
         
        $customers = Customer::with([
            'affectations'=>fn ($stmt) => $stmt->whereDate('end', '>=', date($request->year.'-'.$request->month_id.'-d'))->whereDate('begin', '<=', date($request->year.'-'.$request->month_id.'-d')),
            'bills'=>fn ($query) => $query->where(['month_id'=>$request->month_id, 'year_id'=>$request->year])
        ])->where('deleted', 0)->get();

        $month = $request->monthname;
        $month_id = $request->month_id;
        return view('admin.dash', compact('employees', 'month_id', 'month', 'customers'));
    }

    public function groups()
    {
        $groups = Group::all();
        return view('admin.groups', compact('groups'));
    }

    public function accountant()
    {
        $months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
        return view('admin.accountant', compact('months'));
    }

    public function secretary()
    {
        // dd(Hash::make("richala@jss224"));
        return view('admin.secretary');
    }
    
    public function logistic()
    {
        return view('admin.logistic');
    }
    
    public function getAgents()
    {
        $agents = Employee::where('position', 'LIKE', '%AGENT%')->orderByDesc('id')->get();
        return view('admin.agents', compact('agents'));
    }
    
    public function getAdministrators()
    {
        $administrators = Employee::where('position', 'NOT LIKE', '%AGENT%')->orderByDesc('id')->get();
        return view('admin.administrators', compact('administrators'));
    }

    public function language(string $locale)
    {
        $currentThis = User::find(auth()->id());
        $currentThis->update(array('lang'=>$locale));
        return back();
    }

    public function humanresource()
    {
        $employees = Employee::where('deleted', 0)->get();
        return view('admin.humanresource', compact('employees'));
    }
}
