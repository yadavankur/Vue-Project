<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style type="text/css">
        thead:before, thead:after { display: none; } tbody:before, tbody:after { display: none; }
        .content{
            width: 100%;
        }
        .container {
            width: 100%;
        }
        .page {
            page-break-after: always;
            page-break-inside: avoid;
        }
        .page:last-child{
            page-break-after: auto;
        }
        .header{
            top:0;
            width:100%;
        }
        .supervisor {
            display: inline-block;
            vertical-align: middle;
        }
        .row-title {
            display: inline-block;
            vertical-align: middle;
        }
        .row-content {
            display: inline-block;
            vertical-align: middle;
        }
        hr {
            margin-top: 0px;
            margin-bottom: 0px;
        }
    </style>
</head>
<body>
@php
    $itemsPerPages = $items->chunk($rowsPerPage);
@endphp
@foreach ($itemsPerPages as $index1 => $itemsPerPage)
    <div class="page">
        <div class="content">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>S/O #</th>
                    <th>Delivery Address</th>
                    <th>Delivery Date</th>
                    <th>Site Measure</th>
                    <th>Site Glaze</th>
                    <th>Install</th>
                    <th>General Service</th>
                    <th>Rectification</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($itemsPerPage as $index2 => $item)
                    @php
                        $deliveryAddress = '';
                        if ($item->order->deliver_address)
                        {
                            $address1 = $item->order->deliver_address->ADDR_1 ?? '';
                            $address2 = $item->order->deliver_address->ADDR_2 ? ' ' . $item->order->deliver_address->ADDR_2 : '';
                            $address3 = $item->order->deliver_address->ADDR_3 ? ', ' . $item->order->deliver_address->ADDR_3 : '';
                            $address4 = $item->order->deliver_address->ADDR_4 ? ' ' . $item->order->deliver_address->ADDR_4 : '';
                            $address5 = $item->order->deliver_address->ADDR_5 ? ' ' . $item->order->deliver_address->ADDR_5 : '';
                            $deliveryAddress = $address1 . $address2 . $address3 . $address4 .$address5;
                        }
                    @endphp
                    <tr>
                        <td>{{ ++$index2 + ($index1 * $rowsPerPage) }}</td>
                        <td>{{ $item->order_id }}</td>
                        <td>{{ $deliveryAddress }}</td>
                        <td>{{  $item->agreed_date? $item->agreed_date->format('d/m/Y') : '' }}</td>
                        <td>{{ $item->created_at? $item->created_at->format('d/m/Y') : '' }}</td>
                        <td>{{ $item->created_at? $item->created_at->format('d/m/Y') : '' }}</td>
                        <td>{{ $item->created_at? $item->created_at->format('d/m/Y') : '' }}</td>
                        <td>{{ $item->created_at? $item->created_at->format('d/m/Y') : '' }}</td>
                        <td>{{ $item->created_at? $item->created_at->format('d/m/Y') : '' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @if ($loop->last)
            @include('pdf.confirmation-email-list-footer')
        @endif
    </div>
@endforeach
</body>
</html>