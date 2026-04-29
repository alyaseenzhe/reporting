<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
//use Livewire\WithPagination;
//use Illuminate\Pagination\LengthAwarePaginator;

class ListItems extends Component
{
    private const MARKETING_TYPE_OPTIONS = [
        '30' => 'ادارة فنية - الاسمدة م1',
        '31' => 'ادارة فنية - المبيدات م1',
        '32' => 'ادارة فنية - البذور م1',
        '40' => 'اقسام تسويقية - الحدائق والصحة العامة',
        '41' => 'اقسام تسويقية - المكافحة المتكاملة',
        '50' => 'الآليات والري - الآليات',
        '51' => 'الآليات والري - الري',
        '52' => 'الآليات والري - الري المطري',
        '53' => 'الآليات والري - الخدمات',
    ];

    public $start_date;
    public $end_date;
    public $dept_id = ['dept_all'];
    public $group_type = 'groups_all';
    public $cat_type = ['cat_all'];
    public $sp_type = ['sp_all'];
    public $vendor_type = 'vendor_all';
    public $search_type;
    public $product_code;
    public $catalog_number;
    public $marketing_type = ['marketing_all'];
    public $allowed_marketing_types = [];
    public $item_validity = ['valid'];
    public $customer_type='customer_all';
    public $emps_type ='employees_all';
    public $sortBy = 'ItemCode';
    public $sortDir = 'ASC';
    public $sap_codes = [];
    public $group_results;
    public $sap_results;
    public $totalSalesByItem;
    public $show_msg = false;
    public $branches = [];
    public $vendor_list = [];
    public $customer_list = [];
    public $catalog_numbers = [];
    public $emps = null;
    public $currentGroup = null, $currentItemName = null,
        $itemGroup_item_total = 0, $itemGroup_cost_total = 0, $itemGroup_gross_total = 0, $itemGroup_quantity_total = 0, $itemGroup_trans_total = 0,
        $itemGroup_item_subtotal = 0, $itemGroup_cost_subtotal = 0, $itemGroup_gross_subtotal = 0, $itemGroup_quantity_subtotal = 0, $itemGroup_trans_subtotal = 0,
        $itemGroup_itemName_subtotal = 0, $itemGroup_costName_subtotal = 0, $itemGroup_grossName_subtotal = 0;

    public $grouped;
    public $totalsByBranch;
    public $empKey;
    public $allBranches = [];
    public $salesIndex;
    public $employees = [];
    public $counter = 0,


        $item_code = "*",
        $item_group_code = "*",
        $item_group_itemCode_code = "*",
        $speciality_code = "*",
        $marketing_type_code = "*",
        $vendor_code = "*";
    public  $depts = ['0001' => '1', '0101' => '3', '0102' => '4', '0103' => '5', '0104' => '6', '0105' => '7', '0106' => '8', '0107' => '9', '0108' => '10', '0109' => '11', '0110' => '12', '0111' => '13', '0112' => '14', '0201' => '15', '0202' => '16', '0203' => '17'];
    public $expanded = [];
    public     $branchOptions = [

        '10' => [['0102', 'جدة']],
        '7'  => [['0103', 'الرياض']],
        '13' => [['0104', 'وادي الدواسر']],
        '4'  => [['0105', 'الجوف']],
        '6'  => [['0106', 'الدمام']],
        '5'  => [['0107', 'الخرج']],
        '12' => [['0108', 'نجران']],
        '11' => [['0109', 'حائل']],
        '9'  => [['0110', 'تبوك']],
        '8'  => [['0111', 'القصيم']],
        '505'=> [['0112', 'ساجر']],
        '14'=>'0201',
        '16'=>['0202'],
        '3' => [
            ['0101', 'الاحساء'],
            ['0201', 'مزرعة الدالوة'],
            ['0202', 'مزرعة الفضول'],
            ['0203', 'مزرعة الدلم'],
            ['0001', 'المركز الرئيسي'],

        ],
    ];

//    public function gotoPage($page)
//    {
//        $this->page = $page;
//
//    }
    public $all_option;


