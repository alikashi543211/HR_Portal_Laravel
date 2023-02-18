<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [''];

    protected $appends = ['remaining', 'totalInstallments', 'startFrom', 'endFrom'];
    /**
     * Get all of the installments for the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function installments(): HasMany
    {
        return $this->hasMany(LoanInstallment::class, 'loan_id', 'id');
    }

    /**
     * Get the user that owns the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    /**
     * Get the step title
     *
     * @param  string  $value
     * @return string
     */
    public function getRemainingAttribute()
    {
        return  $this->installments()->where('status', PENDING)->sum('amount');
    }
    public function getTotalInstallmentsAttribute()
    {
        return $this->installments()->where('status', PENDING)->count();
    }
    public function getStartFromAttribute()
    {
        return Carbon::parse($this->installments()->max('month'))->format('M Y');
    }
    public function getEndFromAttribute()
    {
        return Carbon::parse($this->installments()->min('month'))->format('M Y');
    }
}
