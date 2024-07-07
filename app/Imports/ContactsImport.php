<?php

namespace App\Imports;

use DateTime;
use Carbon\Carbon;
use App\Models\Contacts;
use App\Imports\CustomException;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Hash;

class ContactsImport implements ToModel, WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $date = null;
        // $date = $this->validateAndReformatDate($row[43]);

        return new Contacts([
            'status'                => 1,
            'first_name'            => $row[0] ?? 'Not Found',
            'last_name'             => $row[1] ?? 'NULL',
            'title'                 => $row[2],
            'company'               => $row[3],
            'company_name_for_emails' => $row[4],
            'email'                 => $row[5],
            'email_status'          => $row[6],
            'email_confidence'      => $row[7],
            'seniority'             => $row[8],
            'departments'           => $row[9],
            'contact_owner'         => $row[10],
            'first_phone'           => $row[11],
            'work_direct_phone'     => $row[12],
            'home_phone'            => $row[13],
            'mobile_phone'          => $row[14],
            'corporate_phone'       => $row[15],
            'other_phone'           => $row[16],
            'stage'                 => $row[17],
            'lists'                 => $row[18],
            'last_contacted'        => $row[19],
            'account_owner'         => $row[20],
            'employees'             => $row[21],
            'industry'              => $row[22],
            'keywords'              => $row[23],
            'person_linkedin'      => $row[24],
            'website'               => $row[25],
            'company_linkedin_url'  => $row[26],
            'facebook_url'          => $row[27],
            'twitter_url'           => $row[28],
            'city'                  => $row[29],
            'state'                 => $row[30],
            'country'               => $row[31],
            'company_address'       => $row[32],
            'company_city'          => $row[33],
            'company_state'         => $row[34],
            'company_country'       => $row[35],
            'company_phone'         => $row[36],
            'seo_description'       => $row[37],
            'technologies'          => $row[38],
            'annual_revenue'        => is_int($row[39]) ? $row[39] : null,
            'total_funding'         => $row[40],
            'latest_funding'        => $row[41],
            'latest_funding_amount' => $row[42],
            'last_raised_at'        => $date,
            'email_sent'            => null,
            'email_open'            => $row[45],
            'email_bounced'         => $row[46],
            'replied'               => $row[47],
            'demoed'                => $row[48],
            'number_of_retail_locations'  => $row[49],
            'apollo_contact_id'     => $row[50],
            'apollo_account_id'     => $row[51],
        ]);
    }

    private function validateAndReformatDate($date)
    {
        try {
            $date = Carbon::parse($date);

            $date = $date->format('Y-m-d');
            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d h:m:i');
            return $date;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function chunkSize(): int
    {
        return 1000; // Set your desired chunk size
    }
}
