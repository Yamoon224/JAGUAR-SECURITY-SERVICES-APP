<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\View\View;
use Illuminate\Http\Request;

class AccountantController extends Controller
{
    public function index(): View
    {
        $months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
        return view('admin.accountant', compact('months'));
    }
}
