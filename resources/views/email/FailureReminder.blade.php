<div class="table-responsive">
      <table class="table table-bordered table-striped" id="MyTable">
         <thead>
                  <tr>
                    <th>S#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Call Center</th>
                    <td>Last Sale</td>
                    <td>Joining Date</td>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($a as $k=> $item)

                    <tr>
                        <td>
                            {{++$k}}
                        </td>
                        <td>
                            {{$item['name']}}
                        </td>
                        <td>
                            {{$item['email']}}
                        </td>
                        <td>
                            {{$item['call_center']}}
                        </td>
                        <td>
                            {{$item['last_sale']}}
                        </td>
                        <td>
                            {{$item['joining_date']}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
      </table>
