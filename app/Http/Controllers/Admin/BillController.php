<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Organization;
use App\Models\Admin\Package;
use App\Models\Admin\Student;
use App\Traits\FilterByOrganization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use PDF;
use Dompdf\Options;

class BillController extends Controller
{
    public function bill()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('bill.view')){
            abort(403);
        }

        return view('admin.bill.index');
    }

    private function getNestedChildOrganizations($parentOrganizationId, $includeParent = false)
    {
        $result = collect();

        if ($includeParent) {
            $parentOrganization = Organization::find($parentOrganizationId);
            if ($parentOrganization) {
                $result->push($parentOrganization);
            }
        }

        $childOrganizations = Organization::where('parent_id', $parentOrganizationId)->get();

        foreach ($childOrganizations as $childOrganization) {
            $result->push($childOrganization);
            $nestedChildOrganizations = $this->getNestedChildOrganizations($childOrganization->id);
            $result = $result->merge($nestedChildOrganizations);
        }

        return $result;
    }

    public function billList(Request $request)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('bill.view')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        if ($authUser->isSuperAdmin()) {
            $organizations = Organization::all();
        } else {
            $userOrganizationId = $authUser->organization_id;

            $organizations = $this->getNestedChildOrganizations($userOrganizationId, true);
        }

        foreach ($organizations as $organization) {

            $firstDayOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth();
            $lastDayOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();
            $firstDayOfCurrentMonth = Carbon::now()->startOfMonth();
            $lastDayOfCurrentMonth = Carbon::now()->endOfMonth();

            $students = $organization->students;
            $organizationDueAmount = 0;

            foreach ($students as $student) {

                $assignments = $student->packages()
                    ->where(function ($query) use ($firstDayOfPreviousMonth, $lastDayOfPreviousMonth, $firstDayOfCurrentMonth, $lastDayOfCurrentMonth) {
                        $query->whereBetween('student_package.end_date',[$firstDayOfPreviousMonth, $lastDayOfPreviousMonth])
                            ->orWhere(function ($query) use ($firstDayOfPreviousMonth, $lastDayOfPreviousMonth, $firstDayOfCurrentMonth, $lastDayOfCurrentMonth) {
                                $query->whereBetween('student_package.start_date', [$firstDayOfPreviousMonth, $lastDayOfPreviousMonth])
                                      ->whereBetween('student_package.end_date', [$firstDayOfCurrentMonth, $lastDayOfCurrentMonth]);
                            })
                            ->orWhere(function ($query) use ($firstDayOfCurrentMonth, $lastDayOfCurrentMonth) {
                                $query->whereNotBetween('student_package.start_date', [$firstDayOfCurrentMonth, $lastDayOfCurrentMonth])
                                      ->whereNull('student_package.end_date');
                            });
                    })
                    ->get();

                $dueAmount = 0;

                foreach ($assignments as $assignment) {
                    $startDate = Carbon::parse($assignment->pivot->start_date);
                    $endDate = Carbon::parse($assignment->pivot->end_date);

                    // Adjust the start date if it is earlier than the first date of the previous month
                    $firstDayOfPreviousMonth = Carbon::now()->subMonth()->firstOfMonth();
                    if ($startDate->lessThan($firstDayOfPreviousMonth)) {
                        $startDate = $firstDayOfPreviousMonth;
                    }

                    // Adjust the end date if it is greater than the last date of the previous month
                    $lastDayOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();
                    if ($endDate && $endDate->greaterThan($lastDayOfPreviousMonth)) {
                        $endDate = $lastDayOfPreviousMonth;
                    }

                    // Calculate the number of days the package was used within the previous month
                    $daysInPreviousMonth = $startDate->diffInDays($endDate) + 1;

                    // Calculate the due amount based on the number of days used
                    $packagePrice = $assignment->price;

                    if ($daysInPreviousMonth > 1) {
                        if ($daysInPreviousMonth <= 7) {
                            $dueAmount += $packagePrice * 0.5;
                        } else {
                            $dueAmount += $packagePrice;
                        }
                    }

//                    if ($startDate->weekOfMonth === 1) {
//                        $dueAmount += $packagePrice;
//                    } else {
//                        $dueAmount += $packagePrice * 0.5;
//                    }
                }

                $organizationDueAmount += $dueAmount;
            }

            // Store the total due amount for the organization
            $organization->setAttribute('dueAmount', $organizationDueAmount);

        }

        return DataTables::of($organizations)
            ->addIndexColumn()
            ->editColumn('organization', function ($organizations){
                return '<span class="badge badge-round badge-primary badge-lg">' . $organizations->name . '</span>';
            })
            ->editColumn('dueAmount', function ($organizations){
                return '<span class="badge badge-round badge-danger badge-lg">' . $organizations->dueAmount . '</span>';
            })
            ->editColumn('students', function ($organizations){
                return '<span class="badge badge-round badge-success badge-lg">' . $organizations->students->count() . '</span>';
            })
            ->addColumn('action', function ($organizations) {
                $url = route('admin.organization.bill', ['id' => $organizations->id]);
                return '<a href="' . $url . '" class="btn btn-sm btn-warning"><i class="fa fa-file-pdf"></i></a>';
            })
            ->rawColumns(['organization','dueAmount','students','action'])
            ->make(true);
    }

    public function organizationPdf($id){
        $organization = Organization::findOrfail($id);

        $students = $organization->students;
        $firstDayOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastDayOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();
        $firstDayOfCurrentMonth = Carbon::now()->startOfMonth();
        $lastDayOfCurrentMonth = Carbon::now()->endOfMonth();

        $totalDueAmount = 0;
        $assignmentsArray = [];

        foreach ($students as $student) {
            $assignments = $student->packages()
                ->where(function ($query) use ($firstDayOfPreviousMonth, $lastDayOfPreviousMonth, $firstDayOfCurrentMonth, $lastDayOfCurrentMonth) {
                    $query->whereBetween('student_package.end_date', [$firstDayOfPreviousMonth, $lastDayOfPreviousMonth])
                        ->orWhere(function ($query) use ($firstDayOfPreviousMonth, $lastDayOfPreviousMonth, $firstDayOfCurrentMonth, $lastDayOfCurrentMonth) {
                            $query->whereBetween('student_package.start_date', [$firstDayOfPreviousMonth, $lastDayOfPreviousMonth])
                                ->whereBetween('student_package.end_date', [$firstDayOfCurrentMonth, $lastDayOfCurrentMonth]);
                        })
                        ->orWhere(function ($query) use ($firstDayOfCurrentMonth, $lastDayOfCurrentMonth) {
                            $query->whereNotBetween('student_package.start_date', [$firstDayOfCurrentMonth, $lastDayOfCurrentMonth])
                                ->whereNull('student_package.end_date');
                        });
                })
                ->get();

            foreach ($assignments as $assignment) {
                $startDate = Carbon::parse($assignment->pivot->start_date);
                $endDate = Carbon::parse($assignment->pivot->end_date);

                // Adjust the start date if it is earlier than the first date of the previous month
                if ($startDate->lessThan($firstDayOfPreviousMonth)) {
                    $startDate = $firstDayOfPreviousMonth;
                }

                // Adjust the end date if it is greater than the last date of the previous month
                if ($endDate && $endDate->greaterThan($lastDayOfPreviousMonth)) {
                    $endDate = $lastDayOfPreviousMonth;
                }

                // Calculate the number of days the package was used within the previous month
                $daysInPreviousMonth = $startDate->diffInDays($endDate) + 1;

                // Calculate the due amount based on the number of days used
                $packagePrice = $assignment->price;

                if ($daysInPreviousMonth > 1) {
                    if ($daysInPreviousMonth <= 7) {
                        $dueAmount = $packagePrice * 0.5;
                    } else {
                        $dueAmount = $packagePrice;
                    }
                } else {
                    $dueAmount = 0;
                }

                // Add the assignment details to the assignmentsArray
                $assignmentsArray[] = [
                    'student_name' => $student->name,
                    'package_name' => $assignment->name,
                    'package_price' => $assignment->price,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate ? $endDate->format('Y-m-d') : null,
                    'days_used' => $daysInPreviousMonth,
                    'due_amount' => $dueAmount,
                ];

                $totalDueAmount += $dueAmount;
            }
        }

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial'); // Optional: You can set the default font

        $pdf = PDF::loadView('admin.bill.bill_report', compact('organization', 'assignmentsArray', 'totalDueAmount'));

        $prevMonth =  now()->subMonth()->format('F');

        return $pdf->download('invoice_of_'.$prevMonth.'.pdf');
    }
}
