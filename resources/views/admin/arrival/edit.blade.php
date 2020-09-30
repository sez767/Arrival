@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.arrival.actions.edit', ['name' => $arrival->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <arrival-form
                :action="'{{ $arrival->resource_url }}'"
                :data="{{ $arrival->toJson()}}"
                v-cloak
                inline-template>
            
                <form :action="action" method="POST" enctype="multipart/form-data">
                 {{ csrf_field() }}


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.arrival.actions.edit', ['name' => $arrival->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.arrival.components.form-elements')
                        <!-- {{Carbon\Carbon::today()}}
                        {{$arrival->begin}}; -->
                    <div class="container center-block">
						<div class="file-field">
							<div class="btn btn-primary ">
								<span>&check; Додати фото</span>
								<input type="file" class="image" name="images[]"  id="file-selector" accept=".jpg, .jpeg, .png" >
							</div>
							<div class="file-path-wrapper">
								<input class="file-path validate hide" type="text" placeholder="">
							</div>
						</div>
						<!-- <input type="file" class="image" name="images[]"  id="file-selector" > -->
					</div>
					<br><br>
					<p id="status">Ви можете повторно відредагувати фото клікнувши по ньому.</p>

                    <div id="output" class="row containerImg">
                    
                        @foreach($arrival->images as $image)
                                    
                            <div class="containerDiv wrap">
                                <img src="{{'data:image/png;base64,'.$image->filedata}}" class="smallImg img-thumbnail" alt="img" >
                                <input type=hidden class="input" name="hideimage[]" value="{{'data:image/png;base64,'.$image->filedata}}">
                                <div class="btn btn-danger del">&times</div>
                            </div>
                        @endforeach
                     </div>
                    </div>
                   
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </arrival-form>

        </div>
    
</div>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title" id="modalLabel">Виберіть розмір</h5>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">

				<div class="img-container">

					<div class="row">

						<div class="col-md-8">

							<img id="image" src="">

						</div>

						<div class="col-md-4">

							<div class="preview"></div>

						</div>

					</div>

				</div>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

				<button type="button" class="btn btn-primary" id="crop">Crop</button>

			</div>

		</div>

	</div>

</div>


</div>

</div>
@endsection