<div class="modal fade confirmationModal" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">{{__('default_label.confirmation') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <a href="#!" class="col-md-6 confirmation-no"><button type="button" class="btn col-md-12 btn-success ">No</button></a>
                    <a href="#!" class="col-md-6 confirmation-yes"><button type="button" class="btn col-md-12 btn-danger ">Yes</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <div class="modal fade uploadExcelModal" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Upload Excel Sheet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 offset-2">

                        <form method="post" action="{{url(config('data.admin.attendance.excel'))}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <input name="excel" style="display: none" type="file" accept=".xls" required  />
                                </div>
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    <button class="btn btn-primary btn-round" id="upload-excel-sheet-button">Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
