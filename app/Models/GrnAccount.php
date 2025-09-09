<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrnAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_account_id',
        'vendor_net_amount',
        'discount',
        'purchase_id',
        'sale_id',
        'debit',
        'complete',
        'grn',
        'payment',
        'voucher_id',
        'voucher',
        'jv',
        'status',
        'created_at',
        'updated_at',
        'salary',
        'salary_id',
        'salereturn',
        'purchasereturn',
        'raw_material_purchase_id',
        'fine_id',
    ];

    public function rawMaterialPurchase()
{
    return $this->belongsTo(RawmaterialPurchase::class, 'raw_material_purchase_id');
}

    public function vendorAccount()
    {
        return $this->belongsTo(AddAccount::class, 'vendor_account_id');
    }

    public function purchase()
{
    return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
}

public function sale()
{
    return $this->belongsTo(Sale::class, 'sale_id', 'id');
}

public function salary()
{
    return $this->belongsTo(Salary::class, 'salary_id');
}


public function voucher()
{
    return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
}

public function voucherItems()
{
    return $this->hasMany(VoucherItem::class, 'voucher_id', 'voucher_id');
}


public function salaryRelation()
{
    return $this->belongsTo(Salary::class, 'salary_id');
}


public function fine()
{
    return $this->belongsTo(Fine::class, 'fine_id');
}



public function getCustomNarrationAttribute()
{
    if ($this->sale_id && $this->sale) {
    $customerName = $this->sale->customer_name ?? 'N/A';
    $saleId = $this->sale_id;
    $totalAmount = number_format($this->sale->subtotal ?? 0, 2);  
    $date = $this->created_at->format('Y-m-d');

    return "Total Amount Received Against S{$saleId} is {$totalAmount}. Total Amount was {$totalAmount} on Date {$date} and Customer Name is {$customerName}";
}


    if ($this->purchase_id && $this->purchase) {
    $purchaseNo = $this->purchase->id ?? 'N/A';

    $purchaseRates = json_decode($this->purchase->purchase_rate, true);
    $totalAmount = 0;

    if (is_array($purchaseRates)) {
        foreach ($purchaseRates as $rate) {
            $totalAmount += floatval($rate);
        }
    }

    $totalAmountFormatted = number_format($totalAmount, 2);

    $date = $this->created_at->format('Y-m-d');
    $time = $this->created_at->format('H:i:s');

    return "PO # {$purchaseNo} Net Amount was {$totalAmountFormatted}. Transaction Date was {$date}. Complete Stock Received on {$date} {$time}";
}

  if ($this->raw_material_purchase_id && $this->rawMaterialPurchase) {
        $purchaseNo = $this->rawMaterialPurchase->id ?? 'N/A';
        $purchaseRates = json_decode($this->rawMaterialPurchase->purchase_rate, true);
        $totalAmount = 0;

        if (is_array($purchaseRates)) {
            foreach ($purchaseRates as $rate) {
                $totalAmount += floatval($rate);
            }
        }

        $totalAmountFormatted = number_format($totalAmount, 2);
        $date = $this->created_at->format('Y-m-d');
        $time = $this->created_at->format('H:i:s');

        return "Raw Material PO # {$purchaseNo} Net Amount was {$totalAmountFormatted}. Transaction Date was {$date}. Complete Stock Received on {$date} {$time}";
    }


      if ($this->salary_id && $this->salaryRelation && $this->salaryRelation->employee) {
    $employeeName = $this->salaryRelation->employee->employee_name ?? 'N/A';
    $salaryAmount = number_format($this->salaryRelation->paid ?? 0, 2);
    $date = $this->created_at->format('Y-m-d');

    return "Salary of {$salaryAmount} was paid to employee {$employeeName} on {$date}.";
}


if ($this->fine_id && $this->fine) {
        $employeeName = $this->fine->employee->employee_name ?? 'N/A';
        $fineAmount = number_format($this->fine->fine ?? 0, 2);
        $narration = $this->fine->narration ?? 'No narration';
        $date = $this->created_at->format('Y-m-d');

        return "Fine of {$fineAmount} imposed on {$employeeName} for '{$narration}' on {$date}.";
    }

$voucherItem = VoucherItem::where('voucher_id', $this->voucher_id)
    ->where('account', $this->vendor_account_id)
    ->first();

if ($voucherItem && !empty($voucherItem->narration)) {
    return $voucherItem->narration;
}

    return 'No related transaction info available.';
}




}

