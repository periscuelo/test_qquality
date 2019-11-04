<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashController extends Controller
{
    /**
     * Show the application.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $data = $request->all();

        if (!empty($data)) {
            $convertBills = explode(',', $data['bills']);
            $bills = array_map('intval', $convertBills);
            $withdrawal = (int) $data['withdrawal'];

            $data['results'] = $this->calculate($bills, $withdrawal);
        }

        return view('cash', ['data' => $data]);
    }

    /**
     * checkBill
     *
     * Checks if bill is a integer and in range of 1 at 1000
     *
     * @param  integer $bill
     *
     * @return boolean
     */
    public function checkBill($bill) {
        return !(!is_int($bill) || $bill < 1 || $bill > 1000);
    }

    /**
     * checkMinValue
     *
     * Check if the rest of division is 0
     *
     * @param  integer $value
     * @param  integer $divisor
     *
     * @return boolean
     */
    public function checkMinValue($value, $divisor) {
        return ($value % $divisor === 0);
    }

    /**
     * calculate
     *
     * Does the calc for know how much bills is necessary for the withdrawal
     *
     * @param  array $enabledBills
     * @param  int $withdrawal
     *
     * @return array
     */
    public function calculate($enabledBills, $withdrawal) {
        $totalBills = []; // will be the result
        $sum = 0;
        $err = '';

        $minDivisor = min($enabledBills);

        // order by desc to check the sum
        rsort($enabledBills);

        $checkBills = array_map('Self::checkBill', $enabledBills);
        $testBills = array_search(false, $checkBills);

        // check if the bills is in the range
        if ($testBills === false) {
            // check if the withdrawal can be done
            if ($this->checkMinValue($withdrawal, $minDivisor)) {
                foreach($enabledBills AS $value) {
                    $i = 1;
                    while(($value*$i) <= $withdrawal && $sum <= $withdrawal && (($sum + $value) <= $withdrawal)) {
                        $totalBills[$value] = $i;
                        $sum += $value;
                        $i++;
                    }
                }
            } else {
                $err = "O valor do saque deve ser multiplo de $minDivisor!";
            }
        } else {
            $err = 'O valor das notas é inválido. Precisa ser um valor inteiro entre 1 e 1000!';
        }

        return [
            'errors' => $err,
            'totalBills' => $totalBills
        ];
    }
}
