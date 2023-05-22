<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <th class="">Order Id</th>
            <th class="" colspan="3">{{count($item)> 0 ? $item[0]->order_id : ""}}</th>
        </thead>
        <tbody>
            <tr>
                <th>Item name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            @php
                $total = 0;
            @endphp
            @foreach ($item as $val)
                <tr>
                    <td>{{$val->name}}</td>
                    <td>{{$val->total_quantity}}</td>
                    <td>{{$val->total_price!=0 ? number_format(($val->total_price/$val->total_quantity),1) : ""}}</td>
                    <td>{{$val->total_price}}</td>
                    @php
                        $total += $val->total_price;
                    @endphp
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" align="right">Total</td>
                <td>{{$total}}</td>
            </tr>
        </tfoot>
    </table>
</div>
