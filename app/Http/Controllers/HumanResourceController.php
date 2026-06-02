<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Rest;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HumanResourceController extends Controller
{
    public function index(): View
    {
        $employees = Employee::where('deleted', 0)->get();
        return view('admin.humanresource', compact('employees'));
    }
}
