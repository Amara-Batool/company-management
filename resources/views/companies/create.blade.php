@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Add Company</h2>
        {{-- <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data"> --}}
        <form id="companycreate" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" class="form-control name" value="{{ old('name') }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control email" value="{{ old('email') }}">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label>Logo (min 100x100)</label>
                 <div id ="logo-dropzone" class ="dropzone"></div>
                <input type="file" name="logo"  class="form-control logo " id="logoInput"  hidden>
                @error('logo')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label>Website</label>
                <input type="text" name="website" class="form-control website" value="{{ old('website') }}">
                @error('website')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
@endsection
@push('scripts')
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
    <script>
    Dropzone.autoDiscover= false;
    var myDropzone=new Dropzone('#logo-dropzone',{
        url:"#",
        autoProcessQueue:false,
        maxFiles:1,
        acceptedFiles:".png,.jpg,.jpeg",
        init:function(){
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

            


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#companycreate").on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('companies.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000,


                        });
                        $('#companycreate')[0].reset();
                        setTimeout(function(){
                            window.location.href= response.redirect_url;
                        },2000);
                },

                error: function(xhr) {
                    console.log(xhr.responseText);
                    let errors = xhr.responseJSON?.errors;
                    $('.text-danger').remove();
                    if (errors) {
                        $.each(errors, function(key, value) {
                            $('[name="' + key + '"]').after(
                                '<small class="text-danger">' + value[0] + '</small>'
                            );
                        });
                    } else {
                        swal.fire({
                            icon: 'Error',
                            title: 'Oops....',
                            text: 'something went wrong',
                            textConfirmButton: 'Okay',

                        })
                    }
                }
            });
        });
    </script>
@endpush
