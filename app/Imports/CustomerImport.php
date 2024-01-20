<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class CustomerImport  implements ToModel, WithHeadingRow
{
    /**
     * @param Collection $collection
     */

    use Importable;
    private $newCustomerCount = 0;
    private $updatedCustomerCount = 0;

    public function model(array $row)
    {
        $bulkCustomerData = [
            'name' => isset($row['customer_name']) ? trim($row['customer_name']) : null,
            'email' => isset($row['email']) ? trim($row['email']) : null,
            'phone_number' => isset($row['phone_number']) ? trim($row['phone_number']) : null,
            'company_name' => isset($row['company_name']) ? trim($row['company_name']) : null,
            'status' => isset($row['status']) ? strtolower(trim($row['status'])) : 'no_status',
            'communication_medium' => isset($row['communication_medium']) ? strtolower(trim($row['communication_medium'])) : null,

        ];

        $existingCustomerByEmail = Customer::where('email', trim($row['email']))->first();
        $existingCustomerByPhoneNumber = Customer::where('phone_number', trim($row['phone_number']))->first();

        // Check if a customer was updated
        if ($existingCustomerByEmail || $existingCustomerByPhoneNumber) {
            $existingCustomer = $existingCustomerByEmail ?? $existingCustomerByPhoneNumber;
            $existingCustomer->update($bulkCustomerData);
            $this->updatedCustomerCount++;
        } else {
            Customer::create($bulkCustomerData);
            $this->newCustomerCount++;
        }
    }

    public function getNewCustomerCount()
    {
        return $this->newCustomerCount;
    }

    public function getUpdatedCustomerCount()
    {
        return $this->updatedCustomerCount;
    }
}