    protected $rules = [];

    protected $messages = [];
    public $query;

    public function mount(){

        $this->empKey =null;
        $this->product_lists();
        $this->employees();
        $this->vendors();
        $this->customers();
        $this->query = User::where('id', Auth::id())->first();
        $this->branches = json_decode($this->query->branches);
        $this->allowed_marketing_types = $this->resolveAllowedMarketingTypes($this->query);
        $this->marketing_type = $this->defaultMarketingTypes();
        $this->all_option = 'dept_id';
    }

    protected function normalizeMarketingTypeValues($marketingTypes): array
    {
        return array_values(array_unique(array_filter(array_map(function ($marketingType) {
            $marketingType = (string) $marketingType;

            if (strpos($marketingType, 'QryGroup') === 0) {
                $marketingType = substr($marketingType, strlen('QryGroup'));
            }

            return array_key_exists($marketingType, self::MARKETING_TYPE_OPTIONS)
                ? $marketingType
                : null;
        }, (array) $marketingTypes))));
    }

    protected function resolveAllowedMarketingTypes(?User $user): array
    {
        $allMarketingTypes = array_keys(self::MARKETING_TYPE_OPTIONS);

        if (! $user || $user->role === 'a') {
            return $allMarketingTypes;
        }

        $assignedMarketingTypes = $this->normalizeMarketingTypeValues(
            json_decode($user->mrkt_types ?? '[]', true) ?? []
        );

        return count($assignedMarketingTypes) ? $assignedMarketingTypes : $allMarketingTypes;
    }

    protected function userCanAccessAllMarketingTypes(): bool
    {
        return count($this->allowed_marketing_types) === count(self::MARKETING_TYPE_OPTIONS);
    }

    protected function defaultMarketingTypes(): array
    {
        return $this->userCanAccessAllMarketingTypes()
            ? ['marketing_all']
            : $this->allowed_marketing_types;
    }

    protected function resolveSelectedMarketingTypes($marketingTypes): array
    {
        $requestedMarketingTypes = (array) $marketingTypes;
        $selectedMarketingTypes = $this->normalizeMarketingTypeValues($requestedMarketingTypes);
        $allowedMarketingTypes = $this->allowed_marketing_types ?: array_keys(self::MARKETING_TYPE_OPTIONS);

        if ($this->userCanAccessAllMarketingTypes()) {
            if (in_array('marketing_all', $requestedMarketingTypes, true) || ! count($selectedMarketingTypes)) {
                return ['marketing_all'];
            }

            return array_values(array_intersect($allowedMarketingTypes, $selectedMarketingTypes));
        }

        if (in_array('marketing_all', $requestedMarketingTypes, true) || ! count($selectedMarketingTypes)) {
            return $allowedMarketingTypes;
        }

        return array_values(array_intersect($allowedMarketingTypes, $selectedMarketingTypes));
    }

    public function marketingTypeOptions(): array
    {
        $allowedMarketingTypes = $this->allowed_marketing_types ?: array_keys(self::MARKETING_TYPE_OPTIONS);

        return collect($allowedMarketingTypes)
            ->mapWithKeys(fn (string $marketingType): array => [
                $marketingType => self::MARKETING_TYPE_OPTIONS[$marketingType] ?? $marketingType,
            ])
            ->toArray();
    }

    public function generateReport()
    {
        $this->expanded = [];


        $this->resetExpandedData();
        foreach ($this->expanded as $itemCode => $expandedItems) {
            if ($expandedItems) {
                $this->loadBranches($itemCode);
            }
        }
//        $sql = '';        // RESET
//        $bindings = [];   // RESET
        // Now all values are AVAILABLE
//        dd([
//            $this->start_date,
//            $this->end_date,
//            $this->dept_id,
//            $this->group_type,
//            $this->cat_type,
//        ]);



        $this->create_report(
            $this->start_date,
            $this->end_date,
            $this->dept_id,
            $this->group_type ,
            $this->cat_type ,
            $this->sp_type ,
            $this->vendor_type ,
            $this->search_type,
            $this->product_code,
            $this->catalog_number,
            $this->marketing_type,
            $this->item_validity,
            $this->customer_type,
            $this->emps_type
        );
    }

//    public function paginatedResults()
//    {
//        $perPage = 10;
//        $page = request()->get('page', 1);
//
//        $items = collect($this->group_results);
//
//        return new LengthAwarePaginator(
//            $items->forPage($page, $perPage),
//            $items->count(),
//            $perPage,
//            $page,
//            ['path' => request()->url()]
//        );
//    }
    public function updatedSortDir($value)
    {
//        dd($value);
        logger('SORT:', [$this->sortDir]);
        $this->generateReport();
    }

