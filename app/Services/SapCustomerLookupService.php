<?php

namespace App\Services;

use App\Contracts\SapCustomerLookupServiceInterface;
use App\Models\AccMast;
use Illuminate\Support\Facades\Cache;

class SapCustomerLookupService implements SapCustomerLookupServiceInterface
{
    /**
     * Search SAP customers using the current AccMast pattern.
     */
    public function searchCustomers(?string $search): array
    {
        $search = trim((string) $search);

        if ($search === '' || mb_strlen($search) < 2) {
            return [];
        }

        try {
            return Cache::remember(
                'sap_customers_search_' . md5($search),
                now()->addMinutes(10),
                function () use ($search) {
                    return AccMast::query()
                        ->where('Type', '10')
                        ->where(function ($query) use ($search) {
                            $query->where('Arabic_Name', 'like', '%' . $search . '%')
                                ->orWhere('Code', 'like', '%' . $search . '%');
                        })
                        ->orderBy('Arabic_Name')
                        ->limit(50)
                        ->get(['Code', 'Arabic_Name'])
                        ->mapWithKeys(function ($customer) {
                            return [
                                $customer->Code => $this->formatLabel(
                                    $customer->Code,
                                    $customer->Arabic_Name
                                ),
                            ];
                        })
                        ->toArray();
                }
            );
        } catch (\Throwable $exception) {
            report($exception);

            return [];
        }
    }

    /**
     * Resolve a single SAP customer by code.
     */
    public function findCustomerByCode(?string $customerCode): ?array
    {
        $customerCode = trim((string) $customerCode);

        if ($customerCode === '') {
            return null;
        }

        try {
            return Cache::remember(
                'sap_customer_' . md5($customerCode),
                now()->addMinutes(30),
                function () use ($customerCode) {
                    $customer = AccMast::query()
                        ->where('Type', '10')
                        ->where('Code', $customerCode)
                        ->first(['Code', 'Arabic_Name']);

                    if (! $customer) {
                        return null;
                    }

                    return [
                        'code' => $customer->Code,
                        'name' => $customer->Arabic_Name,
                        'label' => $this->formatLabel($customer->Code, $customer->Arabic_Name),
                    ];
                }
            );
        } catch (\Throwable $exception) {
            report($exception);

            return null;
        }
    }

    /**
     * Build the display label shown in the Filament select.
     */
    protected function formatLabel(string $code, string $name): string
    {
        return trim($code . ' - ' . $name);
    }
}
