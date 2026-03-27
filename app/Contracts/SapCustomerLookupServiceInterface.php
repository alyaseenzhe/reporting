<?php

namespace App\Contracts;

interface SapCustomerLookupServiceInterface
{
    /**
     * Search SAP customers using the same AccMast source pattern.
     */
    public function searchCustomers(?string $search): array;

    /**
     * Resolve a single SAP customer by customer code.
     */
    public function findCustomerByCode(?string $customerCode): ?array;
}
