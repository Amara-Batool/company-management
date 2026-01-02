@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Companies
    <a class="btn btn-success my-4 p-2 " href="{{ route('employees.index') }}"> Go to Employee</a>
        <a href="{{ route('companies.create') }}" class="btn btn-primary float-right">Add Company</a>
    </h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Logo</th>
                <th>Website</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
            <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->email }}</td>
                <td>
                    @if($company->logo)
                    <img src="{{ asset('storage/'.$company->logo) }}" width="50" height="50">
                    @endif
                </td>
                <td>{{ $company->website }}</td>
                <td>
                    <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    {{-- <form action="{{ route('companies.destroy', $company->id) }}" method="POST" style="display:inline-block;"> --}}
                       <form class="delete-company-form" data-id="{{ $company->id }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" >Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $companies->links() }}
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".delete-company-form").on('submit', function(e){
        e.preventDefault();

        let form = $(this);
        let companyId = form.data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: '/companies/' + companyId,
                    type: 'POST',
                    data: form.serialize(), // _method=DELETE & _token
                    success: function(response){
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        form.closest('tr').remove(); // remove row from table
                    },
                    error: function(xhr){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        });
                    }
                });
            }
        });
    });

});
</script>
@endpush

