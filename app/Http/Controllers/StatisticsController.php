<?php

namespace App\Http\Controllers;

use App\Account;
use App\MovementCategory;
use App\User;
use App\Charts\statisticsChart;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function totalBalance($user)
    {
        $count = User::query()->where('id','=',$user)->count();

        if($count > 0)
        {
            $balance = Account::query()->where('owner_id', '=', $user)->sum('current_balance');
            $accounts = Account::join('account_types','account_types.id','=','accounts.account_type_id')
                ->select('accounts.*', 'account_types.name')
                ->where('accounts.owner_id','=',$user)->get();
        }else{
            return response(view('errors.404'),404);
        }

        if($user == Auth::user()->id) {
            return view('statistics.statistics', compact('balance', 'accounts'));
        }else{
            return response(view('errors.403'),403);
        }
    }

    public function valuesForm()
    {
        $categories = MovementCategory::all();
        return view('statistics.statisticsGraphValues', compact('categories'));
    }

    public function graphics(Request $request)
    {
        $categories = MovementCategory::all();

        //dd($request);
        $data = $request->validate([
            'initial_date' => 'required|date|date_format:Y-m-d',
            'final_date' => 'required|date|date_format:Y-m-d',
            'category_id' => 'required|exists:movement_categories,id'
        ]);

        $initial_date = $data['initial_date'];
        $final_date = $data['final_date'];
        $category_id = $data['category_id'];

        //dd($initial_date);

        $accounts = Auth::user()->accounts()->get();

        foreach ($accounts as $account)
        {
            $movements_collections[] = $account->movements()->get();
        }
        //dd($movements_collections);

        $movements = [];
        foreach ($movements_collections as $collection)
        {
            foreach ($collection as $movement)
            {
                if (intval($category_id) == intval($movement->movementCategory()->first()->id) && $movement->whereBetween('date', array($initial_date, $final_date)))
                {
                    array_push($movements, $movement);
                }
            }
        }

        // datas de cada movimento ordenadas por ordem crescente
        $dates = [];
        // valores de cada movimento(somados por data) por ordem das datas
        $values = [];
        foreach ($movements as $movement)
        {
            if(!in_array($movement->date, $dates))
            {
                array_push($dates, $movement->date);
                array_push($values, $movement->value);
            } else {
                $key = array_search($movement->date, $dates);
                $values[$key] = $values[$key] + $movement->value;
            }
        }

//        dd($dates, $values);

        //$categories = Movement_category::orderBy('id')->pluck('name')->toArray();
        $chart = new statisticsChart();
            $chart->dataset('Summary values', 'bar',$values);
            $chart->title('Monthly Statistics');
            $chart->labels($dates);

            // nome da categoria (tipo)

            return view('statistics.statisticsGraph', compact('chart', 'categories'));

    }
}
/*$movements = Movement::select('movement_category_id', \DB::raw('SUM(value) as valor'))
    ->where('movements.account_id', '=', 31)
    ->groupBy('movement_category_id')
    ->orderBy('movement_category_id')
    ->pluck('valor')
    ->toArray();

//        if(array_key_exists($movement->movementCategory()->first()->name, $movements))
//                {
//                    $movements[$movement->movementCategory()->first()->name] = $movements[$movement->movementCategory()->first()->name] + $movement->value;
//                } else {
//                    $movements[$movement->movementCategory()->first()->name] = $movement->value;
//                }

//        dd($movements);
        //$movements = Movement::query()->where('account_id', '=', 23 )->get();

        //groupBy('month(date)', 'year(date)')->selectRaw('sum(value) as value')->whereIn('account_id', $accounts)->where('movement_category_id', '=', 102)->get();
//        $movements = Movement::select('movement_category_id', \DB::raw('SUM(value) as valor'))
//            ->where('movements.account_id', '=', 31)
//            ->groupBy('movement_category_id')
//            ->orderBy('movement_category_id')
//            ->pluck('valor')
//            ->toArray();