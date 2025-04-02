<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'cin',
        'base_salary',
        'overtime_hours',
        'overtime_rate',
        'bonus',
        'benefits',
        'taxes',
        'deductions',
        'net_salary',
        'pay_date',
        'payment_status',
        'notes'
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'bonus' => 'decimal:2',
        'benefits' => 'decimal:2',
        'taxes' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'pay_date' => 'date',
    ];

    /**
     * Get the employee associated with the payroll record.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'cin', 'cin');
    }

    /**
     * Calculate the total payroll for a specific month
     */
    public static function getMonthlyTotal($month, $year)
    {
        return self::whereMonth('pay_date', $month)
            ->whereYear('pay_date', $year)
            ->sum('net_salary');
    }

    /**
     * Get department-wise payroll costs
     */
    public static function getDepartmentCosts($month, $year)
    {
        return self::join('employees', 'payrolls.cin', '=', 'employees.cin')
            ->select('employees.department', \DB::raw('SUM(payrolls.net_salary) as total_cost'))
            ->whereMonth('pay_date', $month)
            ->whereYear('pay_date', $year)
            ->groupBy('employees.department')
            ->get()
            ->pluck('total_cost', 'department')
            ->toArray();
    }

    /**
     * Get monthly payroll trends
     */
    public static function getMonthlyTrends($year)
    {
        return self::select(
                \DB::raw('MONTH(pay_date) as month'),
                \DB::raw('SUM(net_salary) as total')
            )
            ->whereYear('pay_date', $year)
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
    }

    /**
     * Get payroll components for a specific month
     */
    public static function getPayrollComponents($month, $year)
    {
        return self::whereMonth('pay_date', $month)
            ->whereYear('pay_date', $year)
            ->select(
                \DB::raw('SUM(base_salary) as base_salary'),
                \DB::raw('SUM(overtime_hours * overtime_rate) as overtime'),
                \DB::raw('SUM(bonus) as bonuses'),
                \DB::raw('SUM(benefits) as benefits'),
                \DB::raw('SUM(taxes) as taxes'),
                \DB::raw('SUM(deductions) as deductions')
            )
            ->first();
    }

    /**
     * Get top earners for a specific month
     */
    public static function getTopEarners($month, $year, $limit = 5)
    {
        return self::join('employees', 'payrolls.cin', '=', 'employees.cin')
            ->select(
                'employees.fullName',
                'employees.position',
                'employees.department',
                'payrolls.net_salary'
            )
            ->whereMonth('pay_date', $month)
            ->whereYear('pay_date', $year)
            ->orderBy('net_salary', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent payments
     */
    public static function getRecentPayments($limit = 8)
    {
        return self::join('employees', 'payrolls.cin', '=', 'employees.cin')
            ->select(
                'employees.fullName as employee',
                'payrolls.pay_date',
                'payrolls.net_salary as amount',
                'payrolls.payment_status as status'
            )
            ->orderBy('pay_date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get year-to-date comparison
     */
    public static function getYTDComparison($year)
    {
        $currentYear = self::whereYear('pay_date', $year)
            ->sum('net_salary');

        $previousYear = self::whereYear('pay_date', $year - 1)
            ->sum('net_salary');

        $difference = $currentYear - $previousYear;
        $percentageChange = $previousYear > 0 ? ($difference / $previousYear) * 100 : 0;

        return [
            'year' => $year,
            'total_payroll' => $currentYear,
            'previous_year' => $year - 1,
            'previous_payroll' => $previousYear,
            'percentage_change' => round($percentageChange, 2),
            'difference' => $difference
        ];
    }
}
