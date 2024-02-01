<x-filament-panels::page>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>

    <div class="overflow-y-auto">
<table class="border p-2 overflow-y-auto">
    <thead>
        <tr class="p-2">
          <th class="p-2">Payrun</th>
          <th class="p-2">Total Gross Salary </th>
          <th class="p-2">Total Tax Deduction</th>
          <th class="p-2">Total Net Salary</th>
          <th class="p-2">Total Allowance Count</th>
          <th class="p-2">Total Deduction Count </th>
          <th class="p-2">Total Allowance Amount </th>
          <th class="p-2">Total Deduction Amount </th>
          <th class="p-2">Total Employee Contribution Amount </th>
          <th class="p-2">Total Employer Contribution Amount </th>
          <th class="p-2">Total Reimbursement Count </th>
          <th class="p-2">Total Reimbursement Amount
        </th>

        </tr>
      </thead>
      <tbody>
        @foreach ($payruns as $payrun)

<tr class="border">
    <td class="p-2">{{$payrun['name']}}</td>
    <td class="p-2">{{$payrun['totalGrossSalary']}} <span class="text-gray-500 text-xs">({{$payrun['companyCurrency']}})</span></td>
    <td class="p-2">{{$payrun['totalTax']}} <span class="text-gray-500 text-xs">({{$payrun['companyCurrency']}})</span></td>
    <td class="p-2">{{$payrun['totalNet']}} <span class="text-gray-500 text-xs">({{$payrun['companyCurrency']}}) </span></td>
    <td class="p-2">{{$payrun['totalAllowanceCount']}}</td>
    <td class="p-2">{{$payrun['totalDeductionCount']}}</td>
    <td class="p-2">{{$payrun['payrunTotalDeductionAmount']}} <span class="text-gray-500 text-xs">({{$payrun['companyCurrency']}})</span></td>
    <td class="p-2">{{$payrun['payrunTotalDeductionAmount']}} <span class="text-gray-500 text-xs">({{$payrun['companyCurrency']}})</span></td>
    <td class="p-2">{{$payrun['totalEmployeeSocialSecurity']}} <span class="text-gray-500 text-xs">({{$payrun['companyCurrency']}})</span></td>
    <td class="p-2">{{$payrun['totalEmployerSocialSecurity']}} <span class="text-gray-500 text-xs">({{$payrun['companyCurrency']}})</span></td>
    <td class="p-2">{{$payrun['totalReimbursementCount']}}</td>
    <td class="p-2">{{$payrun['totalReimbursementAmount']}} <span class="text-gray-500 text-xs">({{$payrun['companyCurrency']}})</span></td>
</tr>
        @endforeach
      </tbody>

</table>
</div>
</x-filament-panels::page>
