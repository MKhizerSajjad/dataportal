<?php
use Illuminate\Support\Facades\Storage;

    function getTitles()
    {
        return json_decode(Storage::get("filters/titles.json"));
    }

    function getSeniority()
    {
        return json_decode(Storage::get("filters/seniority.json"));
    }

    function getDepartments()
    {
        return json_decode(Storage::get("filters/departments.json"));
    }

    function getCompanies()
    {
        return json_decode(Storage::get("filters/companies.json"));
    }

    function getCities()
    {
        return json_decode(Storage::get("filters/cities.json"));
    }

    function getStates()
    {
        return json_decode(Storage::get("filters/states.json"));
    }

    function getCountries()
    {
        return json_decode(Storage::get("filters/countries.json"));
    }

    function getCompanyCities()
    {
        return json_decode(Storage::get("filters/company_cities.json"));
    }

    function getCompanyStates()
    {
        return json_decode(Storage::get("filters/company_states.json"));
    }

    function getCompanyCountries()
    {
        return json_decode(Storage::get("filters/company_countries.json"));
    }

    function getIndustries()
    {
        return json_decode(Storage::get("filters/industries.json"));
    }

    function getTechnologies()
    {
        return json_decode(Storage::get("filters/technologies.json"));
    }

    function getEmailStatus()
    {
        return json_decode(Storage::get("filters/email_status.json"));
    }
