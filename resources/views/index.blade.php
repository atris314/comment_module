@extends('admin.index')

@push('style')
    <style>
        .limit {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            line-clamp: 1;
            -webkit-box-orient: vertical;
        }
    </style>
@endpush

@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin') }}">
                        داشبورد
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    لیست نظرات
                </li>
            </ol>
        </nav>
    </div>
    <hr />
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">لیست نظرات</h5>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th style='width:50px;'>ردیف</th>
                        <th style="width:110px;">نام و نام خانوادگی</th>
                        <th>خلاصه نظر</th>
                        <th style='width:150px;'>وضعیت</th>
                        <th style='width:40px;'>نمایش</th>
                        <th style='width:30px;'>مقاله</th>
                        <th style='width:50px;'>جزئیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($comments as $comment)
                        @php
                            $blog = \App\Models\Blog::where('id',$comment->comments_id)->first();
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$comment->name}}</td>
                            <td>
                                <p class="limit mb-0">{{ $comment->text }}</p>
                            </td>
                            <td>
                                <form action="{{ route('comments.status', $comment->id) }}" method="post">
                                    <select name="status" class="form-select" onchange="this.form.submit();">
                                        <option value="new" @if($comment->status == 'pending') selected @endif>در انتظار تایید</option>
                                        <option value="accepted" @if($comment->status == 'accepted') selected @endif>تایید شده</option>
                                        <option value="rejected" @if($comment->status == 'rejected') selected @endif>رد شده</option>
                                    </select>
                                    @csrf
                                </form>
                                {{--                                    {!! $comment->HtmlStatus !!}--}}
                            </td>
                            <td>
                                <form action="{{ route('comments.show', $comment->id) }}" method="post">
                                    @csrf
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="status" type="checkbox"  onchange="this.form.submit();" @if($comment->status == 'accepted') checked @endif>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <a href="{{route('blog',$blog->slug)}}" target="_blank" class="text-primary ms-3">
                                    <i class="bx bx-link-external"></i>
                                </a>
                            </td>
                            <td>
                                <a href="javascript:void(0)"
                                   onclick="openCommentModal({{$comment}})"
                                   class="text-primary ms-3">
                                    <i class="bx bxs-show"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel">
        <div class="modal-dialog">
            <form action="{{route('comment.reply')}}" method="post" class="modal-content needs-validation" novalidate>
                @csrf
                <input type="hidden" name="comment_id" id="commentId">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p style="text-align:justify"></p>
                    <div class="mt-3">
                        <label for="answer" class="form-label">پاسخ</label>
                        <textarea name="text" value="{{old('text')}}" id="answer" class="form-control" required>در صورتی که پاسخ داده شده بود، متن پاسخ اینجا نمایش داده میشه</textarea>
                        <div class="invalid-feedback">پاسخ الزامی است</div>
                    </div>
                </div>
                <div class="modal-footer d-flex align-items-center justify-content-between">
                    <div class="d-flex gap-3">
                        <a id="callUser" href="" style="display:none;">
                            <i class="bx bx-phone fs-3"></i>
                        </a>
                        <a id="mailUser" href="" style="display:none;">
                            <i class="bx bx-envelope fs-3"></i>
                        </a>
                    </div>
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                            بستن
                        </button>
                        <button type="submit" class="btn btn-danger">
                            ارسال پاسخ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                language: {
                    "url": dataTableLangUrl
                }
            });
        });

        function openCommentModal(comment) {
            $('#commentModalLabel').text(`نظر کاربر "${comment.name}"`);
            $('.modal-body p').text(comment.text);
            $('#commentId').val(comment.id);

            if(comment.answer) {
                $('#answer').text('متن پاسخ ادمین');
            } else {
                $('#answer').text('');
            }

            if(comment.mobile) {
                $('#callUser').attr("href", `tel:${comment.mobile}`);
                $('#callUser').show();
            } else {
                $('#callUser').hide();
            }

            if(comment.email) {
                $('#mailUser').attr("href", `mailto:${comment.email}`);
                $('#mailUser').show();
            } else {
                $('#mailUser').hide();
            }

            $('#commentModal').modal('show');
        }
    </script>
@endpush
