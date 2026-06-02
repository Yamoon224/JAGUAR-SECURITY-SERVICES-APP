<?php

namespace App\Http\Controllers;

use App\Models\Leaf;
use App\Models\Customer;
use App\Models\Dotation;
use App\Models\Employee;
use App\Models\Applicant;
use App\Models\Equipment;
use App\Models\Suspension;
use App\Models\Affectation;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::where('deleted', 0);
        $count = $employees->count();
        $employees = $employees->orderByDesc('id')->paginate(16);
        $applicants = Applicant::where(array('status'=>'inprogress', 'deleted'=>0))->get();
        $equipments = Equipment::all();
        $suspensions = Suspension::with('employee')->get();
        $leaves = Leaf::with('employee')->get();
        $dotations = Dotation::with('employee', 'equipment')->get();
        $customers = Customer::where('deleted', 0)->get();
        $affectations = Affectation::all();
        return view('admin.employees', compact('employees', 'count', 'customers', 'affectations', 'applicants', 'equipments', 'leaves', 'dotations', 'suspensions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->except('_token', 'employee_id');
        $applicant = Applicant::find($request->employee_id);
        $data['firstname'] = $applicant->firstname;
        $data['name'] = $applicant->name;
        $data['phone'] = $applicant->phone;
        $data['photo'] = $applicant->photo;
        $data['matricule'] = str_replace("N° DOSSIER", 'JAGUAR24', $applicant->applicationid);;
        $data['address'] = $applicant->address;
        
        $isMatricule = Employee::firstWhere('matricule', $data['matricule']);
        if($isMatricule) 
            $data['matricule'] = 'JAGUAR23 0'.(Str::substr(Employee::max('matricule'), 10, 3) + 1);
        
            
        DB::beginTransaction();
            $applicant->update(['status'=>'hired']);
            Employee::create($data);
        DB::commit();
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $employee = Employee::find($id);
        $equipments = Equipment::all()->filter(fn ($equipment) => $equipment->qty > $equipment->dotations->sum('qty'));
        return view('admin.employee', compact('employee', 'equipments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $employee = Employee::find($id);
        $employee->status = 1;
        $employee->save();
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, int $id)
    {
        $employee = Employee::find($id);
        $data = $request->except('_token');
        $data['hastopay'] = !empty($data['hastopay']) ? 1 : 0;
        $data['isleaved'] = $employee->isleaved == 1 && empty($data['isleaved'])  ? 0 : $employee->isleaved;
        $data['issuspended'] = $employee->issuspended == 1 && empty($data['issuspended'])  ? 0 : $employee->issuspended;
        
        $isMatricule = Employee::where('matricule', '!=', $employee->matricule)->firstWhere('matricule', $data['matricule']);
        if($isMatricule) return back()->withErrors(['matricule'=>"Ce N° Matricule ".$data['matricule']." appartient déjà un autre employé"]);
        
        $employee->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $employee)
    {
        $employee = Employee::find($employee);
        Affectation::where('employee_id', $employee->id)->delete();
        $employee->delete();
        return back();
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function employeeNotAffected(int $id)
    {
        $employee = Employee::find($id);
        Affectation::where('employee_id', $employee->id)->delete();
        $employee->delete();
        
        return back();
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function getSearch(Request $request)
    {
        $employees = Employee::where('deleted', 0)
            ->where('matricule', 'LIKE', "%".trim($request->search)."%")
            ->OrWhere('name', 'LIKE', "%".trim($request->search)."%")
            ->OrWhere('firstname', 'LIKE', "%".trim($request->search)."%")
            ->get();
        return view('admin.search_employees', compact('employees'));
    }
}
