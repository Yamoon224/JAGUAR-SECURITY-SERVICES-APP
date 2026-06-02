<?php

    use App\Models\Bill;
    use App\Models\Affectation;

    /**
     * @return Route : return route url
     */
    if (!function_exists('getDashboardRoute')) {     
        function getDashboardRoute() {
            switch (auth()->user()->group_id) {
                case 1:
                    $intended = route('dashboard', app()->getLocale());
                    break;
    
                case 2:
                    $intended = route('accountant', app()->getLocale());
                    break;
    
                case 3:
                    $intended = route('humanresource', app()->getLocale());
                    break;
    
                case 4:
                    $intended = route('secretary', app()->getLocale());
                    break;
                    
                default:
                    $intended = route('logistic', app()->getLocale());
            }

            return $intended;
        }
    }


    /**
     * @param $money|string : The amount to format
     * @param $sep|string : The separator who need to replace
     * @param $currency|string : The currency of the money
     * @return string : return a string 
     */
    if (!function_exists('moneyFormat')) {
        function moneyFormat($amount, string $sep = ".", string $currency = "GNF") {
    
            // Sécurité
            if (!is_numeric($amount)) {
                $amount = 0;
            }
    
            // ✅ Format selon devise
            if ($currency === 'USD') {
                // USD → 2 décimales
                $formatted = number_format($amount, 2, '.', ',');
                return $formatted . ' USD';
            }
    
            // GNF (par défaut) → pas de décimales
            $formatted = number_format($amount, 0, ',', $sep);
            return $formatted . ' GNF';
        }
    }

    /**
     * @param $monthIndex|int : Index Of Month 
     * @return string : return Month Name 
     */
    if (!function_exists('getMonthName')) {
        function getMonthName($monthIndex) {
            $months = array(
                '1'=>'january',
                '2'=>'february',
                '3'=>'march',
                '4'=>'april',
                '5'=>'may',
                '6'=>'june',
                '7'=>'july',
                '8'=>'august',
                '9'=>'september',
                '10'=>'october',
                '11'=>'november',
                '12'=>'december',
            );
            // return strtolower(date('F', mktime(0, 0, 0, $monthIndex)));
            return $months[(int) $monthIndex];
        }   
    }
    
    /**
     * @param $authorizeds|array : Group Users List Authorized
     * @return boolean : return True Or False
     */
    if (!function_exists('isRightAccess')) {
        function isRightAccess($authorizeds) {
            return in_array(auth()->user()->group_id, $authorizeds) ? true : false;
        }   
    }

    /**
     * @param $customerId|int : Customer Id
     * @param $monthIndex|int : Index Of Month
     * @return double : return Sum Of Price 
     */
    if (!function_exists('getBillAmount')) {
        function getBillAmount($customerId, $monthIndx, $year) {
            if($monthIndx < date('m') && $year == date('Y'))
                return Bill::where('month_id', '=', $monthIndx)
                    ->where('customer_id', $customerId)
                    ->sum('amount');
                    
            return Affectation::with([ 'employee' => fn ($item) => $item->where('deleted', 0) ])
                ->whereDate('end', '>=', date($year. '-' .$monthIndx. '-d'))
                ->whereDate('begin', '<=', date($year. '-' .$monthIndx. '-d'))
                ->where('customer_id', $customerId)
                ->sum('price');
        }   
    }
    
    /**
     * @param $customerId|int : Customer Id
     * @param $monthIndex|int : Index Of Month
     * @return double : return Sum Of Price 
     */
    if (!function_exists('getCustomerTax')) {
        function getCustomerTax($customerId, $monthIndex) {
            return Affectation::with([ 'employee' => fn ($item) => $item->where(['deleted'=>0]) ])
                ->whereDate('end', '>=', date('Y-'.$monthIndex.'-d'))
                ->whereDate('begin', '<=', date('Y-'.$monthIndex.'-d'))
                ->where('customer_id', $customerId)
                ->sum(fn($affectation) => $affectation->price*$affectation->tva*0.01);
        }   
    }


