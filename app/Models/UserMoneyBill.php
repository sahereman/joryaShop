<?php

namespace App\Models;

use App\Exceptions\InvalidRequestException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMoneyBill extends Model
{
    use SoftDeletes;

    const TYPE_ORDER_PAYMENT = 'orderPayment';
    const TYPE_ORDER_REFUND = 'orderRefund';
    const TYPE_DISTRIBUTION_INCOME = 'distributionIncome';

    public $typeMap = [];

    protected $fillable = [
        'user_id', 'type', 'description',
        'currency', 'operator', 'number',
        'related_model', 'related_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        //
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        //
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
        //
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->typeMap = [
            self::TYPE_ORDER_PAYMENT => trans('user.money_bill.orderPayment'),
            self::TYPE_ORDER_REFUND => trans('user.money_bill.orderRefund'),
            self::TYPE_DISTRIBUTION_INCOME => __('user.money_bill.distributionIncome'),
        ];
    }

    public function change(User $user, $type, $currency, $number, Model $related = null)
    {
        if (!in_array($type, array_keys($this->typeMap)))
        {
            throw new \Exception('账单类型异常');
        }

        if (!in_array($currency, array_keys(ExchangeRate::$symbolMap)))
        {
            throw new InvalidRequestException('货币参数异常');
        }

        if ($number < 0)
        {
            throw new InvalidRequestException('账单数额异常');
        }

        switch ($type)
        {
            case self::TYPE_ORDER_PAYMENT :
                if (!$related instanceof Order || !$related->exists)
                {
                    throw new \Exception('关联模型异常');
                }
                $operator = '-';
                $description = 'Order Payment '  . $currency . ' ' . $number;
                break;
            case self::TYPE_ORDER_REFUND :
                if (!$related instanceof OrderRefund || !$related->exists)
                {
                    throw new \Exception('关联模型异常');
                }
                $operator = '+';
                $description = 'Order Refund '  . $currency . ' ' . $number;
                break;
            case self::TYPE_DISTRIBUTION_INCOME :
                $operator = '+';
                $description = 'Distribution Income '  . $currency . ' ' . $number;
                break;
        }


        $data = [
            'user_id' => $user->id,
            'type' => $type,
            'description' => $description,
            'currency' => $currency,
            'operator' => $operator,
            'number' => $number,
        ];

        if ($related != null && $related->exists)
        {
            $data = array_merge($data, [
                'related_model' => $related->getMorphClass(),
                'related_id' => $related->id,
            ]);
        }

        $this->create($data);
    }

    public function getTypeTextAttribute($value)
    {
        return __('user.money_bill.' . $value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function related()
    {
        return $this->belongsTo($this->related_model, 'related_id');
    }


}
