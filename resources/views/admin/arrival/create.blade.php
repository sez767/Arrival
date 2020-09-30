@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.arrival.actions.create'))

@section('body')

<div class="container-xl">

	<div class="card">

		<arrival-form :action="'{{ url('admin/arrivals') }}'" v-cloak inline-template>


			<form :action="action" method="POST" class="md-form" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="card-header">
					<i class="fa fa-plus"></i> {{ trans('admin.arrival.actions.create') }}
				</div>
				<div class="card-body ">
					@include('admin.arrival.components.form-elements')
					<div class="contain">
					<div class="row margin">
						<label class="col-form-label text-md-right col-md-2">Документи</label>
						<input  type="text" name="items[1][value]" id="in" class="form-control col-md-8"/>
						<input  type="hidden" name="items[1][type]" value="docs"/>
						<div id="addD" class="btn btn-success widthButton addD" data-type="docs">Додати ще</div>
					</div>
					</div>
					<div class="contain">
						<div class="row margin">				
						<label class="col-form-label text-md-right col-md-2">Їжа</label>
						<input  type="text" name="items[101][value]"  class="form-control col-md-8"/>
						<input  type="hidden" name="items[101][type]" value="food"/>
						<div id="addF" class="btn btn-success widthButton addF" data-type="food">Додати ще</div>
					</div>	
					</div>	
					
					<br>
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

					<div id="output" class="row containerImg"></div>
					<div class="card-footer">
						<button type="submit" class="btn btn-primary" :disabled="submiting">
							<i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
							Зберегти
						</button>
					</div>
				</div>
	</div>
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

				<button type="button" class="btn btn-secondary" data-dismiss="modal">Відміна</button>

				<button type="button" class="btn btn-primary" id="crop">Обрізати</button>

			</div>

		</div>

	</div>

</div>


</div>

</div>
@endsection