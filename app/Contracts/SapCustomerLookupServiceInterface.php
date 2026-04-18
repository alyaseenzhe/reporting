<?php

namespace App\Contracts;

interface SapCustomerLookupServiceInterface
{
    /**
     * Search SAP customers using the same AccMast source pattern.
     */
    public function searchCustomers(?string $search, array $customerCodePrefixes = [], ?int $limit = 50): array;

    /**
     * Search SAP customers and return customer details needed by filtered forms.
     */
    public function searchCustomerRows(?string $search, array $customerCodePrefixes = [], ?int $limit = 50): array;

    /**
     * Resolve a single SAP customer by customer code.
     */
    public function findCustomerByCode(?string $customerCode): ?array;
}
