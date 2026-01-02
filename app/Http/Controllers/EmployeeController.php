<?php

namespace App\Http\Controllers;
// use Illuminate\Http\Models\Employee;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Http\Request;
class EmployeeController extends Controller
{
    public function employeelist(Request $request)
    {
        $employees = Employee::with('company')->paginate(10);
     if($request->ajax()){
        return response()->json([
            'status'=>'success',
            'data'=>$employees,
        ]);
     }
    return view('employees.index', compact('employees'));

    }

    public function create()
    {
        $companies = Company::all();
        return view('employees.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'company_id' => 'required|exists:companies,id',
            'email' => 'nullable|email',
            'phone' => 'nullable',
        ]);

        Employee::create($request->all());
        // return redirect()->route('employees.index');
        return response()->json([
            'status'=>'success',
            'message'=>'employee is created',
            'redirect_url'=>route('employees.index'),

        ]);

    }

    public function edit(Employee $employee)
    {
        $companies = Company::all();
        return view('employees.edit', compact('employee','companies'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $employee->update($request->all());
        // return redirect()->route('employees.index');
        return response()->json([
            'status'=>'success',
            'message'=>'employee updated successfully',
            'redirect_url'=>route('employees.index'),

        ]);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        // return redirect()->route('employees.index');
        return response()->json([
            'status'=>'success',
            'message'=>'employee deleted successfully'
        ]);
    }
}
