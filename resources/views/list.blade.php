
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet"/>

<script>
        $(document).ready(function() {
         var startDate;
         var endDate;

        $("#start_date").datepicker({
        format: 'yyyy-mm-dd',
            autoclose: true,
        }).on('changeDate', function (selected) {
          startDate = $('input[name=start_date]').val();
            $('#end_date').datepicker('setStartDate', startDate);
        });

        $("#end_date").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,

        }).on('changeDate', function (selected) {
                endDate = $('input[name=end_date]').val(); 
                $('#start_date').datepicker('setEndDate', endDate);
        });
          $('#search').submit(function(e) {
                e.preventDefault(); // prevent default form submission
                
                var searchQuery = $('input[name=search]').val();   
                $.ajax({
                    url: '{{ route('list.view') }}',
                    type: 'GET',
                    data: { search: searchQuery,
                            start_date: startDate,
                            end_date: endDate   
                    },
                   
                    success: function(files) {
                        var tdata =  $(files).find('tbody').html();  
                        $('tbody').html(tdata);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log(xhr.responseText);
                    }
                });
            });
          });
    </script>
@if(session()->has('message'))
      <p class="alert alert-success"> {{ session()->get('message') }}</p>
    @endif


     
<section class="intro">
  <div class="gradient-custom-1 h-100">
    <div class="mask d-flex align-items-center h-100">
      <div class="container">
      <form action="" id = "search" method="GET">
      <input type="text" name="start_date"  id = "start_date" class="" placeholder="yyyy-mm-dd">
        <input type="text" name="end_date" id = "end_date" class="" placeholder="yyyy-mm-dd">

        <input type="text" name="search" placeholder="Search...">
        <button type="submit">Search</button>
        <input type="reset" value="Reset">
  </form>
      <a href="{{ route('import') }}">
      <button type="submit" class="btn btn-primary">Import</button>
      </a>
      <button style="margin-bottom: 10px" class="btn btn-primary delete_all" data-url="{{ route('delete') }}">Delete All Selected</button> 
      <div class="row justify-content-center">
        <div class="col-12">
            <div class="table-responsive bg-white">
              <table class="table mb-0" id="listing-data">
                <thead>
                <th>
                     
                    <th scope="col">Order ID</th>
                    <th scope="col">Date</th>
                    <th scope="col">Description</th>
                    <th scope="col">Client ID</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                    <th scope="col">Delete</th>
                </thead>
                <tbody>
                @foreach($files as $file)
                <tr>
                <td>
                    <td><input type="checkbox" class="sub_chk" data-id="{{$file->id}}"></td>  
                </td>
                    <th>{{ $file->order_id }}</th>
                    <td>{{ $file->date }}</td>
                    <td>{{ $file->description }}</td>
                    <td>{{ $file->client_id }}</td>
                    <td>{{ $file->quantity }}</td>
                    <td>{{ $file->price }}</td>                    
                </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
  $(document).ready(function() {
         
    $('#master').on('click', function(e) {  
        if($(this).is(':checked',true))    
        {  
           $(".sub_chk").prop('checked', true);    
        } else {    
           $(".sub_chk").prop('checked',false);    
        }    
    });  

    $('.delete_all').on('click', function(e) {  
       var allVals = [];    
       $(".sub_chk:checked").each(function() {    
          allVals.push($(this).attr('data-id'));  
       });    

        if(allVals.length <=0)    
          {    
            alert("Please select row.");    
          }  else {    
          var check = confirm("Are you sure you want to delete this row?");    
          if(check == true){    
          var join_selected_values = allVals.join(",");   
          $.ajax({  
             url: $(this).data('url'),  
             type: 'get',  
             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
             data: 'ids='+join_selected_values,  
             success: function (data) {  
                 if (data['success']) {  
                     $(".sub_chk:checked").each(function() {    
                         $(this).parents("tr").remove();  
                     });  
                     alert(data['success']);  
                 } else if (data['error']) {  
                     alert(data['error']);  
                 } else {  
                     alert('Whoops Something went wrong!!');  
                 }  
             },  
             error: function (data) {  
                 alert(data.responseText);  
             }  
         });  

          $.each(allVals, function( index, value ) {  
             $('table tr').filter("[data-row-id='" + value + "']").remove();  
          });  
      }    
 }    
});  
});
  </script>
