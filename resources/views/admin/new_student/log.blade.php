<table class="table table-bordered">
    <thead>
    <tr>
        <th>Sl.</th>
        <th>Package</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Duration</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($logData as $key=>$package)
        @php
            $startDate = \Carbon\Carbon::parse($package->pivot->start_date);
            $endDate = \Carbon\Carbon::parse($package->pivot->end_date);

            $daysDifference = $startDate->diffInDays($endDate);
        @endphp
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $package->name }}</td>
            <td>{{ $package->pivot->start_date }}</td>
            <td>{{ $package->pivot->end_date }}</td>
            <td>{{ $daysDifference }} days</td>
        </tr>
    @endforeach
    </tbody>
</table>
