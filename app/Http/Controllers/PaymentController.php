<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.payments');
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
    public function store(StorePaymentRequest $request)
    {
        $data = $request->except('_token');
        Payment::create($data);
        return response()->json(['status'=>'success', 'message'=>'Reglement Salaire effectué avec succès']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }

    public function getByMonthly(Request $request)
    {
        $employees = Employee::with([ 'payments' => fn ($query) => $query->where(['month_id'=>request()->month_id, 'year_id'=>request()->year]) ])
            ->where(['deleted'=>0, 'hastopay'=>1]);
            
        if($request->bank != '*') {
            $employees = $employees->where('bank', $request->bank);
        }
                
        $employees = $employees->get();
            
        $month = $request->monthname;
        $month_id = $request->month_id;
        $bank = $request->bankname;
        $bank_id = $request->bank;
        
        return view('admin.payment', compact('employees', 'month', 'month_id', 'bank', 'bank_id'));
    }
}
