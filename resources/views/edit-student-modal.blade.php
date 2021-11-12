<div class="modal fade editStudent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                 <form action="<?= route('update.student.details') ?>" method="post" id="update-student-form">
                    @csrf
                     <input type="hidden" name="cid">
                     <div class="form-group">
                         <label for="">Student name</label>
                         <input type="text" class="form-control" name="student_name" placeholder="Enter student name">
                         <span class="text-danger error-text student_name_error"></span>
                     </div>
                     <div class="form-group">
                         <label for="">Student Major</label>
                         <input type="text" class="form-control" name="student_major" placeholder="Enter student major">
                         <span class="text-danger error-text student_major_error"></span>
                     </div>
                     <div class="form-group">
                         <button type="submit" class="btn btn-block btn-success">Save Changes</button>
                     </div>
                 </form>
                

            </div>
        </div>
    </div>
</div>