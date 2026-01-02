@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Company</h2>
<form id="companyupdateform" enctype="multipart/form-data">
    <input type="hidden" id="company_id" value="{{ $company->id }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Name *</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $company->name) }}">
        <small class="text-danger"></small>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $company->email) }}">
        <small class="text-danger"></small>
    </div>

    <div class="form-group">
        <label>Logo (min 100x100)</label>
        <div id="logo-dropzone" class="dropzone mb-2"></div>
        <input type="file" name="logo" id="logoInput" hidden>
        @if($company->logo)
            <img src="{{ asset('storage/' . $company->logo) }}" width="50" height="50" id="existingLogo">
        @endif
        <small class="text-danger"></small>
    </div>

    <div class="form-group">
        <label>Website</label>
        <input type="text" name="website" class="form-control" value="{{ old('website', $company->website) }}">
        <small class="text-danger"></small>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
      
    </div>
@endsection
@push('scripts')
    <script>
     Dropzone.autoDiscover= false;
    var myDropzone=new Dropzone('#logo-dropzone',{
        url:"#",
        autoProcessQueue:false,
        maxFiles:1,
        acceptedFiles:".png,.jpg,.jpeg",
        init:function(){
            var dz=this;
             // Load existing logo as mock file
            @if($company->id)
            var mockFile={ name: "Existing Logo", size: 12345, type: 'image/jpeg' };
            dz.emit("addedfile",mockFile);
            dz.emit("thumbnail",mockFile,"{{asset('storage/'.$company->logo)}}");
            dz.files.push(mockFile);
            // Sync Dropzone file with hidden input
            dz.on("addfile",function(file){
                let dataTransfer=new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('logoInput').files=dataTransfer.files;
            });
            @endif
            dz.on("maxfilesexceeded",function(file){
                dz.removeAllFiles();
                dz.addFile(file);
            })

            this.on("addedfile",function(file){
                let  dataTransfer=new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('logoInput').files=dataTransfer.files;
            });
            this.on("maxfilesexceeded",function(file){
                this.removeAllFiles();
                this.addFile(file);
            });
        }
    });
        $(document).ready(function() {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                    });
                    $('#companyupdateform').on('submit', function(e) {
                        e.preventDefault();
                        let companyId = $('#company_id').val();
                        let formData = new FormData(this);

                        $.ajax({
                            url: '/companies/' + companyId,
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(()=>{
                                    window.location.href=response.redirect_url;
                                });
                                $('.text-danger').text('');
                            },

                            error: function(xhr){
                                console.log(xhr.responseText);
                                let errors = xhr.responseJSON?.errors;
                                $('.text-danger').text('');

                                if (errors) {
                                    $.each(errors, function(key, value) {
                                        $('[name="' + key + '"]').next('.text-danger').text(
                                            value[0]);
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong!',
                                    });
                                }}});
                    })});

                    </script>
                    @endpush;
                