    public function updatedSortBy($value)
    {
        logger('SORT:', [$this->sortBy]);
        $this->generateReport();
    }

    public function product_lists() {

        $this->products_codes = [];
        $this->catalog_numbers = [];

        if (! extension_loaded('odbc'))
        {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');
        $host = env('DB_HOST_FOURTH');
        $db_name = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);

        if (!$conn)
        {
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
        }
        else
        {
            $productQuery = 'SELECT DISTINCT T0."ItemCode",T0."U_UDF1" AS "ScribeCode", T0."ItemName", T0."SuppCatNum" AS "CatalogNumber" FROM AL_YASEEN_AGRI_PLIVE."OITM" T0 JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1 ON T0."ItmsGrpCod" = T1."ItmsGrpCod" WHERE (T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\' OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\' OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\' OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\' OR T0."ItemCode" LIKE \'30%\' OR T0."ItemCode" LIKE \'99%\')';

            $result = odbc_exec($conn, $productQuery);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {

                while ($row = odbc_fetch_array($result)) {
                    array_push($this->products_codes, $row);
                }

                $this->catalog_numbers = collect($this->products_codes)
                    ->pluck('CatalogNumber')
                    ->filter(fn ($catalogNumber) => ! empty($catalogNumber))
                    ->unique()
                    ->sort()
                    ->values()
                    ->all();
            }

//            dd($this->itemGrp);
            odbc_close($conn);
        }
    }
    // Component property

    protected function resetExpandedData()
    {
        $this->expanded = [];

    }



    public function render()
    {
        return view('livewire.items.list-items')->layout('layouts.dashboard');
    }

//    public function create_report(){
    public function resetProductCodes()
    {
        foreach ([
                     'product_codes',
                     'group_codes',
                     'category_codes',
                     'speciality_codes',
                     'vendor_codes',
                     'marketing_codes',
                     'selected_products'
                 ] as $property) {

            if (property_exists($this, $property)) {
                $this->{$property} = [];
            }
        }
    }

    public function create_report($start_date, $end_date, $dept_id, $group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $catalog_number, $marketing_type, $item_validity, $customer_type, $emps_type) {
        set_time_limit(2000);
        ini_set('memory_limit', '2048M');

        $this->show_msg = false;
        $this->marketing_type = $this->resolveSelectedMarketingTypes($marketing_type);
        $this->resetProductCodes();
        $this->sap_results = [];
        $this->group_results = [];

        $this->productCodes($group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $catalog_number, $this->marketing_type, $item_validity);
        $this->sapQuery($start_date, $end_date, $dept_id, $customer_type, $emps_type, $item_validity);

        $this->group_results = collect($this->sap_results)
            ->unique('ItemCode')
            ->sortBy('ItemCode')
            ->values()
            ->all();

        $this->show_msg = true;
        $this->emit('finished');
    }

