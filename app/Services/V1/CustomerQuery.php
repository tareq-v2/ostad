<?php
namespace App\Services\V1;

use Illuminate\Http\Request;

class CustomerQuery {
    protected $safeParms = [
        'name' => ['eq'],
        'type' => ['eq'],
        'email' => ['eq'],
        'address' => ['eq'],
        'city' => ['eq'],
        'state' => ['eq'],
        'postalCode' => ['eq', 'gt', 'lt'],
    ];

    protected $columnMap = [
        'postalCode' => 'postal_code'
    ];

    protected $operatorMap = [
        'eq' => '=',
        'gt' => '>',
        'gte'=> '>=',
        'lt' => '<',
        'lte'=> '<='
    ];

    public function transform(Request $request)
    {
        $eloQuery = [];
        foreach($this->safeParms as $parms => $operators){
            $query = $request->query($parms);

            if(!isset($query)){
                continue;
            }

            $column = $this->columnMap[$parms] ?? $parms;

            foreach ($operators as $operator){
                if(isset($query[$operator])){
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }
        return $eloQuery;
    }
}
