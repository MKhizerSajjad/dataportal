<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacts;
use App\Models\Title;
use App\Models\Seniority;
use App\Models\Departments;
use App\Models\Company;
use App\Models\Cities;
use App\Models\States;
use App\Models\Countries;
use App\Models\Industry;
use App\Models\Keywords;
use App\Models\Technologies;

class FiltersController extends Controller
{
    public function getNames(Request $request)
    {
        return Contacts::orWhere('first_name', 'LIKE', '%'.$request->search.'%')
            ->orWhere('last_name', 'LIKE', '%'.$request->search.'%')->select('id', 'name')->get();
    }

    public function getJobTitles(Request $request)
    {
        $formattedTitles = Title::where('name', 'LIKE', '%'.$request->search.'%')->select('id', 'name')->get();

        return response()->json($formattedTitles);
        // $query = Title::query();

        // if ($request->has('search') && !empty($request->search)) {
        //     $query->where('name', 'LIKE', '%'.$request->search.'%');
        // }

        // // Fetch filtered titles or all titles
        // $titles = $query->select('id', 'name')->get();

        // // Format data for Select2
        // $formattedTitles = [];
        // foreach ($titles as $title) {
        //     $formattedTitles[] = [
        //         'id' => $title->id,
        //         'name' => $title->name
        //     ];
        // }

        return response()->json($formattedTitles);
    }

    public function getSeniorities(Request $request)
    {
        return Seniority::where('name', 'LIKE', '%'.$request->search.'%')->select('id', 'name')->get();
    }

    public function getDepartments(Request $request)
    {
        return Departments::where('name', 'LIKE', '%'.$request->search.'%')->select('id', 'name')->get();
    }

    public function getCompanies(Request $request)
    {
        return Company::where('name', 'LIKE', '%'.$request->search.'%')->select('id', 'name')->get();
    }

    public function getCities(Request $request)
    {
        return Cities::where('name', 'LIKE', '%'.$request->search.'%')->select('id', 'name')->get();
    }

    public function getStates(Request $request)
    {
        return States::where('name', 'LIKE', '%'.$request->search.'%')->select('id', 'name')->get();
    }

    public function getCountries(Request $request)
    {
        return Countries::where('name', 'LIKE', '%'.$request->search.'%')->select('id', 'name')->get();
    }

    public function getIndustries(Request $request)
    {
        return Industry::where('name', 'LIKE', '%'.$request->search.'%')->select('id', 'name')->get();
    }

    public function getTechnologies(Request $request)
    {
        return Technologies::where('name', 'LIKE', '%'.$request->search.'%')->select('id', 'name')->get();
    }
}