    public function customers()
    {

        $this->customer_list = [];


        if (! extension_loaded('odbc'))
        {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');
        $host = env('DB_HOST_FOURTH');
        $db_name = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);

        if (!$conn)
        {
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
        }
        else
        {
//            dd($this->branches);
            $customer_depts = ['2' => '01%', '3' => '01%', '10' => '02%', '7' => '03%', '13' => '04%', '4' => '05%' , '6' => '06%', '5' => '07%', '12' => '08%', '11' => '09%', '9' => '10%', '8' => '11%', '505' => '12%', '15' => '01%', '500' => '01%', '504' => '01%'];
            $customer_codes = array_intersect_key($customer_depts, array_flip($this->branches));


            $query = implode(' OR ', array_map(function ($value) {
                return 'T0."CardCode" LIKE \'' . $value . '\'';
            }, $customer_codes));

            $customerQuery = 'SELECT T0."CardCode", T0."CardName",
       CASE
	WHEN "CardCode" LIKE \'01%\' THEN \'0101\'
	WHEN "CardCode" LIKE \'02%\' THEN \'0102\'
	WHEN "CardCode" LIKE \'03%\' THEN \'0103\'
	WHEN "CardCode" LIKE \'04%\' THEN \'0104\'
	WHEN "CardCode" LIKE \'05%\' THEN \'0105\'
	WHEN "CardCode" LIKE \'06%\' THEN \'0106\'
	WHEN "CardCode" LIKE \'07%\' THEN \'0107\'
	WHEN "CardCode" LIKE \'08%\' THEN \'0108\'
	WHEN "CardCode" LIKE \'09%\' THEN \'0109\'
	WHEN "CardCode" LIKE \'10%\' THEN \'0110\'
	WHEN "CardCode" LIKE \'11%\' THEN \'0111\'
	WHEN "CardCode" LIKE \'12%\' THEN \'0112\'
END AS "Dept"
FROM AL_YASEEN_AGRI_PLIVE.OCRD T0 WHERE T0."CardType" = \'C\' --AND ('.$query.')
ORDER BY "CardCode"';

//            dd($customerQuery);

            $result = odbc_exec($conn, $customerQuery);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {

                while ($row = odbc_fetch_array($result)) {
                    array_push($this->customer_list, $row);
                }
            }

//            dd($this->vendor_list);
            odbc_close($conn);
        }
    }


    protected function normalizeValidityFilter($item_validity)
    {
        $item_validity = array_values(array_filter((array) $item_validity));

        if (in_array('valid', $item_validity, true) && in_array('invalid', $item_validity, true)) {
            return [];
        }

        return $item_validity;
    }

    protected function buildValidityCondition($item_validity, $column = 'T0."validFor"')
    {
        $item_validity = $this->normalizeValidityFilter($item_validity);

        if ($item_validity === ['valid']) {
            return $column . ' = \'Y\'';
        }

        if ($item_validity === ['invalid']) {
            return $column . ' = \'N\'';
        }

        return null;
    }


