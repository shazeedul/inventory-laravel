<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'count',
    ];

    public $timestamps = false;

    /**
     * Add visitor
     *
     * @param  string  $date
     * @return void
     */
    public static function add($date)
    {
        $visitor = self::firstOrNew(['date' => $date]);
        $visitor->count++;
        $visitor->save();
    }

    public static function getVisitorsCount()
    {
        return self::sum('count');
    }

    /**
     * Get visitors count by date
     *
     * @param  string  $date
     * @return int
     */
    public static function getVisitorsCountByDate($date)
    {
        return self::where('date', $date)->sum('count');
    }

    /**
     * Get visitors count by period
     *
     * @param  string  $from
     * @param  string  $to
     * @return int
     */
    public static function getVisitorsCountByPeriod($from, $to)
    {
        return self::whereBetween('date', [$from, $to])->sum('count');
    }

    /**
     * Get visitors count by Month
     *
     * @param  string  $month
     */
    public static function getVisitorsCountByMonth($month): int
    {
        return self::whereMonth('date', $month)->sum('count') ?? 0;
    }
}
