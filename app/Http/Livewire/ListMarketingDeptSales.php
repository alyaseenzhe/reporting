<?php

namespace App\Http\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListMarketingDeptSales extends Component
{
    private const REPORT_PERMISSION = 'list.marketing-depts-sales';

    private const MARKETING_GROUPS = [
        'QryGroup30',
        'QryGroup31',
        'QryGroup32',
        'QryGroup40',
        'QryGroup41',
        'QryGroup50',
        'QryGroup51',
        'QryGroup52',
        'QryGroup53',
    ];

    private const BRANCH_DEPARTMENTS = [
        '2' => "'0001'",
        '3' => "'0101'",
        '10' => "'0102'",
        '7' => "'0103'",
        '13' => "'0104'",
        '4' => "'0105'",
        '6' => "'0106'",
        '5' => "'0107'",
        '12' => "'0108'",
        '11' => "'0109'",
        '9' => "'0110'",
        '8' => "'0111'",
        '505' => "'0112'",
        '15' => "'0201'",
        '500' => "'0202'",
        '504' => "'0203'",
    ];

    public $start_date;
    public $end_date;
    public $branches = [];
    public $allowed_marketing_types = [];
    public $sap_results = [];
    public $show_msg = false;
    public   $current_year;
    public   $previous_year;
    public $percentage;

    protected $listeners = ['create-report' => 'generateReport'];

    protected $rules = [
        'start_date' => 'required|date|after_or_equal:2024-01-01',
        'end_date' => 'required|date|after_or_equal:2024-01-01',
    ];

    protected $messages = [
        'start_date.required' => 'مطلوب',
        'start_date.after_or_equal' => 'يجب ان يكون التاريخ اعلى او يساوي 2024-01-01',
        'end_date.required' => 'مطلوب',
        'end_date.after_or_equal' => 'يجب ان يكون التاريخ اعلى او يساوي 2024-01-01',
    ];

    public function booted()
    {
        $user = Auth::user();

        if ($user->is_active == '0') {
            return redirect()->route('non-active-user');
        }

        if ($user->role == 'a') {
            return;
        }

        $reportTypes = $user->user_group
            ? json_decode($user->user_group->report_type)
            : [];

        if (in_array(self::REPORT_PERMISSION, $reportTypes ?: [])) {
            return;
        }

        return redirect()->route('dashboard');
    }

    public function mount()
    {
        $user = User::where('id', Auth::id())->first();
        $this->branches = array_values(array_map('strval', json_decode($user->branches ?? '[]', true) ?? []));
        $this->allowed_marketing_types = $this->resolveAllowedMarketingTypes($user);

    }

    public function render()
    {
        return view('livewire.list-marketing-dept-sales')
            ->layout('layouts.dashboard');
    }

    public function calculatePercentage($previous, $current){
        $this->percentage =  number_format(($current / $previous *100) -1, 2);
        return   $this->percentage ;
    }

    public function generateReport($start_date, $end_date, $depts, $marketingTypes = null)
    {

        set_time_limit(2000);

        $this->emit('show-container');
        $this->sapQuery($start_date, $end_date, $depts, $marketingTypes);


        $this->show_msg = true;
        $this->emit('finished');
    }

    public function sapQuery($start_date, $end_date, $depts, $marketingTypes = null)
    {
        $dateRange = $this->buildDateRange($start_date, $end_date);
        $departments = $this->resolveDepartments($depts);
        $marketingGroups = $this->resolveMarketingGroups($marketingTypes);

        $this->sap_results = [];

        if (! count($departments) || ! count($marketingGroups)) {
            return;
        }

        $conn = $this->connectToSap();

        if (! $conn) {
            return;
        }

        $sql =   $this->buildMarketingDepartmentSalesSql($dateRange, $departments, $marketingGroups);

        $result = odbc_exec($conn, $sql );
//        dd($sql);

        if (! $result) {
            echo "Error while sending SQL statement to the database server.\n";
            echo 'ODBC error code: ' . odbc_error() . '. Message: ' . odbc_errormsg();

            odbc_close($conn);

            return;
        }

        while ($row = odbc_fetch_array($result)) {
            $this->sap_results[] = $row;
        }

        odbc_close($conn);
    }

    private function buildDateRange($startDate, $endDate)
    {
        $this->current_year  =  Carbon::parse( $endDate)->addYears(-1)->addDay()->format('Y-m-d');
        $this->previous_year = Carbon::parse($this->current_year)->addYears(-1)->format('Y-m-d');

        return [
            'start' => $startDate,
            'end' => $endDate,
            'previous_start' => Carbon::parse($startDate)->addYears(-1)->format('Y-m-d'),
            'previous_end' => Carbon::parse($endDate)->addYears(-1)->format('Y-m-d'),
//            'current_year' => Carbon::parse($startDate)->addYears(-1)->addMonth()->format('Y-m-d'),
            'current_year' => $this->current_year,
            'previous_year' => $this->previous_year,
            'previous_year_end' => Carbon::parse($this->current_year)->addDays(-1)->format('Y-m-d')
        ];
    }