    public function productCodes($group_type, $cat_type, $sp_type, $vendor_type, $search_type, $product_code, $catalog_number, $marketing_type, $item_validity)
    {
        // Clean filters
        $cat_type       = array_filter((array) $cat_type);
        $sp_type        = array_filter((array) $sp_type);
        $vendor_type    = array_filter((array) $vendor_type);
        $marketing_type = $this->resolveSelectedMarketingTypes($marketing_type);
        $item_validity  = $this->normalizeValidityFilter($item_validity);
        $group_type     = is_array($group_type) ? reset($group_type) : $group_type;

        if (count($cat_type) > 1) {
            $cat_type = array_values(array_diff($cat_type, ['cat_all']));
        }

        if (count($sp_type) > 1) {
            $sp_type = array_values(array_diff($sp_type, ['sp_all']));
        }

        if (count($vendor_type) > 1) {
            $vendor_type = array_values(array_diff($vendor_type, ['vendor_all']));
        }

        if (count($marketing_type) > 1) {
            $marketing_type = array_values(array_diff($marketing_type, ['marketing_all']));
        }


        $this->sap_codes = [];

        $driver   = env('DB_CONNECTION_FOURTH');
        $host     = env('DB_HOST_FOURTH');
        $db_name  = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect(
            "Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;",
            $username,
            $password,
            SQL_CUR_USE_ODBC
        );

        if (!$conn) {
            dd("ODBC Connection failed: " . odbc_errormsg());
        }

        $categoryQuery = '
        SELECT DISTINCT T0."ItemCode"
        FROM AL_YASEEN_AGRI_PLIVE."OITM" T0
        JOIN AL_YASEEN_AGRI_PLIVE."OITB" T1
            ON T0."ItmsGrpCod" = T1."ItmsGrpCod"
        WHERE T0."ItemType" = \'I\'
    ';

        // Conditions array
        $conditions = [];

        if (!empty($product_code)) {
            if (is_array($product_code)) {
                $escaped = array_map(fn($v) => "'" . str_replace("'", "''", $v) . "'", $product_code);
                $conditions[] = 'T0."ItemCode" IN (' . implode(',', $escaped) . ')';
            } else {
                $safe = "'" . str_replace("'", "''", $product_code) . "'";
                $conditions[] = "T0.\"ItemCode\" = $safe";
            }
        }

        if (!empty($catalog_number)) {
            if (is_array($catalog_number)) {
                $escapedCatalogNumbers = array_map(
                    fn ($catalogNumberValue) => "'" . str_replace("'", "''", $catalogNumberValue) . "'",
                    array_filter($catalog_number)
                );

                if (count($escapedCatalogNumbers) > 0) {
                    $conditions[] = 'T0."SuppCatNum" IN (' . implode(',', $escapedCatalogNumbers) . ')';
                }
            } else {
                $safeCatalogNumber = "'" . str_replace("'", "''", $catalog_number) . "'";
                $conditions[] = "T0.\"SuppCatNum\" = $safeCatalogNumber";
            }
        }

        if ($group_type === "commerce") {
            $conditions[] = '(T0."ItemCode" LIKE \'11%\' OR T0."ItemCode" LIKE \'12%\'
                          OR T0."ItemCode" LIKE \'13%\' OR T0."ItemCode" LIKE \'14%\'
                          OR T0."ItemCode" LIKE \'15%\' OR T0."ItemCode" LIKE \'16%\'
                          OR T0."ItemCode" LIKE \'28%\' OR T0."ItemCode" LIKE \'29%\')';
        }

        if (!empty($cat_type) && !in_array('cat_all', $cat_type, true)) {
            $conditions[] = 'T0."ItmsGrpCod" IN (' . implode(',', $cat_type) . ')';
        }

        if (!empty($sp_type) && !in_array('sp_all', $sp_type, true)) {
            $sp = array_map(fn($s) => 'T0."QryGroup' . (intval($s) + 1) . '" = \'Y\'', $sp_type);
            $conditions[] = '(' . implode(' OR ', $sp) . ')';
        }

        if (!empty($marketing_type) && !in_array('marketing_all', $marketing_type, true)) {
            $mk = array_map(fn($m) => 'T0."QryGroup' . intval($m) . '" = \'Y\'', $marketing_type);
            $conditions[] = '(' . implode(' OR ', $mk) . ')';
        }

        if (!empty($vendor_type) && !in_array('vendor_all', $vendor_type, true)) {
            $escapedVendors = array_map(
                fn ($vendor) => "'" . str_replace("'", "''", $vendor) . "'",
                $vendor_type
            );

            $conditions[] = 'T0."CardCode" IN (' . implode(',', $escapedVendors) . ')';
        }

        $validityCondition = $this->buildValidityCondition($item_validity);

        if ($validityCondition !== null) {
            $conditions[] = $validityCondition;
        }

        // Add conditions to query
        if (count($conditions) > 0) {
            $categoryQuery .= ' AND ' . implode(' AND ', $conditions);
        }

//        dd($categoryQuery);

        // Execute
        $result = odbc_exec($conn, $categoryQuery);

        while ($row = odbc_fetch_array($result)) {
            $this->sap_codes[] = "'" . $row['ItemCode'] . "'";
        }

        odbc_close($conn);
    }


