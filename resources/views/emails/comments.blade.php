@component('mail::message')
    # Your comments

<table class="table table-sm" style="border-spacing: 2rem">
    <thead>
    <tr style="text-align: left">
        <th>Date</th>
        <th>Comments</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $comment)
        <tr">
            <td>{{ $comment['date'] }}</td>
            <td>{{ $comment['text'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>


Thanks,
Misha from Andersen
@endcomponent
