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
                        <h1>토론 정보</h1>
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
                                            <button class="btn btn-sidebar" onclick="onClickTopicSearch()">
                                                <i class="fas fa-search fa-fw"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="topics" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>UserId</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>CreatedAt</th>
                                        <th>UpdatedAt</th>
                                        <th>DeletedAt</th>
                                        <td>수정</td>
                                        <td>삭제</td>
                                    </tr>
                                    </thead>
                                    <tbody onclick="onClickAction(event)">
                                    @foreach ($topics as $topic)
                                        <tr>
                                            <td>{{ $topic->id }}</td>
                                            <td>{{ $topic->user_id }}</td>
                                            <td>{{ $topic->title }}</td>
                                            <td>{{ $topic->description }}</td>
                                            <td>{{ $topic->created_at }}</td>
                                            <td>{{ $topic->updated_at }}</td>
                                            <td>{{ $topic->deleted_at }}</td>
                                            <td>
                                                <button type="submit" class="btn btn-primary btn-block"
                                                        data-toggle="modal" data-target="#modify-user-modal"
                                                        data-id="{{ $topic->id }}" data-type="modify"
                                                        data-title="{{$topic->title}}"
                                                        data-description="{{$topic->description}}">
                                                    수정
                                                </button>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-danger btn-block"
                                                        data-id="{{ $topic->id }}" data-type="delete">삭제
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>UserId</th>
                                        <th>Title</th>
                                        <th>Description</th>
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
                    <h5 class="modal-title">토론 수정</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">타이틀</span>
                        </div>
                        <input id="topic-title" type="text" class="form-control" aria-label="Default"
                               aria-describedby="inputGroup-sizing-default">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">설명</span>
                        </div>
                        <input id="topic-description" type="text" class="form-control" aria-label="Default"
                               aria-describedby="inputGroup-sizing-default">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="onClickModifyTopic()">적용</button>
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
        let targetTopicId = -1;

        function onClickDeleteTopic(id) {
            if (!id || id === -1) {
                return;
            }

            $.ajax({
                type: 'delete',
                url: '/admin/topics',
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

        function onClickModifyTopic() {
            const title = $('#topic-title').val();
            const desc = $('#topic-description').val();
            const id = targetTopicId;
            $.ajax({
                type: 'put',
                url: '/admin/topics',
                headers: {
                    "Content-Type": "application/json"
                },
                data: JSON.stringify({
                    title,
                    description: desc,
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

            targetTopicId = target.dataset.id;
            const type = target.dataset.type;

            switch (type) {
                case 'modify':
                    $('#topic-title').val(target.dataset.title);
                    $('#topic-description').val(target.dataset.description);
                    break;
                case 'delete':
                    onClickDeleteTopic(target.dataset.id);
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

            onClickTopicSearch();
        }

        function onClickTopicSearch() {
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

            $('#topics').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endsection