    public function employees()
    {

        $this->emps = User::join('user_groups', 'user_groups.id', 'users.group')
//            ->where('branches', 'like', '%"'.$branch.'"%')
            ->where('sales_dept_code', '<>', '')
            ->where('is_active', '1')
            ->whereIn('write_product_target', ['1', '2'])
            ->select('users.id', 'users.emp_code', 'users.name', 'users.sales_dept_code')
            ->get();
        return $this->emps;
    }
    public function vendors() {

        $this->vendor_list = [];

        if (! extension_loaded('odbc'))
        {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');
        $host = env('DB_HOST_FOURTH');
        $db_name = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);

        if (!$conn)
        {
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
        }
        else
        {
//            $vendorQuery = 'SELECT "CardCode" AS "VendorCode", "CardName" AS "VendorName" FROM AL_YASEEN_AGRI_PLIVE.OCRD WHERE "CardType" = \'S\'';
            $vendorQuery = 'SELECT
                          "CardCode" AS "VendorCode",
                           "CardName" AS "VendorName"
                            FROM AL_YASEEN_AGRI_PLIVE.OCRD
                            WHERE "CardType" = \'S\'
                             AND "CardCode" LIKE \'99%\'';

            $result = odbc_exec($conn, $vendorQuery);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {

                while ($row = odbc_fetch_array($result)) {
                    array_push($this->vendor_list, $row);
                }
            }

//            dd($this->vendor_list);
            odbc_close($conn);
        }
    }
    public function sapQuery($start_date, $end_date, $departments, $customer_type, $emps_type, $item_validity)
    {
        if (count($this->sap_codes) <= 0) {
            return;
        }

        if (! extension_loaded('odbc')) {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');
        $host = env('DB_HOST_FOURTH');
        $db_name = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);

        if (! $conn) {
            echo "Connection failed.
";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();

            return;
        }

        $selectedItemCodes = implode(',', $this->sap_codes);
        $validityCondition = $this->buildValidityCondition($item_validity);
        $validitySql = $validityCondition ? "\n    AND {$validityCondition}" : '';

        $sql = <<<SQL
SELECT DISTINCT
    T0."ItemCode",
    T0."ItemName",
    T0."SuppCatNum" AS "CatalogNumber",
    T1."CardName" AS "VendorName",
    T2."UomCode",
    CASE
        WHEN T0."QryGroup1" = 'Y' THEN '0'
        WHEN T0."QryGroup2" = 'Y' THEN '1'
        WHEN T0."QryGroup3" = 'Y' THEN '2'
        ELSE ''
    END AS "Speciality",
    CASE
        WHEN T0."QryGroup30" = 'Y' THEN 'fan - asmedah 1'
        WHEN T0."QryGroup31" = 'Y' THEN 'fan - mobedat 1'
        WHEN T0."QryGroup32" = 'Y' THEN 'fan - bathoor 1'
        WHEN T0."QryGroup40" = 'Y' THEN 'tasweeg - sehah'
        WHEN T0."QryGroup41" = 'Y' THEN 'tasweeg - mokafahh'
        WHEN T0."QryGroup50" = 'Y' THEN 'aleyat - aleyat'
        WHEN T0."QryGroup51" = 'Y' THEN 'aleyat - ray'
        WHEN T0."QryGroup52" = 'Y' THEN 'aleyat - ray matary'
        WHEN T0."QryGroup53" = 'Y' THEN 'aleyat - khadamat'
        ELSE 'general'
    END AS "mrkt_type",
    T0."validFor"
FROM AL_YASEEN_AGRI_PLIVE.OITM T0
INNER JOIN AL_YASEEN_AGRI_PLIVE.OCRD T1 ON T0."CardCode" = T1."CardCode"
INNER JOIN AL_YASEEN_AGRI_PLIVE.OUOM T2 ON T0."PUoMEntry" = T2."UomEntry"
WHERE
   -- T0."ItemType" = 'I'
     T0."ItemCode" IN ({$selectedItemCodes})
{$validitySql}
ORDER BY T0."ItemCode"
SQL;

        $result = odbc_exec($conn, $sql);
        if (! $result) {
            echo "Error while sending SQL statement to the database server.
";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
        } else {
            while ($row = odbc_fetch_array($result)) {
                array_push($this->sap_results, $row);
            }
        }

        odbc_close($conn);
    }
}
