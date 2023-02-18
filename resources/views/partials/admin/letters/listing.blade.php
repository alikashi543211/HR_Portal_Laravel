<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-primary">
            <th> Sr# </th>
            <th> Title </th>
            <th> Actions </th>
        </thead>
        <tbody>
            @foreach ($data as $letter)
                <tr>
                    <td>{{ $letter->id }}</td>
                    <td>{{ $letter->title }}</td>
                    <td>
                        @if (checkPermission(LETTERS, WRITE))
                            <a href="{{ url(config('data.admin.letters.mail') . '/' . $letter->id) }}" class="btn btn-warning">Generate</a>
                            <a href="{{ url(config('data.admin.letters.edit') . '/' . $letter->id) }}" class="btn btn-primary">Edit</a>
                            <a href="{{ url(config('data.admin.letters.delete') . '/' . $letter->id) }}" class="btn btn-danger">Delete</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}
</div>
