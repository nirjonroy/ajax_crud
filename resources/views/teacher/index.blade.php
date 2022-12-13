<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>
<body>

<div style="padding:30px"></div>
<div class="container">
    <h2 style="color:red">ajax</h2>
    <div class="row">
        <div class="col-sm-8">
        <table class="table table-bordered">
    <thead>
      <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Institute</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <!-- <tr>
        <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
      </tr> -->
      
    </tbody>
  </table>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <span id="addT">Add Teacher</span>
                    <span id="updateT">Update Teacher</span>
                </div>
            </div>
        
    <div class="form-group">
      <label class="control-label col-sm-2" for="name">Name:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="name" placeholder="Enter name" >
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="title">Title:</label>
      <div class="col-sm-10">          
        <input type="text" class="form-control" id="title" placeholder="Enter title" >
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="institute">Institute:</label>
      <div class="col-sm-10">          
        <input type="text" class="form-control" id="institute" placeholder="Enter institute" >
      </div>
    </div>
    <input type="hidden" id="id">
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <!-- <button type="submit" id="addButton" onclick="addData()" class="btn btn-warning">Submit</button> -->
        <input type="submit" id="addButton" onclick="addData()" class="btn btn-warning" value="submit">
        <button type="submit" id="updateButton" onclick="updateData()" class="btn btn-warning">update</button>
      </div>
    </div>
  
        </div>
    </div>
</div>


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
<script>
    $('#addT').show();
    $('#addButton').show();
    $('#updateT').hide();
    $('#updateButton').hide();

    $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
    //   --------------------------------Get All Data From DB--------------------------------          
    function allData(){
        $.ajax({
            type: "GET",
            dataType : 'json',
            url : "/teacher/all",
            success : function(response){
                var data = ""
                $.each(response, function(key, value){
                  data = data + "<tr>"
                  data = data + "<td>"+value.id+"</td>"
                  data = data + "<td>"+value.name+"</td>"
                  data = data + "<td>"+value.title+"</td>"
                  data = data + "<td>"+value.institute+"</td>"
                  data = data + "<td>"
                  data = data + "<button class='btn-btn-sm btn-success mr-2' onclick='editCatData("+value.id+")'> Edit </button>"
                  data = data + "<button class='btn-btn-sm btn-danger mr-2' onclick='deleteData("+value.id+")' > Delete </button>"
                  data = data + "</td>"
                  data = data + "</tr>"
                })
                
                $('tbody').html(data);
            }
        })
    }
    allData();

    // data clear form input field
    function clearData(){
        $('#name').val('');
        $('#title').val('');
        $('#institute').val('');
    }
        // data insert
    function addData(){
        var name = $('#name').val();
        var title = $('#title').val();
        var institute = $('#institute').val();
        // console.log(name);
        $.ajax({
            type: "POST",
            dataType:"json",
            data:{name:name, title:title, institute:institute, _token: '{{csrf_token()}}'},
            url:"/teacher/store/",
            success: function(data){
             const Msg = Swal.mixin({
                  toast: true, 
                  position : 'top-end',
                  icon : 'success',
                  showConfirmButton : false,
                  timer: 1500
                })

                Msg.fire({
                  type: 'success',
                  title : 'Data Added Succesfully',
                })

              $('#addT').show();
              $('#addButton').show();
              $('#updateT').hide();
              $('#updateButton').hide();
                clearData();
                allData();
                console.log('succesfully data added');
            },
            error: function(error){
                console.log(error);
                

            }
        })

    }

    // ---------------------Edit Data------------------------
    function editCatData(id){
      $.ajax({
        type: "GET",
        dataType: "json", 
        url : "/teacher/edit/"+id,
        success: function(data){
          $('#addT').hide();
          $('#addButton').hide();
          $('#updateT').show();
          $('#updateButton').show();

          $('#id').val(data.id);
          $('#name').val(data.name);
          $('#title').val(data.title);
          $('#institute').val(data.institute);
          console.log(data);
        } 
      })
    }

// -----------------Update Data--------------------
function updateData(){
  var id = $('#id').val();
  var name = $('#name').val();
  var title = $('#title').val();
  var institute = $('#institute').val();
  $.ajax({
    type : "POST",
    dataType: "json",
    data:{name:name, title:title, institute:institute, _token: '{{csrf_token()}}'},
    url : "/teacher/update/"+id,
    success: function(data){
      clearData();
      allData();
      const Msg = Swal.mixin({
                  toast: true, 
                  position : 'top-end',
                  icon : 'success',
                  showConfirmButton : false,
                  timer: 1500
                })

                Msg.fire({
                  type: 'success',
                  title : 'Data Update Succesfully',
                })
    },
    error: function(error){
                console.log(error);
                
            }

  })
}

// -----------------Delete--------------------
function deleteData(id){
  swal({
    title: "Are you sure to delete",
    text: "kdsfjldsfj",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if(willDelete){
      $.ajax({
  type: "GET",
  dataType: "json",
  url : "/teacher/delete/"+id,
  success : function(data){
    $('#addT').show();
    $('#addButton').show();
    $('#updateT').hide();
    $('#updateButton').hide();
    clearData();
    allData();

    const Msg = Swal.mixin({
                  toast: true, 
                  position : 'top-end',
                  icon : 'success',
                  showConfirmButton : false,
                  timer: 1500
                })

                Msg.fire({
                  type: 'success',
                  title : 'Data Delete Succesfully',
                })
  }
})
    }
    else{
      swal("canceled");
    }
  });

}
</script>
</body>
</html>
