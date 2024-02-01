
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>payslip</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
        }

        .s1 {
            color: #231F20;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 23pt;
        }

        h1 {
            color: #231F20;
            font-family: Tahoma, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 9pt;
        }

        .s2 {
            color: #231F20;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 9pt;
        }

        p {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 9pt;
            margin: 0pt;
        }

        .s3 {
            color: #231F20;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 9pt;
        }

        .s4 {
            color: #231F20;
            font-family: "Trebuchet MS", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 9pt;
        }

        .s5 {
            color: #231F20;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 9pt;
        }

        .s6 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 9pt;
        }

        .s7 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 9pt;
        }

        .s8 {
            color: #F00;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 9pt;
        }

        .s9 {
            color: #007F00;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 9pt;
        }

        .s10 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 9pt;
        }

        table,
        tbody {
            vertical-align: top;
            overflow: visible;
        }
    </style>
</head>

<body >
    <div style="display: flex;align-items:center;justify-content:between">

<div style="margin-left: 1rem">

    <p class="s1" style="padding-top: 3px;padding-left: 5pt;text-indent: 0pt;text-align: left;">
        @if ($companyLogo)

        <img src="./storage/{{$companyLogo}}" alt="" srcset="" style="width: 40px;height:40px">
        @endif
    </p>
    <p style="text-indent: 0pt;text-align: left; margin-bottom:10px"><br /></p>
    {{-- logo --}}

