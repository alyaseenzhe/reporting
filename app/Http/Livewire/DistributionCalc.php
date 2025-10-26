<?php

namespace App\Http\Livewire;

use App\Models\Products;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DistributionCalc extends Component
{
    public $product_code;
    public $start_month;
    public $end_month;
    public $period;
    public $container_size;

    public $results;

    protected $rules = [
        'product_code' =>'required',
        'start_month' =>'required',
        'end_month' =>'required',
    ];

    protected $messages = [
        'product_code.required' => "مطلوب",
        'start_month.required' => "مطلوب",
        'end_month.required' => "مطلوب",
    ];

    protected $listeners = ['create-report' => 'createReport'];

    /**
     * The `booted` method is a Livewire lifecycle hook that runs after the component
     * is instantiated and before the `mount` method. It is commonly used for
     * authorization and access control. This function first checks if the authenticated
     * user is active. If not, it redirects them. It then checks if the user's
     * permissions (via their group or role) allow them to view this page. If not,
     * they are redirected to the dashboard.
     */
    public function booted() {

        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group && in_array('list.distribution-calc', json_decode(Auth::user()->user_group->report_type))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * The `render` method is the core of a Livewire component. It is responsible for
     * returning the view that will be rendered to the user. This function specifies
     * the `livewire.distribution-calc` view and applies the `layouts.dashboard` layout
     * to it.
     */
    public function render()
    {
        return view('livewire.distribution-calc')
            ->layout('layouts.dashboard');
    }

    /**
     * Creates a detailed report of product targets for a given product and date range.
     *
     * This function dynamically generates a SQL query to retrieve and pivot product target data.
     * It iterates through a period of months and constructs a `SELECT` statement that includes
     * a `MAX(CASE WHEN ...)` clause for each month and each branch, effectively transforming
     * the row-based target data into a column-based report. The final query joins product data
     * with the target data, groups the results, and returns them.
     * The results are stored in the `$results` property, and the `container_size` is determined
     * from the first result. Finally, it emits a Livewire event to update the front-end.
     *
     * @param string $selected_product_code The product code for which to create the report.
     * @param string $start_month The starting month of the report period (e.g., '2023-01').
     * @param string $end_month The ending month of the report period (e.g., '2023-03').
     */
    public function createReport($selected_product_code, $start_month, $end_month) {

        $this->results = [];
        $this->container_size = null;
        $stmt = "";

        $this->period = new \Carbon\CarbonPeriod($start_month, '1 month', $end_month);
        $this->period = $this->period->toArray();
        $month_counter = 1;

        foreach ($this->period as $key => $month_n) {

            $stmt .= "MAX(CASE WHEN year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "' and branch = '3' THEN target ELSE '0' END) as 'target_".$month_n->format('n')."-".$month_n->format('Y')."_3',";
            $stmt .= "MAX(CASE WHEN year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "' and branch = '10' THEN target ELSE '0' END) as 'target_".$month_n->format('n')."-".$month_n->format('Y')."_10',";
            $stmt .= "MAX(CASE WHEN year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "' and branch = '7' THEN target ELSE '0' END) as 'target_".$month_n->format('n')."-".$month_n->format('Y')."_7',";
            $stmt .= "MAX(CASE WHEN year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "' and branch = '13' THEN target ELSE '0' END) as 'target_".$month_n->format('n')."-".$month_n->format('Y')."_13',";
            $stmt .= "MAX(CASE WHEN year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "' and branch = '4' THEN target ELSE '0' END) as 'target_".$month_n->format('n')."-".$month_n->format('Y')."_4',";
            $stmt .= "MAX(CASE WHEN year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "' and branch = '6' THEN target ELSE '0' END) as 'target_".$month_n->format('n')."-".$month_n->format('Y')."_6',";
            $stmt .= "MAX(CASE WHEN year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "' and branch = '5' THEN target ELSE '0' END) as 'target_".$month_n->format('n')."-".$month_n->format('Y')."_5',";
            $stmt .= "MAX(CASE WHEN year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "' and branch = '12' THEN target ELSE '0' END) as 'target_".$month_n->format('n')."-".$month_n->format('Y')."_12',";
            $stmt .= "MAX(CASE WHEN year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "' and branch = '11' THEN target ELSE '0' END) as 'target_".$month_n->format('n')."-".$month_n->format('Y')."_11',";
            $stmt .= "MAX(CASE WHEN year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "' and branch = '9' THEN target ELSE '0' END) as 'target_".$month_n->format('n')."-".$month_n->format('Y')."_9',";
            $stmt .= "MAX(CASE WHEN year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "' and branch = '8' THEN target ELSE '0' END) as 'target_".$month_n->format('n')."-".$month_n->format('Y')."_8',";
            $stmt .= "MAX(CASE WHEN year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "' and branch = '505' THEN target ELSE '0' END) as 'target_".$month_n->format('n')."-".$month_n->format('Y')."_505',";
            $stmt .= "CONCAT('".$month_n->format('Y')."', '-', '".$month_n->format('n')."') as 'month_".$month_counter."' ,";

            $month_counter++;

//            if (count($period) > 1) {
//                if ($key === array_key_first($period)) {
////                    $stmt .= "((year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "') or ";
//                } elseif ($key === array_key_last($period)) {
//                    $stmt .= "(year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "'))";
////                    $stmt .= "(year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "'))";
//                } else {
//                    $stmt .= "(year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "') or ";
////                    $stmt .= "(year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "') or ";
//                }
//            } else {
//                $stmt .= "(year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "')";
////                $stmt .= "(year ='" . $month_n->format('Y') . "' and month = '" . $month_n->format('n') . "')";
//            }
        }

        $stmt = rtrim($stmt, ', ');
//        dd($stmt);
//        dd('vendor_code, vendor_name, product_code, product_name, ' . $stmt);

        $this->results = Products::leftJoin('product_target_branch_totals', 'product_target_branch_totals.product_id', 'products.product_code')
//                                    ->where('product_code', $this->product_code)
            ->where('product_code', $selected_product_code)
            ->orWhere('products.sap_code', $selected_product_code)
            ->selectRaw('vendor_code, vendor_name, product_code, product_name, products.container_size, ' . $stmt)
            ->groupBy('vendor_code', 'vendor_name', 'product_code', 'product_name', 'products.container_size')
            ->get();



        if (count($this->results) > 0) {
            $this->container_size = $this->results ? $this->results[0]->container_size : 0;
        }
        $this->emit('show-container');

//        dd($this->results);
    }
}
