@component('mail::message')
    # Your comments

<table class="table table-sm" style="border-spacing: 2rem">
    <thead>
    <tr style="text-align: left">
        <th>#</th>
        <th>Date</th>
        <th>Comments</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $comment)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $comment->created_at }}</td>
            <td>{{ $comment->text }}</td>
        </tr>
    @endforeach
    </tbody>
</table>


Thanks,
Misha from Andersen
@endcomponent
