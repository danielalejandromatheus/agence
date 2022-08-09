<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EarningsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'co_usuario' => $this->co_usuario,
            'no_usuario' => $this->no_usuario,
            'ds_senha' => $this->ds_senha,
            'co_usuario_autorizacao' => $this->co_usuario_autorizacao,
            'nu_matricula' => $this->nu_matricula,
            'dt_nascimento' => $this->dt_nascimento,
            'dt_admissao_empresa' => $this->dt_admissao_empresa,
            'dt_desligamento' => $this->dt_desligamento,
            'dt_inclusao' => $this->dt_inclusao,
            'dt_expiracao' => $this->dt_expiracao,
            'nu_cpf' => $this->nu_cpf,
            'nu_rg' => $this->nu_rg,
            'no_orgao_emissor' => $this->no_orgao_emissor,
            'uf_orgao_emissor' => $this->uf_orgao_emissor,
            'ds_endereco' => $this->ds_endereco,
            'no_email' => $this->no_email,
            'no_email_pessoal' => $this->no_email_pessoal,
            'nu_telefone' => $this->nu_telefone,
            'dt_alteracao' => $this->dt_alteracao,
            'url_foto' => $this->url_foto,
            'instant_messenger' => $this->instant_messenger,
            'icq' => $this->icq,
            'msn' => $this->msn,
            'yms' => $this->yms,
            'ds_comp_end' => $this->ds_comp_end,
            'ds_bairro' => $this->ds_bairro,
            'nu_cep' => $this->nu_cep,
            'no_cidade' => $this->no_cidade,
            'uf_cidade' => $this->uf_cidade,
            'dt_expedicao' => $this->dt_expedicao,
            'set_cost'=> $set_cost = $this->salary ? $this->salary->brut_salario: 0,
            'invoices'=> $this->invoices? $this->invoices->sortBy('data_emissao')->groupBy(function($val) {
                return Carbon::parse($val->data_emissao)->format('m/Y');
            })->map(function($month) use($set_cost){
                return [
                    'net_earning'=> $net_earning = $month->sum(function($invoice){
                        return $invoice->valor - ($invoice->valor * $invoice->total_imp_inc/100);
                    }),
                    'commission'=>$comission = $month->sum(function($invoice){
                        return ($invoice->valor - ($invoice->valor * $invoice->total_imp_inc/100)) * $invoice->comissao_cn / 100;

                    }),
                    'profit' => $net_earning - ($set_cost + $comission)
                ];
            }):[]
        ];
    }
}
