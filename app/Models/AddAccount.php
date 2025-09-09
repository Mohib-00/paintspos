<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AddAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_type',
        'sub_head_1',
        'sub_head_2',
        'head_name',
        'sub_head_name',
        'child',
        'opening',
    ];

    public function grnAccounts()
{
    return $this->hasMany(GrnAccount::class, 'vendor_account_id');
}

}
