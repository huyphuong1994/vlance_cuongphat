<?php

namespace App\Repositories\Admin\Addon;

use App\Models\CommissionHistory;
use App\Models\DeliveryHeroAccount;
use App\Models\OfflineMethod;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\Admin\CurrencyRepository;
use App\Repositories\Admin\RewardSystemRepository;
use App\Repositories\Interfaces\Admin\Addon\WalletInterface;
use App\Repositories\UserRepository;
use App\Traits\ImageTrait;
use App\Traits\PaymentTrait;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\DB;
use Sentinel;

class WalletRepository implements WalletInterface
{

}
