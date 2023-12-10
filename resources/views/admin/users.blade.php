@extends('admin.layout.layout')
@section('style')
    <link rel="stylesheet" href="{{ url('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>유저 정보</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <!-- SidebarSearch Form -->
                                <div class="form-inline">
                                    <div class="input-group">
                                        <input id="search-user" class="form-control form-control-sidebar" type="search"
                                               placeholder="Search"
                                               aria-label="Search"
                                               onkeydown="onKeyDownSearch(event)"
                                        >
                                        <div class="input-group-append">
                                            <button class="btn btn-sidebar" onclick="onClickUserSearch()">
                                                <i class="fas fa-search fa-fw"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="users" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>VerifiedAt</th>
                                        <th>role</th>
                                        <th>CreatedAt</th>
                                        <th>UpdatedAt</th>
                                        <th>DeletedAt</th>
                                        <td>수정</td>
                                        <td>삭제</td>
                                    </tr>
                                    </thead>
                                    <tbody onclick="onClickAction(event)">
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->email_verified_at }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>{{ $user->created_at }}</td>
                                            <td>{{ $user->updated_at }}</td>
                                            <td>{{ $user->deleted_at }}</td>
                                            <td>
                                                <button type="submit" class="btn btn-primary btn-block"
                                                        data-toggle="modal" data-target="#modify-user-modal"
                                                        data-id="{{ $user->id }}" data-type="modify"
                                                        data-name="{{$user->name}}" data-role="{{$user->role}}">
                                                    수정
                                                </button>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-danger btn-block"
                                                        data-id="{{ $user->id }}" data-type="delete">삭제
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>VerifiedAt</th>
                                        <th>role</th>
                                        <th>CreatedAt</th>
                                        <th>UpdatedAt</th>
                                        <th>DeletedAt</th>
                                        <td>수정</td>
                                        <td>삭제</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div id="modify-user-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">유저 수정</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">이름</span>
                        </div>
                        <input id="user-name" type="text" class="form-control" aria-label="Default"
                               aria-describedby="inputGroup-sizing-default">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">역할</span>
                        </div>
                        <input id="user-role" type="text" class="form-control" aria-label="Default"
                               aria-describedby="inputGroup-sizing-default">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="onClickModifyUser()">적용</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables  & Plugins -->
    <script src="{{ url('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ url('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ url('admin/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ url('admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ url('admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ url('admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ url('admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Page specific script -->
    <script>
        let targetUserId = -1;

        function onClickDeleteUser(id) {
            if (!id || id === -1) {
                return;
            }

            $.ajax({
                type: 'delete',
                url: '/admin/users',
                headers: {
                    "Content-Type": "application/json"
                },
                data: JSON.stringify({
                    id
                }),
                success: function (data) {
                    window.location.reload();
                },
                error: function (req, status, error) {
                    alert("삭제 불가");
                }
            })
        }

        function onClickModifyUser() {
            const name = $('#user-name').val();
            const role = $('#user-role').val();
            const id = targetUserId;
            $.ajax({
                type: 'put',
                url: '/admin/users',
                headers: {
                    "Content-Type": "application/json"
                },
                data: JSON.stringify({
                    name,
                    role,
                    id
                }),
                success: function (data) {
                    window.location.reload();
                },
                error: function (req, status, error) {
                    alert("수정 불가");
                }
            })
        }

        function onClickAction(event) {
            const target = event.target;
            if (!target.dataset.id) {
                return;
            }

            targetUserId = target.dataset.id;
            const type = target.dataset.type;

            switch (type) {
                case 'modify':
                    $('#user-name').val(target.dataset.name);
                    $('#user-role').val(target.dataset.role);
                    break;
                case 'delete':
                    onClickDeleteUser(target.dataset.id);
                    break;
                default:
                    alert("지정한 액션 타입이 아닙니다.");
                    break;
            }
        }

        function onKeyDownSearch(event) {
            if (event.keyCode !== 13) {
                return;
            }

            onClickUserSearch();
        }

        function onClickUserSearch() {
            const searchText = $('#search-user').val();
            if (!searchText || searchText.length === 0) {
                alert("검색 키워드가 없습니다.");

                return;
            }

            window.location.href = `http://127.0.0.1:56000/admin/users?search=${searchText}`;
        }

        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $('#users').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endsection
