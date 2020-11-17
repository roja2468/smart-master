<?php 
namespace App\Traits;
use Str;
use App\User;
use App\ReferralAmount;
use App\Wallet;

trait comman_fun
{
	public function generateReferralNumber(){
        $code = strtoupper(Str::random(8));
        if ($this->referralNumberExists($code)) {
            return $this->generateReferralNumber();
        }
        return $code;
    }
    public function generateRandomPassword(){
        $code = Str::random(8);
        return $code;
    }
    public function referralNumberExists($code) {
        return User::where('referral_code',$code)->exists();
    }
    protected function setData($value)
    {
        array_walk_recursive($value, function (&$item, $key) {
            $item = null === $item ? '' : $item;
        });
        return $value;
    }
    public function referralAmount() {
        $ReferralAmount = ReferralAmount::find(1);
        return $ReferralAmount->amount;
    }
    public function referralCommissionAmount() {
        $ReferralAmount = ReferralAmount::find(1);
        return $ReferralAmount->referral_amount_commission;
    }
    public function userWalletAmount($id) {
        $totalAmount = 0;
        $WalletAmount = Wallet::where('user_id',$id)->get();
        foreach ($WalletAmount as $key => $Amount) {
            if($Amount->type == 1)
            {
                $totalAmount += $Amount->amount;
            }
            elseif($Amount->type == 2)
            {
                $totalAmount -= $Amount->amount;
            }
        }
        return $totalAmount;
    }
}