    private function resolveDepartments($departments)
    {
        $departments = (array) $departments;

        if (! in_array('dept_all', $departments)) {
            return $departments;
        }

        return array_intersect_key(
            self::BRANCH_DEPARTMENTS,
            array_flip($this->branches ?: [])
        );
    }

    private function connectToSap()
    {
        if (! extension_loaded('odbc')) {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');
        $host = env('DB_HOST_FOURTH');
        $dbName = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect(
            "Driver=$driver;ServerNode=$host;Database=$dbName;char_as_utf8=true;",
            $username,
            $password,
            SQL_CUR_USE_ODBC
        );

        if ($conn) {
            return $conn;
        }

        echo "Connection failed.\n";
        echo 'ODBC error code: ' . odbc_error() . '. Message: ' . odbc_errormsg();

        return false;
    }

    private function buildMarketingDepartmentSalesSql(array $dateRange, array $departments, array $marketingGroups)
    {
        $departmentSql = implode(', ', $departments);
        $branchRowsSql = $this->buildMarketingGroupUnion($dateRange, null, true, $marketingGroups);
        $totalRowsSql = $this->buildMarketingGroupUnion($dateRange, $departmentSql, false, $marketingGroups);

        return <<<SQL
SELECT * FROM (
{$branchRowsSql}
) tbl1
LEFT JOIN (
----------------------------------

{$totalRowsSql}

----------------------------------
) tbl2
ON tbl1."mrkt_type" = tbl2."mrkt_type"

WHERE tbl1."BranchRegistrationNumber" IN ({$departmentSql})
ORDER BY tbl1."mrkt_type", tbl1."BranchCode", tbl1."BranchRegistrationNumber"
SQL;
    }

    private function buildMarketingGroupUnion(array $dateRange, $departmentSql, $includeBranchColumns, array $marketingGroups)
    {
//        dd($dateRange['current_year']);
        $selects = [];

        foreach ($marketingGroups as $group) {
            $selects[] = $this->buildMarketingGroupSelect(
                $group,
                $dateRange,
                $departmentSql,
                $includeBranchColumns
            );
        }

        return implode("\n\nUNION ALL\n\n", $selects);
    }

    private function resolveMarketingGroups($marketingTypes): array
    {
        $allowedMarketingTypes = $this->allowed_marketing_types;

        if (Auth::user()?->role === 'a') {
            $allowedMarketingTypes = self::MARKETING_GROUPS;
        }

        $marketingTypes = array_values(array_unique(array_filter(
            array_map('strval', (array) $marketingTypes)
        )));

        if (! count($marketingTypes) || in_array('marketing_all', $marketingTypes, true)) {
            return $allowedMarketingTypes;
        }

        return array_values(array_intersect($allowedMarketingTypes, $marketingTypes));
    }

    private function resolveAllowedMarketingTypes(?User $user): array
    {
        if (! $user) {
            return [];
        }

        if ($user->role === 'a') {
            return self::MARKETING_GROUPS;
        }

        $assignedMarketingTypes = array_values(array_unique(array_filter(
            array_map('strval', json_decode($user->mrkt_types ?? '[]', true) ?? [])
        )));

        return array_values(array_intersect(self::MARKETING_GROUPS, $assignedMarketingTypes));
    }

    public function marketingTypeOptions(): array
    {
        return collect($this->allowed_marketing_types)
            ->mapWithKeys(fn (string $marketingType): array => [
                $marketingType => $this->marketingTypeLabel($marketingType),
            ])
            ->toArray();
    }

    private function marketingTypeLabel(string $marketingType): string
    {
        return [
            'QryGroup30' => 'ادارة فنية - الاسمدة م1',
            'QryGroup31' => 'ادارة فنية - المبيدات م1',
            'QryGroup32' => 'ادارة فنية - البذور م1',
            'QryGroup40' => 'اقسام تسويقية - الحدائق والصحة العامة',
            'QryGroup41' => 'اقسام تسويقية - المكافحة المتكاملة',
            'QryGroup50' => 'تقنيات الزراعة - الاليات',
            'QryGroup51' => 'تقنيات الزراعة - الري',
            'QryGroup52' => 'تقنيات الزراعة - الري المطري',
            'QryGroup53' => 'تقنيات الزراعة - الخدمات',
        ][$marketingType] ?? $marketingType;
    }

    private function buildMarketingGroupSelect($group, array $dateRange, $departmentSql, $includeBranchColumns)
    {
        $selectColumns = $includeBranchColumns
            ? "\"BranchName\", \"BranchCode\", \"BranchRegistrationNumber\", '{$group}' as \"mrkt_type\""
            : "'{$group}' as \"mrkt_type\"";

        $currentAlias = $includeBranchColumns ? 'CurrentMonth' : 'CurrentMonth_total';
        $previousAlias = $includeBranchColumns ? 'PreviousMonth' : 'PreviousMonth_total';
        $yearAlias = $includeBranchColumns ? 'CurrentYear' : 'CurrentYear_total';
        $previousYearAlias = $includeBranchColumns ? 'PreviousYear' : 'PreviousYear_total';
        $branchFilter = $departmentSql ? "\nAND \"BranchRegistrationNumber\" IN ({$departmentSql})" : '';
        $groupBy = $includeBranchColumns
            ? "\nGROUP BY \"BranchName\", \"BranchCode\", \"BranchRegistrationNumber\""
            : '';

        return <<<SQL
SELECT {$selectColumns}, SUM(CASE WHEN "DocumentDate" >= '{$dateRange['start']}'
                                AND "DocumentDate" <= '{$dateRange['end']}'
    THEN "NetSalesAmountLC" END) as "{$currentAlias}",
SUM(CASE WHEN "DocumentDate" >= '{$dateRange['previous_start']}'
                  AND "DocumentDate" <= '{$dateRange['previous_end']}'
    THEN "NetSalesAmountLC" END) as "{$previousAlias}",
SUM(CASE WHEN "DocumentDate" >= '{$dateRange['current_year']}'
                  AND "DocumentDate" <= '{$dateRange['end']}'
    THEN "NetSalesAmountLC" END) as "{$yearAlias}",
SUM(CASE WHEN "DocumentDate" >= '{$dateRange['previous_year']}'
                  AND "DocumentDate" <= '{$dateRange['previous_year_end']}'
    THEN "NetSalesAmountLC" END) as "{$previousYearAlias}"
FROM (

{$this->buildSalesAnalysisSubquery($group, $dateRange)}
)
WHERE "BranchCode" IS NOT NULL{$branchFilter}
AND "InvType" IS NULL{$groupBy}
SQL;
    }

    private function buildSalesAnalysisSubquery($group, array $dateRange)
    {
        return <<<SQL
SELECT (SELECT TBL0."DocNum" FROM AL_YASEEN_AGRI_PLIVE.ODPI TBL0
    INNER JOIN AL_YASEEN_AGRI_PLIVE.DPI1 TBL1 ON TBL0."DocEntry" = TBL1."DocEntry"
    LEFT JOIN AL_YASEEN_AGRI_PLIVE.RIN1 TBL2 ON TBL2."BaseEntry" = TBL1."DocEntry"
                                                    AND TBL2."BaseLine" = TBL1."LineNum" AND TBL2."BaseType" = 203
    LEFT JOIN AL_YASEEN_AGRI_PLIVE.ORIN TBL3 ON TBL2."DocEntry" = TBL3."DocEntry"
                             WHERE TBL3."DocNum" = T1."DocumentNumber" AND TBL2."BaseType" = 203
                             GROUP BY TBL0."DocNum") as "InvType",T1.* FROM (
Select "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName",
SUM("GrossProfitSC") AS "GrossProfitSC",
SUM("GrossProfitBaseAmountLC") AS "GrossProfitBaseAmountLC", SUM("NetSalesAmountLC") AS "NetSalesAmountLC",
SUM("NetSalesAmountSC") AS "NetSalesAmountSC", SUM("GrossProfitMarginByBaseAmount") AS "GrossProfitMarginByBaseAmount",
SUM("GrossProfitLC") AS "GrossProfitLC", SUM("QuantityInInventoryUoM") AS "QuantityInInventoryUoM",
SUM("GrossProfitMarginBySalesAmount") AS "GrossProfitMarginBySalesAmount"

FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/SalesAnalysisQuery"
--WHERE "DocumentDate" >= '{$dateRange['previous_start']}' AND "DocumentDate" <= '{$dateRange['end']}'
WHERE "DocumentDate" >= '{$dateRange['previous_year']}' AND "DocumentDate" <= '{$dateRange['end']}'
AND ("DocumentTypeCode" != '17' AND "DocumentTypeCode" != '15')

GROUP BY "BranchName", "BranchCode", "BranchRegistrationNumber",
"BusinessPartnerNameAndCode", "BusinessPartnerType", "BusinessPartnerGroupName","BusinessPartnerName", "BusinessPartnerCode",
"CancellationStatus", "DocumentDate",
"DocumentNumber", "DocumentTypeCode", "DocumentTypeShortName", "ItemDescriptionAndCode",
"ItemGroup", "DefaultPreferredVendor", "ItemCode", "ItemDescription",
"SalesEmployeeOrBuyerNumber", "SalesEmployeeOrBuyerName") T1
RIGHT JOIN AL_YASEEN_AGRI_PLIVE.OITM T2
ON T1."ItemCode" = T2."ItemCode"
WHERE T2."{$group}" = 'Y'
SQL;

    }
}
