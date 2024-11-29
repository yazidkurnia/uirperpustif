<table id="dataTable" class="table table-striped table-responsive">
    <thead>
        <tr>
            @for ($i = 0; $i < count($configHeaderTable); $i++)
                <th>{{ $configHeaderTable[$i] }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
