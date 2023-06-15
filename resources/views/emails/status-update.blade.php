@if ($status == 'Đã duyệt')
<h1>Thông báo sự kiện</h1>
<p>Chúc mừng sự kiện "{{$event->title}}" của bạn đã được duyệt</p>
<!-- Nội dung email chúc mừng sự kiện đã được duyệt -->
@elseif ($status == 'Không duyệt')
    <h1>Thông báo sự kiện</h1>
    <p>Sự kiện "{{ $event->title }}" của bạn không đáp ứng được yêu cầu!</p>
    <p>Vui lòng kiểm tra lại và thực hiện các điều chỉnh cần thiết.</p>
    <!-- Nội dung email thông báo sự kiện không đáp ứng được yêu cầu -->

<!-- Nội dung email thông báo sự kiện không đáp ứng được yêu cầu -->
@elseif ($status == 'Công Khai')
<h1>Thông báo sự kiện</h1>
<p> Sự kiện "{{$event->title}}" của bạn đang được công khai</p>
@elseif ($status == 'Ẩn')
<h1>Thông báo sự kiện</h1>
<p> Sự kiện "{{$event->title}}" của bạn đã được ẩn đi</p>
@else
<h1>Thông báo sự kiện</h1>
@endif