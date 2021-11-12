<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRUD Letter Generator</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    {{-- <link rel="stylesheet" href="sweetalert2.min.css"> --}}
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
</head>
<body>
    @include('sweetalert::alert')

    <h2 class="mt-5 text-center">Student Active Letter Generator</h2>
    <h4 class="text-center text-muted">Universitas Kesana Kemari</h4>
    <div class="container">
        <div class="row" style="margin-top: 45px">
            <div class="col-md-8">

                {{-- <input type="text" name="searchfor" id="" class="form-control"> --}}
                    <div class="card">
                        <div class="card-header">Students</div>
                        <div class="card-body">
                            <table class="table table-hover table-condensed" id="students-table">
                                <thead>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Student Major</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                  <div class="card">
                      <div class="card-header">Add new Student</div>
                      <div class="card-body">
                          <form action="{{ route('add.student') }}" method="post" id="add-student-form" autocomplete="off">
                              @csrf
                              <div class="form-group">
                                  <label class="mb-1" for="">Student Name</label>
                                  <input type="text" class="form-control mb-2" name="student_name" placeholder="Enter student name">
                                  <span class="text-danger error-text student_name_error"></span>
                              </div>
                              <div class="form-group">
                                  <label class="mb-1" for="">Student Major</label>
                                  <input type="text" class="form-control mb-2" name="student_major" placeholder="Enter student major">
                                  <span class="text-danger error-text student_major_error"></span>
                              </div>
                              <div class="form-group">
                                  <button type="submit" class="btn btn-block btn-success">SAVE</button>
                              </div>
                          </form>
                      </div>
                  </div>
            </div>
        </div>
    </div>

    @include('edit-student-modal')
    {{-- @include('letter') --}}
    <!-- JavaScript Bundle with Popper -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    {{-- <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script> --}}
    <script
			  src="https://code.jquery.com/jquery-3.6.0.min.js"
			  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
			  crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="sweetalert2.min.js"></script> --}}
    <script>
        toastr.options.preventDuplicates = true;
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function(){
            $('#add-student-form').on('submit', function(e){
                e.preventDefault();
                let form = this;
                $.ajax({
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data:new FormData(form),
                    processData:false,
                    dataType:'json',
                    contentType:false,
                    beforeSend:function(){
                        $(form).find('span.error-text').text('');
                    },
                    success:function(data){
                        if(data.code == 0){
                            $.each(data.error, function(prefix,val){
                                $(form).find('span.'+prefix+'_error').text(val[0]);
                            });
                        }else{
                            $(form)[0].reset();
                            $('#students-table').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        }
                    }
                })
            });

            //GET ALL STUDENTS
            $('#students-table').DataTable({
                processing:true,
                info:true,
                ajax:"{{ route('get.students.list') }}",
                    "pageLength":5,
                    "aLengthMenu":[[5,10,25,50,-1],[5,10,25,50,"All"]],
                aoColumns:[
                    { data: "DT_RowIndex" },
                    { data: "student_name" },
                    { data: "student_major" },
                    { data: "actions" },
                    // {data:'created_at', name:'created_at'},
                    // {data:'updated_at', name:'updated_at'},
                ],
                columnDefs: [{
                    // "defaultContent": ".",
                    // "targets": "_all"
                }]
            });

            $(document).on('click','#editStudentBtn', function(){
                let student_id = $(this).data('id');
                // alert(student_id);
                $('.editStudent').find('form')[0].reset();
                $('.editStudent').find('span.error-text').text('');
                $.post('<?= route("get.students.details") ?>',{student_id:student_id}, function(data){
                    // alert(data.details.student_name);
                    $('.editStudent').find('input[name="cid"]').val(data.details.id);
                    $('.editStudent').find('input[name="student_name"]').val(data.details.student_name);
                    $('.editStudent').find('input[name="student_major"]').val(data.details.student_major);
                    $('.editStudent').modal('show');
                }), 'json';
            })

            //UPDATE
            $('#update-student-form').on('submit', function(e){
                e.preventDefault();
                let form = this;
                $.ajax({
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data:new FormData(form),
                    processData:false,
                    dataType:'json',
                    contentType:false,
                    beforeSend: function(){
                        $(form).find('span.error-text').text('');
                    },
                    success: function(data){
                        if(data.code == 0){
                            $.each(data.error, function(prefix, val){
                                $(form).find('span.'+prefix+'_error').text(val[0]);
                            });
                        }else{
                            $('#students-table').DataTable().ajax.reload(null, false);
                            $('.editStudent').modal('hide');
                            $('.editStudent').find('form')[0].reset();
                            toastr.success(data.msg);
                        }
                    }
                })
            })

            // Delete
            $(document).on('click','#deleteStudentBtn', function(){
                let student_id = $(this).data('id');
                //alert(student_id);
                let url = '<?= route("delete.student") ?>';
                Swal.fire({
                    title:'Are you sure?',
                    html:'You want to <b>delete</b> this country',
                    showCancelButton:true,
                    showCloseButton:true,
                    cancelButtonText:'Cancel',
                    confirmButtonText:'Yes, Delete',
                    cancelButtonColor:'#d33',
                    confirmButtonColor:'#556ee6',
                    width:300,
                    allowOutsideClick:false
                }).then(function(result){
                    if(result.value){
                        $.post(url,{student_id:student_id}, function(data){
                            if(data.code == 1){
                                $('#students-table').DataTable().ajax.reload(null, false);
                                toastr.success(data.msg);
                            }else{
                                toastr.error(data.msg);
                            }
                        },'json');
                    }
                });
            });

            // Download PDF
            $(document).on('click','#pdfStudentBtn', function(){
                let student_id = $(this).data('id');
                $("#exampleModal").modal('show');
                // $.ajax({
                //     url:'download.pdf',
                // })
                alert(student_id);
                // let url = '<?= route("download.pdf") ?>';
                // $.post(url,{student_id:student_id});
                // Swal.fire({
                //     title:'Are you sure?',
                //     html:'You want to <b>delete</b> this country',
                //     showCancelButton:true,
                //     showCloseButton:true,
                //     cancelButtonText:'Cancel',
                //     confirmButtonText:'Yes, Delete',
                //     cancelButtonColor:'#d33',
                //     confirmButtonColor:'#556ee6',
                //     width:300,
                //     allowOutsideClick:false
                // }).then(function(result){
                //     if(result.value){
                //         $.post(url,{student_id:student_id}, function(data){
                //             if(data.code == 1){
                //                 $('#students-table').DataTable().ajax.reload(null, false);
                //                 toastr.success(data.msg);
                //             }else{
                //                 toastr.error(data.msg);
                //             }
                //         },'json');
                //     }
                // });
            });

        });
        

    </script>
</body>
</html>