</div>
<h2 style="margin-left: 1.2rem">PAYSLIP</h2>
    </div>
    <div style="display: flex;align-items:center">
        <div style="margin-right: 4rem">
            <p class="s2" style="padding-top: 5pt;text-indent: 0pt;text-align: right;">Pay period: {{$payrun->start}} to {{$payrun->end}}</p>
            <p class="s2" style="padding-top: 2pt;text-indent: 0pt;text-align: right;">Date of payment: {{$dateOfPayment}}</p>

            <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;"><br /></p>
        </div>
        <div style="margin-left: 1rem">
            <h1 style="padding-left: 7pt;text-indent: 0pt;line-height: 1%;text-align: left;">Employer’s name: @if ($employeeName)
{{$companyName}}
            @endif</h1> <br>
            <h1 style="padding-left: 7pt;text-indent: 0pt;line-height: 1%;text-align: left;"><span
                class="s2">RegistrationId: {{$companyRegistrationId}}</span></h1><br />
                <h1 style="padding-left: 7pt;text-indent: 0pt;line-height: 1%;text-align: left;">Employee’s name: {{$employeeName}}</h1><br />

            {{-- <h1 style="padding-left: 7pt;text-indent: 0pt;line-height: 1%;text-align: left;">Employment status:</h1><br /> --}}
        </div>

    </div>



    <table style="border-collapse:collapse;margin-left:3rem" cellspacing="0">
        <tr style="height:13pt">
            <td style="width:233pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;"><br /></p>
            </td>
            <td style="width:85pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s3" style="padding-right: 3pt;text-indent: 0pt;line-height: 9pt;text-align: right;">Unit</p>
            </td>
            <td style="width:85pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s3" style="padding-right: 3pt;text-indent: 0pt;line-height: 9pt;text-align: right;">Rate</p>
            </td>
            <td style="width:101pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s3" style="padding-right: 3pt;text-indent: 0pt;line-height: 9pt;text-align: right;">Total</p>
            </td>
        </tr>
        <tr style="height:18pt">
            <td style="width:318pt;border-top-style:solid;border-top-width:1pt;border-top-color:#231F20;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                colspan="2">
                <p class="s3" style="padding-top: 3pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">EARNINGS</p>
            </td>
            <td
                style="width:85pt;border-top-style:solid;border-top-width:1pt;border-top-color:#231F20;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;"><br /></p>
            </td>
            <td
                style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#231F20;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;"><br /></p>
            </td>
        </tr>
        <tr style="height:19pt">
            <td style="width:233pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s4" style="padding-top: 3pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">Salary or
                    wages <span class="s5">for ordinary hours worked</span></p>
            </td>
            <td style="width:85pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s5" style="padding-top: 3pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">{{round($totalWorkHours,1)}} hours</p>
            </td>
            <td style="width:85pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s5" style="padding-top: 3pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">{{round($salaryPerHour,2)}}<br /></p>
            </td>
            <td style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 3pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{$companyCurrencySymbol}} {{round($workingTimeAmount,1)}}</p>
            </td>
        </tr>
        <tr style="height:19pt">
            <td style="width:233pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 3pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">Over Time</p>
            </td>
            <td style="width:85pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s5" style="padding-top: 3pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">{{round($overTimeHour,1)}} hours</p>
            </td>
            <td style="width:85pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s5" style="padding-top: 3pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">{{round($overTimeRate,1)}}<br /></p>
            </td>
            <td style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 3pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{$companyCurrencySymbol}} {{round($overTimeAmount,2)}}</p>
            </td>
        </tr>
        <tr style="height:19pt">
            <td style="width:233pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">Paid leave</p>
            </td>
            <td style="width:85pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s5" style="padding-top: 3pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">{{$paidLeaveHour}} hours</p>
            </td>
            <td style="width:85pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 3pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{round($salaryPerHour,2)}}<br /></p>
            </td>
            <td style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{$companyCurrencySymbol}} {{round($paidLeaveAmount,2)}}</p>
            </td>
        </tr>
        {{-- <tr style="height:19pt">
            <td style="width:233pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">&lt;Insert
                    name of earnings (e.g. paid sick leave)&gt;</p>
            </td>
            <td style="width:85pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s5" style="padding-top: 3pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">hours</p>
            </td>
            <td style="width:85pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;"><br /></p>
            </td>
            <td style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">$ 0.00</p>
            </td>
        </tr>
        <tr style="height:19pt">
            <td style="width:233pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">&lt;Insert
                    name of earnings (e.g. lump sum)&gt;</p>
            </td>
            <td style="width:85pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s5" style="padding-top: 3pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">N/A</p>
            </td>
            <td style="width:85pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;"><br /></p>
            </td>
            <td style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">$ 0.00</p>
            </td>
        </tr> --}}
        <tr style="height:18pt">
            <td style="width:403pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                colspan="3">
                <p class="s3" style="padding-top: 4pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">GROSS
                    PAYMENT</p>
            </td>
            <td
                style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s7" style="padding-top: 3pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{$companyCurrencySymbol}} {{$grossSalary}}</p>
            </td>
        </tr>
        {{-- allowance --}}

        <tr style="height:13pt">
            <td style="width:233pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;"><br /></p>
            </td>
            <td style="width:85pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s3" style="padding-right: 3pt;text-indent: 0pt;line-height: 9pt;text-align: right;padding-top:10px"></p>
            </td>
            <td style="width:85pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s3" style="padding-right: 3pt;text-indent: 0pt;line-height: 9pt;text-align: right;padding_top:10px">Count</p>
            </td>
            <td style="width:101pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s3" style="padding-right: 3pt;text-indent: 0pt;line-height: 9pt;text-align: right;"></p>
            </td>
        </tr>
        <tr style="height:19pt">

            <td style="width:504pt;border-top-style:solid;border-top-width:1pt;border-top-color:#231F20;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
            colspan="4">
            {{-- <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;"><br /></p> --}}
            <p class="s3" style="padding-left: 4pt;text-indent: 0pt;text-align: left;margin-top:9px">ALLOWANCE</p>
        </td>
    </tr>
    @foreach ($allowanceList as $name=>$allowance)

        <tr style="height:19pt">
            <td style="width:403pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                colspan="2" bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$name}}</p>
            </td>

            <td style="width:85pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
            bgcolor="#EFEFF0">
            <p class="s5" style="padding-top: 3pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">{{$allowance[1]}}<br /></p>
        </td>
            <td style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{$allowance[0]}}<br /></p>
            </td>
        </tr>

        @endforeach

        <tr style="height:18pt">
            <td style="width:403pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                colspan="3">
                <p class="s3" style="padding-top: 4pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">TOTAL
                    ALLOWANCE</p>
            </td>
            <td
                style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s9" style="padding-top: 3pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{$companyCurrencySymbol}} {{$totalAllowanceAmount}}</p>
            </td>
        </tr>

        {{-- end allowance --}}
        {{-- deduction --}}
        <tr style="height:13pt">
            <td style="width:233pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;"><br /></p>
            </td>
            <td style="width:85pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s3" style="padding-right: 3pt;text-indent: 0pt;line-height: 9pt;text-align: right;padding-top:10px"></p>
            </td>
            <td style="width:85pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s3" style="padding-right: 3pt;text-indent: 0pt;line-height: 9pt;text-align: right;padding_top:10px">Count</p>
            </td>
            <td style="width:101pt;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s3" style="padding-right: 3pt;text-indent: 0pt;line-height: 9pt;text-align: right;"></p>
            </td>
        </tr>
        <tr style="height:47pt">
            <td style="width:504pt;border-top-style:solid;border-top-width:1pt;border-top-color:#231F20;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                colspan="4">
                <p class="s3" style="padding-left: 4pt;text-indent: 0pt;text-align: left;margin-top:9px">DEDUCTIONS</p>
            </td>
        </tr>
        <tr style="height:19pt">
            <td style="width:403pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                colspan="3" bgcolor="#EFEFF0">
                <p class="s5" style="padding-top: 3pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">Taxation
                    (PAYG)</p>
            </td>
            <td style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{round($monthTax,2)}}<br /></p>
            </td>
        </tr>

        @foreach ($deductionList as $name=>$deduction)

        <tr style="height:19pt">
            <td style="width:403pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                colspan="2" bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$name}}</p>
            </td>
            <td style="width:85pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
            bgcolor="#EFEFF0">
            <p class="s5" style="padding-top: 3pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">{{$deduction[1]}}<br /></p>
        </td>
            <td style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{$deduction[0]}}<br /></p>
            </td>
        </tr>
        @endforeach


        <tr style="height:18pt">
            <td style="width:403pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                colspan="3">
                <p class="s3" style="padding-top: 4pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">TOTAL
                    DEDUCTIONS</p>
            </td>
            <td
                style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s8" style="padding-top: 3pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{$companyCurrencySymbol}} {{round($totalDeductionAmount,2)}}</p>
            </td>
        </tr>
        {{-- reimbursment --}}
        @if ($reimbursementList)

        <tr style="height:47pt">
            <td style="width:504pt;border-top-style:solid;border-top-width:1pt;border-top-color:#231F20;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                colspan="4">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;"><br /></p>
                <p class="s3" style="padding-left: 4pt;text-indent: 0pt;text-align: left;">REIMBURSEMENTS</p>
            </td>
        </tr>

        @foreach ($reimbursementList as $name=>$reimbursement)

        <tr style="height:19pt">
            <td style="width:403pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                colspan="3" bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$name}}</p>
            </td>
            <td style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{$reimbursement}}<br /></p>
            </td>
        </tr>
        @endforeach


        <tr style="height:18pt">
            <td style="width:403pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                colspan="3">
                <p class="s3" style="padding-top: 4pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">TOTAL
                    REIMBURSEMENT</p>
            </td>
            <td
                style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s7" style="padding-top: 3pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{$companyCurrencySymbol}} {{$reimbursementAmount}}</p>
            </td>
        </tr>
        @endif

        {{-- end reimbursment --}}
        <tr style="height:18pt">
            <td style="width:403pt;border-top-style:solid;border-top-width:1pt;border-top-color:#231F20;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20;border-right-style:solid;border-right-width:1pt;border-right-color:#231F20"
                colspan="3" bgcolor="#C7C8CA">
                <p class="s3" style="padding-top: 3pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">NET
                    PAYMENT</p>
            </td>
            <td style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#231F20;border-left-style:solid;border-left-width:1pt;border-left-color:#231F20;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20"
                bgcolor="#C7C8CA">
                <p class="s7" style="padding-top: 3pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{$companyCurrencySymbol}} {{round($netPayAmount,2)}}</p>
            </td>
        </tr>
        <tr style="height:47pt">
            <td style="width:504pt;border-top-style:solid;border-top-width:1pt;border-top-color:#231F20;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                colspan="4">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;"><br /></p>
                <p class="s3" style="padding-left: 4pt;text-indent: 0pt;text-align: left;">Social Security</p>
            </td>
        </tr>
        <tr style="height:19pt">
            <td style="width:403pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                colspan="3" bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$socialSecurity->name}} (Employee Contribution Amount)</p>
            </td>
            <td style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{round($employeeContributionAmount,2)}}<br /></p>
            </td>
        </tr>
        <tr style="height:19pt">
            <td style="width:403pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                colspan="3" bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-left: 3pt;text-indent: 0pt;text-align: left;">{{$socialSecurity->name}} (Employer Contribution Amount)</p>
            </td>
            <td style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#C7C8CA"
                bgcolor="#EFEFF0">
                <p class="s6" style="padding-top: 4pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{round($employerContributionAmount,2)}}<br /></p>
            </td>
        </tr>


        <tr style="height:18pt">
            <td style="width:403pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20;border-right-style:solid;border-right-width:1pt;border-right-color:#C7C8CA"
                colspan="3">
                <p class="s3" style="padding-top: 4pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">TOTAL
                    CONTRIBUTION</p>
            </td>
            <td
                style="width:101pt;border-top-style:solid;border-top-width:1pt;border-top-color:#C7C8CA;border-left-style:solid;border-left-width:1pt;border-left-color:#C7C8CA;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#231F20">
                <p class="s7" style="padding-top: 3pt;padding-right: 5pt;text-indent: 0pt;text-align: right;">{{$companyCurrencySymbol}} {{round($employeeContributionAmount,2)+round($employeeContributionAmount,2)}}</p>
            </td>
        </tr>
    </table>

</body>

</html>
