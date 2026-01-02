@extends('layouts.app')
@section('content')
    <div class="container">
        <h2>Edit Employee</h2>
        {{-- <form method="POST" action="{{ route('employees.update', $employee->id) }}"> --}}
        <form id="employeeformupdate">
        <input type="hidden" id ="employee_id" value={{$employee->id}}>
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" name="first_name" class="form-control"
                    value="{{ old('first_name', $employee->first_name) }}">
                @error('first_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label>Last Name *</label>
                <input type="text" name="last_name" class="form-control"
                    value="{{ old('last_name', $employee->last_name) }}">
                @error('last_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label>Company *</label>
                <select name="company_id" class="form-control">
                    <option value="">Select Company</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ old('company_id', $employee->company_id) == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}</option>
                    @endforeach
                </select>
                @error('company_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                @error('phone')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            },

        });
        $('#employeeformupdate').on('submit', function(e) {
            e.preventDefault();
            let employeeId = $('#employee_id').val();
            let formData = new FormData(this);
            $.ajax({
                url: '/employees/' + employeeId,
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
                    }).then(() => {
                        window.location.href = response.redirect_url;
                    });
                    $('.text-danger').text('');
                },
                error: function(xhr) {
                    console.log(xhr.responsetext);
                    let errors = xhr.responseJSON?.errors;
                    $('.text-danger').text('');
               
                if(errors) {
                    $.each(errors, function(key, value) {
                        $('[name="' + key + '"]').next('.text-danger').text(value[0]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    });
                }
                 },
            })
        })
    })
</script>
@endpush
