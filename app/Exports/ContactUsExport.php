<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\ContactUs;

class ContactUsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Email',
            'Phone Number',
            'Message'
            // Add more column headings here as needed
        ];
    }
    public function collection()
    {
        return ContactUs::select('first_name','last_name', 'email','phone', 'message')->get();
    }
}
