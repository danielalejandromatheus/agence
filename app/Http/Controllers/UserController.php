<?php

namespace App\Http\Controllers;

use App\Http\Resources\EarningsResource;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function consultors(){
        $consultors = User::whereHas('permission', function($q){
            return $q->where('CO_SISTEMA',1)->where('IN_ATIVO','S')->whereIn('CO_TIPO_USUARIO',[0,1,2]);
        })->get();
        return Inertia::render('Consultor', [
            'consultors'=>$consultors->groupBy(function ($item){
                return $item->no_usuario[0];
            })->sortKeys()
        ]);
    }

    public function earnings(Request $request){
        return $this->showAll(EarningsResource::collection(User::whereIn('co_usuario', json_decode(base64_decode($request->q)))->get()));
    }

    public function netEarningsBar(Request $request){
        $users = json_decode(base64_decode($request->q));
        $invoices= Invoice::whereHas('service',function(Builder $q) use($users){
            return $q->whereIn('co_usuario', $users);
        })->with('service.user.salary')->get()->groupBy(function($val) use($users){
            return Carbon::parse($val->data_emissao)->format('m/Y');
        })->map(function ($month){
            return $month->mapToGroups(function($item){
                return[
                    $item->service->co_usuario => ['net_earnings' => $item->valor - ($item->valor * $item->total_imp_inc/100),
                    'set_cost'=>$item->service->user->salary ? $item->service->user->salary->brut_salario:0]
                ];
            })->map(function($i){
                return ['net_earnings' => $i->sum('net_earnings'), 'set_cost'=> $i->sum('set_cost')/count($i)];
            });
        })->map(function($month) use($users){
            $intersect = Collection::make($users)->diff($month->keys())->values()->flatMap(fn($i)=>[$i=> ['net_earnings'=>0,'set_cost'=>0]]);
            return $month->merge($intersect);
        });
        if($invoices->count()>0){
            $median = $invoices->sum(fn($invoice)=> $invoice->median('set_cost'))/$invoices->count();
            $invoices = $invoices->map(fn($i)=> $i->map(fn($i)=>$i['net_earnings']));
            $invoices->put('set_cost_median', $median);
        }else{
            $invoices->put('set_cost_median',0);
        }

        return $this->showAll($invoices);
    }
    public function netEarningsPie(Request $request){
        $users = json_decode(base64_decode($request->q));
        $earnings = User::whereIn('co_usuario', $users)->with('invoices')->get()->map(function($user){
            return[
                $user->co_usuario => $user->invoices->sum(function($invoice){
                    return $invoice->valor - ($invoice->valor * $invoice->total_imp_inc/100);
                })
            ];
        });
        return $this->showAll($earnings);
    }
}
