@extends('layouts.master')
@section('title','User List')
@section('content')

<div class="card">
    <h5 class="card-header">User List</h5>
    <div class="table-responsive">
        <table class="table table-bordered" id="data-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th width="100px">Action</th>
                </tr>
            </thead>
        </table>
    </div>


    <div class="modal fade" id="userUpdateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="userUpdateModalTitle">User Update</h5>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
            ></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name*</label>
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="name"
                        placeholder="Enter your name"
                        autofocus
                        required
                    />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email*</label>
                    <input
                        type="text"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        autofocus
                        value="{{ old('email') }}"
                        required
                    />
                </div>
                <div class="mb-3 form-password-toggle">
                    <label for="pasword" class="form-label">Password*</label>
                    <div class="input-group input-group-merge">
                        <input
                            type="password"
                            id="password"
                            class="form-control"
                            name="password"
                            placeholder="Enter Password"
                            aria-describedby="password"
                            required
                        />
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="user_id" value="">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" onclick="updateUserData()" class="btn btn-primary">Update</button>
            </div>
        </div>
        </div>
    </div>
    
</div>

@endsection
@push('script')
    <script type="text/javascript">
        $(function () {
            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'role', name:'role'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            }); 
        });
        function updateUser(userId){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content'); 
            $.ajax({
                url: "{{ route('getUserById') }}",
                type: 'post',
                data: {_token: CSRF_TOKEN,id: userId},
                dataType: 'json',
                success: function(response){
                    if(response.status){
                        $('#userUpdateModal').modal('show')
                        $('#name').val(response.data.name);
                        $('#email').val(response.data.email);
                        $('#user_id').val(response.data.id)
                    }else{
                        alert(response.message);
                    }
                }
            });
        }

        function updateUserData(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content'); 
            let user_id = $('#user_id').val()
            $.ajax({
                url: "{{ route('users.update') }}",
                type: 'post',
                data: {
                    _token: CSRF_TOKEN,id: user_id,
                    name: $('#name').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                },
                dataType: 'json',
                success: function(response){
                    if(response.status){
                        $('#userUpdateModal').modal('hide')
                        $('#data-table').DataTable().ajax.reload()
                    }else{
                        alert(response.message);
                    }
                }
            });
        }
    </script>
@endpush