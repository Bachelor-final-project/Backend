<div class="col-lg-12 col-md-6 mb-md-0 mb-4">
    <div class="card">
        <div class="card-header pb-0">
            <div class="row justify-content-between">
                
                <div class="col-10">
                    <h6>{{Str::plural($modelClass)}}</h6>
                </div>
                <button id="showCreateModalBtn" type="button" class="btn btn-primary col-2" data-bs-toggle="modal" data-bs-target="#mainModal">{{__("Add New " . $modelClass)}}</button>
                {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal"> --}}
                
            </div>
        </div>
        <div class="card-body px-0 pb-2">
            <div class="table-responsive">
                <x-table :model-class="$modelClass" :headers="$headers"/>
                
            </div>
        </div>
    </div>
</div>
