<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Example 3</title>
    <style>

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #001028;
            text-decoration: none;
        }

        body {
            font-family: Junge;
            position: relative;
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-size: 14px;
        }

        .arrow {
            margin-bottom: 4px;
        }

        .arrow.back {
            text-align: right;
        }

        .inner-arrow {
            padding-right: 10px;
            height: 30px;
            display: inline-block;
            background-color: rgb(233, 125, 49);
            text-align: center;

            line-height: 30px;
            vertical-align: middle;
        }

        .arrow.back .inner-arrow {
            background-color: rgb(233, 217, 49);
            padding-right: 0;
            padding-left: 10px;
        }

        .arrow:before,
        .arrow:after {
            content:'';
            display: inline-block;
            width: 0; height: 0;
            border: 15px solid transparent;
            vertical-align: middle;
        }

        .arrow:before {
            border-top-color: rgb(233, 125, 49);
            border-bottom-color: rgb(233, 125, 49);
            border-right-color: rgb(233, 125, 49);
        }

        .arrow.back:before {
            border-top-color: transparent;
            border-bottom-color: transparent;
            border-right-color: rgb(233, 217, 49);
            border-left-color: transparent;
        }

        .arrow:after {
            border-left-color: rgb(233, 125, 49);
        }

        .arrow.back:after {
            border-left-color: rgb(233, 217, 49);
            border-top-color: rgb(233, 217, 49);
            border-bottom-color: rgb(233, 217, 49);
            border-right-color: transparent;
        }

        .arrow span {
            display: inline-block;
            width: 80px;
            margin-right: 20px;
            text-align: right;
        }

        .arrow.back span {
            margin-right: 0;
            margin-left: 20px;
            text-align: left;
        }

        h1 {
            color: #5D6975;
            font-family: Junge;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
            margin: 0 0 2em 0;
        }

        h1 small {
            font-size: 0.45em;
            line-height: 1.5em;
            float: left;
        }

        h1 small:last-child {
            float: right;
        }

        #project {
            float: left;
        }

        #company {
            float: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 30px;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            padding: 5px 10px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }

        table .service,
        table .desc {
            text-align: left;
        }

        table td {
            padding: 10px;
            text-align: center;
        }

        table td.service,
        table td.desc {
            vertical-align: top;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table td.sub {
            border-top: 1px solid #C1CED9;
        }

        table td.grand {
            border-top: 1px solid #5D6975;
        }

        table tr:nth-child(2n-1) td {
            background: #EEEEEE;
        }

        table tr:last-child td {
            background: #DDDDDD;
        }

        #details {
            margin-bottom: 30px;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }
    </style>
</head>
<body>
<main>
    <h1  class="clearfix">
        <small><span>DATE</span><br />
            @php
               $prevMonth =  now()->subMonth()->format('F');
               echo Carbon\Carbon::now();
            @endphp
        </small>
        Bill Of {{ $prevMonth }}
    </h1>
    <table>
        <thead>
        <tr>
            <th>Student Name</th>
            <th>Package Name</th>
            <th>Package Price</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Days Used</th>
            <th>Due Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($assignmentsArray as $assignment)
            <tr>
                <td>{{ $assignment['student_name'] }}</td>
                <td>{{ $assignment['package_name'] }}</td>
                <td>{{ $assignment['package_price'] }}</td>
                <td>{{ $assignment['start_date'] }}</td>
                <td>{{ $assignment['end_date'] }}</td>
                <td>{{ $assignment['days_used'] }}</td>
                <td>{{ $assignment['due_amount'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6">GRAND TOTAL</td>
            <td>{{ $totalDueAmount }}</td>
        </tr>
        </tbody>
    </table>
    <div id="details" class="clearfix">
        <div id="project">
            <div class="arrow"><div class="inner-arrow"><span>PROJECT</span> Website development</div></div>
            <div class="arrow"><div class="inner-arrow"><span>CLIENT</span> John Doe</div></div>
            <div class="arrow"><div class="inner-arrow"><span>ADDRESS</span> 796 Silver Harbour, TX 79273, US</div></div>
            <div class="arrow"><div class="inner-arrow"><span>EMAIL</span> <a href="mailto:john@example.com">john@example.com</a></div></div>
        </div>
        <div id="company">
            <div class="arrow back"><div class="inner-arrow">{{ $organization->name }}<span>COMPANY</span></div></div>
            <div class="arrow back"><div class="inner-arrow">{{ $organization->address }}<span>ADDRESS</span></div></div>
            <div class="arrow back"><div class="inner-arrow">0140004343<span>PHONE</span></div></div>
            <div class="arrow back"><div class="inner-arrow"><a href="mailto:company@example.com">test@example.com</a> <span>EMAIL</span></div></div>
        </div>
    </div>
    <div id="notices">
        <div>NOTICE:</div>
        <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
    </div>
</main>
<footer>
    Service will be off if u didn't paid the bill before 15th
</footer>
</body>
</html>
