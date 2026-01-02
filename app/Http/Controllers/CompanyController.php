<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Company;
// use App\Mail\SendCompanyCreatedEmail;
use App\Jobs\SendCompanyCreatedEmail;
use Illuminate\Support\Facades\Storage;


class CompanyController extends Controller
{
        // public function index()

public function listCompanies(Request $request)
{
    $companies = Company::paginate(10);

    if($request->ajax()){
        return response()->json([
            'status' => 'success',
            'data'   => $companies,
        ]);
    }   

    return view('companies.index', compact('companies'));
}


public function show()
    {
        $companies = Company::paginate(10);
        return view('companies.index', compact('companies'));
        // return response()->json([
        //     'status'=>'success',
        //     'data'=>$companies
        // ]);
    }

    public function create()
    {
        return view('companies.create');
        // return response()->json([
        //     'status'=>'success',
        //      'message'=>'company created successfully'
        // ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|dimensions:min_width=100,min_height=100',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->logo->store('logos', 'public');
        }else{
            $data['logo']="logo not uploaded";
        }

        $company = Company::create($data);

        SendCompanyCreatedEmail::dispatch($company);
        
    //  Mail::to('amarabatool76@gmail.com')
    // ->send(new CompanyCreatedMail($company));

        // return redirect()->route('companies.index');
        return response()->json([
            'status'=>'success',
            'message'=>'company is created',
            'redirect_url'=>route('companies.index'),
        ]);
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|dimensions:min_width=100,min_height=100',

        ]);
        //  $data = $request->all();
    $data = $request->only(['name', 'email', 'website']);

    // Check if user uploaded a new logo
    if ($request->hasFile('logo')) {
        // Delete old logo if exists
        if ($company->logo && Storage::disk('public')->exists($company->logo)) {
            Storage::disk('public')->delete($company->logo);
        }

        // Store new logo
        $data['logo'] = $request->file('logo')->store('logos', 'public');
    } else {
        // Old logo exists → keep it, else null
        $data['logo'] = $company->logo ?? null;
    }

    // Update company
    $company->update($data);


        // $company->update($request->all());
        // return redirect()->route('companies.index');
        return response()->json([
            'status'=>'success',
            'message'=>'company updated successfully',
           'redirect_url' => route('companies.index')
        ]);
    }

    public function destroy(Company $company)
    {
        $company->delete();
        // return redirect()->route('companies.index');
        return response()->json([
            'status'=>'success',
            'message'=>'company deleted successfully'
        ]);
    }
}


