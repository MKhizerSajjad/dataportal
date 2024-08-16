<?php

namespace App\Exports;

use App\Models\Contacts;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactsExport implements FromCollection, WithHeadings
{
    protected $filters;
    protected $offset;
    protected $limit;

    public function __construct(array $filters, $offset = null, $limit = null)
    {
        $this->filters = $filters;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $contacts = Contacts::query();
        logger("COUNT ALL : " . $contacts->count());
        // logger("Filterss ALL : " . json_encode($this->filters));
        if(isset($this->filters)) {
            foreach ($this->filters as $key => $filter) {
                if ($filter === null || $filter === '') {
                    continue; // Skip empty filters
                }

                switch ($key) {
                    case 'first_name':
                        $contacts->where(function($query) use ($filter) {
                            $query->where('first_name', 'LIKE', '%' . $filter . '%');
                        });
                        break;
                    case 'last_name':
                        $contacts->where(function($query) use ($filter) {
                            $query->Where('last_name', 'LIKE', '%' . $filter . '%');
                        });
                        break;

                        case 'title':
                            // $contacts->where(function($query) use ($filter) {
                            //     foreach ((array) $filter as $value) {
                            //         $words = explode(' ', $value);
                            //         // Build the query to find titles containing all words
                            //         foreach ($words as $word) {
                            //             // Ensure each word is present in the title
                            //             $query->where('title', 'LIKE', '%' . $word . '%');
                            //         }
                            //     }
                            // });
                            // break;
                            $contacts->where(function($query) use ($filter) {
                                $titleMappings = [
                                    'CEO' => ['CEO', 'Chief Executive Officer'],
                                    'CSO' => ['CSO', 'Chief Sales Officer'],
                                    'CRO' => ['CRO', 'Chief Revenue Officer'],
                                    'CCO' => ['CCO', 'Chief Commercial Officer'],
                                    'Chief Operating Officer' => ['Chief Operating Officer', 'COO'],
                                    'Managing Director' => ['Managing Director', 'MD'],
                                    'VP Sales' => ['VP Sales', 'Vice President Sales'],
                                    'Chief Marketing Officer' => ['Chief Marketing Officer', 'CMO'],
                                    'CIO' => ['CIO', 'Chief Information Officer'],
                                    'Chief Technology Officer' => ['Chief Technology Officer', 'CTO']
                                ];

                                foreach ((array) $filter as $value) {
                                    // Check if the word exists in the mappings
                                    $matchedTitles = [];
                                    foreach ($titleMappings as $key => $synonyms) {
                                        if (in_array($value, $synonyms)) {
                                            $matchedTitles = $synonyms;
                                        }
                                    }
                                    if (!empty($matchedTitles)) {
                                        // Build OR condition for matched titles
                                        // $orQueries[] = function ($q) use ($matchedTitles) {
                                            foreach ($matchedTitles as $title) {
                                                $query->orWhere('title', 'LIKE', '%' . $title . '%');
                                                // logger('title : ' . $title);
                                            }
                                        // };
                                    }
                                    $words = explode(' ', $value);
                                    $normalized = '%' . implode('%', $words) . '%';
                                    // Build the OR condition for each possible permutation
                                    foreach ($words as $word) {
                                        $query->where('title', 'LIKE', '%' . $word . '%');
                                    }
                                    // Allow different word order and additional strings
                                    $query->orWhere('title', 'LIKE', $normalized);
                                }
                            });
                            // case 'title':
                            //     $contacts->where(function($query) use ($filter) {
                            //         foreach ((array) $filter as $value) {
                            //             $words = explode(' ', $value);
                            //             $normalized = '%' . implode('%', $words) . '%';
                            //             // Build the OR condition for each possible permutation
                            //             foreach ($words as $word) {
                            //                 $query->orWhere('title', 'LIKE', '%' . $word . '%');
                            //             }
                            //             // Allow different word order and additional strings
                            //             $query->orWhere('title', 'LIKE', $normalized);
                            //         }
                            //     });
                            break;
                        case 'technologies':
                            // $contacts->where(function($query) use ($filter) {
                            //     foreach ((array) $filter as $value) {
                            //         $query->orWhereIn('technologies', [$value]);
                            //     }
                            // });
                            // break;
                        // case 'technologies':
                            $contacts->where(function($query) use ($filter) {
                                foreach ((array) $filter as $value) {
                                    $words = explode(' ', $value);
                                    foreach ($words as $word) {
                                        $query->Where('technologies', 'LIKE', '%' . $word . '%');
                                    }
                                }
                            });
                            break;

                    case 'department':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->Where('departments', 'LIKE', $value);
                            }
                        });
                        break;

                    case 'company':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('company', 'LIKE', $value);
                            }
                        });
                        break;
                    // case 'company':
                    //     $contacts->where(function($query) use ($filter) {
                    //         foreach ((array) $filter as $value) {
                    //             $words = explode(' ', $value);
                    //             foreach ($words as $word) {
                    //                 $query->orWhere('company', 'LIKE', '%' . $word . '%');
                    //             }
                    //         }
                    //     });
                    //     break;
                    case 'company_city':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('company_city', 'LIKE', $value);
                            }
                        });
                        break;
                    case 'company_state':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('company_state', 'LIKE', $value);
                            }
                        });
                        break;
                    case 'company_country':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('company_country', 'LIKE', $value);
                            }
                        });
                        break;
                    case 'city':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('city', 'LIKE', $value);
                            }
                        });
                        break;
                    case 'state':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('state', 'LIKE', $value);
                            }
                        });
                        break;
                    case 'country':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('country', 'LIKE', $value);
                            }
                        });
                        break;

                    case 'industry':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $words = explode(' ', $value);
                                // Build the query to find industry containing all words
                                foreach ($words as $word) {
                                    // Ensure each word is present in the industry
                                    $query->orWhere('industry', 'LIKE', '%' . $word . '%');
                                }
                            }
                        });
                        break;
                    // case 'industry':
                    //     $contacts->where(function($query) use ($filter) {
                    //         foreach ((array) $filter as $value) {
                    //             $words = explode(' ', $value);
                    //             foreach ($words as $word) {
                    //                 $query->orWhere('industry', 'LIKE', '%' . $word . '%');
                    //             }
                    //         }
                    //     });
                    //     break;
                    case 'email_status':
                        $contacts->whereIn($key, (array) $filter);
                        break;
                    case 'exclude_company':
                        $contacts->whereNotIn('company', (array) $filter);
                        break;
                    case 'from_employees':
                        $fromEmployees = $this->removeFormatt($filter);
                        break;
                    case 'to_employees':
                        $toEmployees = $this->removeFormatt($filter);
                        break;
                    case 'from_revenue':
                        $fromRevenue = $this->removeFormatt($filter);
                        break;
                    case 'to_revenue':
                        $toRevenue = $this->removeFormatt($filter);
                        break;
                    case 'from_funding':
                        $fromFunding = $this->removeFormatt($filter);
                        break;
                    case 'to_funding':
                        $toFunding = $this->removeFormatt($filter);
                        break;
                    case 'keywords':
                        $keywords = explode(',', $filter);
                        $contacts->where(function($query) use ($keywords) {
                            foreach ($keywords as $value) {
                                $query->orWhere('keywords', 'LIKE', '%' . $value . '%');
                            }
                        });
                        break;

                    case 'funding-cats':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('latest_funding', 'LIKE', $value);
                            }
                        });
                        break;

                    case 'technologies':
                        // $contacts->where(function($query) use ($filter) {
                        //     foreach ((array) $filter as $value) {
                        //         $query->orWhereIn('technologies', [$value]);
                        //     }
                        // });
                        // break;
                    // case 'technologies':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $words = explode(' ', $value);
                                foreach ($words as $word) {
                                    $query->Where('technologies', 'LIKE', '%' . $word . '%');
                                }
                            }
                        });
                        break;
                }
            }

            // Additional where clauses for range filters
            if(isset($fromEmployees) || isset($toEmployees)) {
                $contacts->whereBetween('employees', [$fromEmployees ?? 0, $toEmployees ?? 1000000]);
            }

            if(isset($fromRevenue) || isset($toRevenue)) {
                $contacts->whereBetween('annual_revenue', [$fromRevenue ?? 0, $toRevenue ?? 10000000000]);
            }

            if(isset($fromFunding) || isset($toFunding)) {
                $contacts->whereBetween('total_funding', [$fromFunding ?? 0, $toFunding ?? 10000000000]);
            }
        }

        if(($this->offset == null) && ($this->limit  == null)) {
            logger('get');
            return $contacts->get();
        } else {
            logger('offset');
            logger($this->offset .'------'.$this->limit);
            return $contacts->offset($this->offset)->limit($this->limit)
                    ->select('first_name', 'last_name', 'title', 'company', 'company_name_for_emails', 'email', 'email_status', 'seniority', 'departments',
                    'first_phone', 'work_direct_phone', 'home_phone', 'mobile_phone', 'corporate_phone', 'other_phone', 'employees', 'industry', 'keywords', 'person_linkedin',
                    'website', 'company_linkedin_url', 'facebook_url', 'twitter_url', 'city', 'state', 'country', 'company_address', 'company_city', 'company_state', 'company_country',
                    'company_phone', 'seo_description', 'technologies', 'annual_revenue', 'total_funding', 'latest_funding', 'latest_funding_amount', 'id')
                    ->get();
        }

        // $contacts = $contacts->limit(25000)->get();

        $transformedData = collect([$this->headings()]);
        // Transform data to replace IDs with actual values
        $transformedData = $transformedData->merge($contacts->map(function ($item) {
            return [
                // 'date' => $item->date,
                // 'status' => $item->status,
                'first_name' => $item->first_name,
                'last_name' => $item->last_name,
                'title' => $item->title,
                'company' => $item->company,
                'company_name_for_emails' => $item->company_name_for_emails,
                'email' => $item->email,
                'email_status' => $item->email_status,
                'email_confidence' => $item->email_confidence,
                'seniority' => $item->seniority,
                'departments' => $item->departments,
                'contact_owner' => $item->contact_owner,
                'first_phone' => $item->first_phone,
                'work_direct_phone' => $item->work_direct_phone,
                'home_phone' => $item->home_phone,
                'mobile_phone' => $item->mobile_phone,
                'corporate_phone' => $item->corporate_phone,
                'other_phone' => $item->other_phone,
                'stage' => $item->stage,
                'lists' => $item->lists,
                'last_contacted' => $item->last_contacted,
                'account_owner' => $item->account_owner,
                'employees' => $item->employees,
                'industry' => $item->industry,
                'keywords' => $item->keywords,
                'person_linkedin' => $item->person_linkedin,
                'website' => $item->website,
                'company_linkedin_url' => $item->company_linkedin_url,
                'facebook_url' => $item->facebook_url,
                'twitter_url' => $item->twitter_url,
                'city' => $item->city,
                'state' => $item->state,
                'country' => $item->country,
                'company_address' => $item->company_address,
                'company_city' => $item->company_city,
                'company_state' => $item->company_state,
                'company_country' => $item->company_country,
                'company_phone' => $item->company_phone,
                'seo_description' => $item->seo_description,
                'technologies' => $item->technologies,
                'annual_revenue' => $item->annual_revenue,
                'total_funding' => $item->total_funding,
                'latest_funding' => $item->latest_funding,
                'latest_funding_amount' => $item->latest_funding_amount,
                // 'last_raised_at' => $item->last_raised_at,
                // 'email_sent' => $item->email_sent,
                // 'email_open' => $item->email_open,
                // 'email_bounced' => $item->email_bounced,
                // 'replied' => $item->replied,
                // 'demoed' => $item->demoed,
                // 'number_of_retail_locations' => $item->number_of_retail_locations,
                // 'apollo_contact_id' => $item->apollo_contact_id,
                // 'apollo_account_id' => $item->apollo_account_id,
            ];
        }));

        return $transformedData;
        // return $contacts->get()->first();

    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Title',
            'Company',
            'Company Name for Emails',
            'Email',
            'Email Status',
            // 'Email Confidence',
            'Seniority',
            'Departments',
            // 'Contact Owner',
            'First Phone',
            'Work Direct Phone',
            'Home Phone',
            'Mobile Phone',
            'Corporate Phone',
            'Other Phone',
            // 'Stage',
            // 'Lists',
            // 'Last Contacted',
            // 'Account Owner',
            'Employees',
            'Industry',
            'Keywords',
            'Person LinkedIn',
            'Website',
            'Company LinkedIn URL',
            'Facebook URL',
            'Twitter URL',
            'City',
            'State',
            'Country',
            'Company Address',
            'Company City',
            'Company State',
            'Company Country',
            'Company Phone',
            'SEO Description',
            'Technologies',
            'Annual Revenue',
            'Total Funding',
            'Latest Funding',
            'Latest Funding Amount',
            // 'last_raised_at',
            // 'email_sent',
            // 'email_open',
            // 'email_bounced',
            // 'replied',
            // 'demoed',
            // 'number_of_retail_locations',
            // 'apollo_contact_id',
            // 'apollo_account_id',
            'Index',
            // 'Status',
        ];

    }

    public function chunkSize(): int
    {
        return 1000; // Set your desired chunk size
    }

    public function queryCount()
    {
        // Apply filters (if needed)
        if (!empty($this->filters)) {

            logger ('in queryCount');
            $contacts = Contacts::query();

            $fromEmployees = 0;
            $toEmployees = 1000000;
            $fromRevenue = 0;
            $toRevenue = 10000000000;
            $fromFunding = 0;
            $toFunding = 10000000000;
            foreach ($this->filters as $key => $filter) {
                if ($filter === null || $filter === '') {
                    continue; // Skip empty filters
                }

                switch ($key) {
                    case 'first_name':
                        $contacts->where(function($query) use ($filter) {
                            $query->where('first_name', 'LIKE', '%' . $filter . '%');
                        });
                        break;
                    case 'last_name':
                        $contacts->where(function($query) use ($filter) {
                            $query->Where('last_name', 'LIKE', '%' . $filter . '%');
                        });
                        break;

                        case 'title':
                            // $contacts->where(function($query) use ($filter) {
                            //     foreach ((array) $filter as $value) {
                            //         $words = explode(' ', $value);
                            //         // Build the query to find titles containing all words
                            //         foreach ($words as $word) {
                            //             // Ensure each word is present in the title
                            //             $query->where('title', 'LIKE', '%' . $word . '%');
                            //         }
                            //     }
                            // });
                            // break;
                            $contacts->where(function($query) use ($filter) {
                                $titleMappings = [
                                    'CEO' => ['CEO', 'Chief Executive Officer'],
                                    'CSO' => ['CSO', 'Chief Sales Officer'],
                                    'CRO' => ['CRO', 'Chief Revenue Officer'],
                                    'CCO' => ['CCO', 'Chief Commercial Officer'],
                                    'Chief Operating Officer' => ['Chief Operating Officer', 'COO'],
                                    'Managing Director' => ['Managing Director', 'MD'],
                                    'VP Sales' => ['VP Sales', 'Vice President Sales'],
                                    'Chief Marketing Officer' => ['Chief Marketing Officer', 'CMO'],
                                    'CIO' => ['CIO', 'Chief Information Officer'],
                                    'Chief Technology Officer' => ['Chief Technology Officer', 'CTO']
                                ];

                                foreach ((array) $filter as $value) {
                                    // Check if the word exists in the mappings
                                    $matchedTitles = [];
                                    foreach ($titleMappings as $key => $synonyms) {
                                        if (in_array($value, $synonyms)) {
                                            $matchedTitles = $synonyms;
                                        }
                                    }
                                    if (!empty($matchedTitles)) {
                                        // Build OR condition for matched titles
                                        // $orQueries[] = function ($q) use ($matchedTitles) {
                                            foreach ($matchedTitles as $title) {
                                                $query->orWhere('title', 'LIKE', '%' . $title . '%');
                                                // logger('title : ' . $title);
                                            }
                                        // };
                                    }
                                    $words = explode(' ', $value);
                                    $normalized = '%' . implode('%', $words) . '%';
                                    // Build the OR condition for each possible permutation
                                    foreach ($words as $word) {
                                        $query->where('title', 'LIKE', '%' . $word . '%');
                                    }
                                    // Allow different word order and additional strings
                                    $query->orWhere('title', 'LIKE', $normalized);
                                }
                            });
                            // case 'title':
                            //     $contacts->where(function($query) use ($filter) {
                            //         foreach ((array) $filter as $value) {
                            //             $words = explode(' ', $value);
                            //             $normalized = '%' . implode('%', $words) . '%';
                            //             // Build the OR condition for each possible permutation
                            //             foreach ($words as $word) {
                            //                 $query->orWhere('title', 'LIKE', '%' . $word . '%');
                            //             }
                            //             // Allow different word order and additional strings
                            //             $query->orWhere('title', 'LIKE', $normalized);
                            //         }
                            //     });
                            break;
                    case 'seniority':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->where('seniority', 'LIKE', $value);
                            }
                        });
                        break;
                    // case 'seniority':
                    //     $contacts->where(function($query) use ($filter) {
                    //         foreach ((array) $filter as $value) {
                    //             $words = explode(' ', $value);
                    //             foreach ($words as $word) {
                    //                 $query->orWhere('seniority', 'LIKE', '%' . $word . '%');
                    //             }
                    //         }
                    //     });
                    //     break;

                    case 'department':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->Where('departments', 'LIKE', $value);
                            }
                        });
                        break;

                    case 'company':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('company', 'LIKE', $value);
                            }
                        });
                        break;
                    // case 'company':
                    //     $contacts->where(function($query) use ($filter) {
                    //         foreach ((array) $filter as $value) {
                    //             $words = explode(' ', $value);
                    //             foreach ($words as $word) {
                    //                 $query->orWhere('company', 'LIKE', '%' . $word . '%');
                    //             }
                    //         }
                    //     });
                    //     break;
                    case 'company_city':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('company_city', 'LIKE', $value);
                            }
                        });
                        break;
                    case 'company_state':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('company_state', 'LIKE', $value);
                            }
                        });
                        break;
                    case 'company_country':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('company_country', 'LIKE', $value);
                            }
                        });
                        break;
                    case 'city':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('city', 'LIKE', $value);
                            }
                        });
                        break;
                    case 'state':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('state', 'LIKE', $value);
                            }
                        });
                        break;
                    case 'country':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('country', 'LIKE', $value);
                            }
                        });
                        break;

                    case 'industry':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $words = explode(' ', $value);
                                // Build the query to find industry containing all words
                                foreach ($words as $word) {
                                    // Ensure each word is present in the industry
                                    $query->orWhere('industry', 'LIKE', '%' . $word . '%');
                                }
                            }
                        });
                        break;
                    // case 'industry':
                    //     $contacts->where(function($query) use ($filter) {
                    //         foreach ((array) $filter as $value) {
                    //             $words = explode(' ', $value);
                    //             foreach ($words as $word) {
                    //                 $query->orWhere('industry', 'LIKE', '%' . $word . '%');
                    //             }
                    //         }
                    //     });
                    //     break;
                    case 'email_status':
                        $contacts->whereIn($key, (array) $filter);
                        break;
                    case 'exclude_company':
                        $contacts->whereNotIn('company', (array) $filter);
                        break;
                    case 'from_employees':
                        $fromEmployees = $this->removeFormatt($filter);
                        break;
                    case 'to_employees':
                        $toEmployees = $this->removeFormatt($filter);
                        break;
                    case 'from_revenue':
                        $fromRevenue = $this->removeFormatt($filter);
                        break;
                    case 'to_revenue':
                        $toRevenue = $this->removeFormatt($filter);
                        break;
                    case 'from_funding':
                        $fromFunding = $this->removeFormatt($filter);
                        break;
                    case 'to_funding':
                        $toFunding = $this->removeFormatt($filter);
                        break;
                    case 'keywords':
                        $keywords = explode(',', $filter);
                        $contacts->where(function($query) use ($keywords) {
                            foreach ($keywords as $value) {
                                $query->orWhere('keywords', 'LIKE', '%' . $value . '%');
                            }
                        });
                        break;

                    case 'funding-cats':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $query->orWhere('latest_funding', 'LIKE', $value);
                            }
                        });
                        break;

                    case 'technologies':
                        // $contacts->where(function($query) use ($filter) {
                        //     foreach ((array) $filter as $value) {
                        //         $query->orWhereIn('technologies', [$value]);
                        //     }
                        // });
                        // break;
                    // case 'technologies':
                        $contacts->where(function($query) use ($filter) {
                            foreach ((array) $filter as $value) {
                                $words = explode(' ', $value);
                                foreach ($words as $word) {
                                    $query->Where('technologies', 'LIKE', '%' . $word . '%');
                                }
                            }
                        });
                        break;
                }
            }

            // Additional where clauses for range filters
            if(isset($fromEmployees) || isset($toEmployees)) {
                $contacts->whereBetween('employees', [$fromEmployees ?? 0, $toEmployees ?? 1000000]);
            }

            if(isset($fromRevenue) || isset($toRevenue)) {
                $contacts->whereBetween('annual_revenue', [$fromRevenue ?? 0, $toRevenue ?? 10000000000]);
            }

            if(isset($fromFunding) || isset($toFunding)) {
                $contacts->whereBetween('total_funding', [$fromFunding ?? 0, $toFunding ?? 10000000000]);
            }
            logger ('in queryCount END : COUNT : ' . $contacts->count());
            return $contacts->count();
        }

    }

    private function removeFormatt($value) {
        $plainNumber = str_replace(',', '', $value);
        return (int) $plainNumber;
    }
}
