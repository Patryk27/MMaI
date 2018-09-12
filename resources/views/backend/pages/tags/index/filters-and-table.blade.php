@php
    /**
     * @var \Illuminate\Support\Collection|int[] $languages
     */
@endphp

<div id="tags-loader" data-loader-type="tile">
    {{ Form::open([
        'id' => 'tags-search-form',
        'class' => 'form-inline',
    ]) }}

    <div class="form-group">
        {{ Form::label('language_id', 'Show tags for language:') }}

        {{ Form::select('language_id', $languages, null, [
            'class' => 'custom-select',
        ]) }}
    </div>

    {{ Form::close() }}

    <table id="tags-table" class="table table-striped table-dark">
        <thead>
        <tr>
            <th data-datatable-column='{"name": "id", "orderable": true}'>
                Id
            </th>

            <th data-datatable-column='{"name": "name", "orderable": true}'>
                Name
            </th>

            <th data-datatable-column='{"name": "page_variant_count", "orderable": true}'>
                Number of pages / posts
            </th>

            <th data-datatable-column='{"name": "created_at", "orderable": true}'>
                Created at
            </th>

            <th data-datatable-column='{"name": "actions"}'>
                &nbsp;
            </th>
        </tr>
        </thead>

        <tbody>
        </tbody>
    </table>
</div>
