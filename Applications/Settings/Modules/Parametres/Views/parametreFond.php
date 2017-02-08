<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-sm-6 col-sm-offset-1 sousMenu">
			<h2>Wallpaper settings</h2>
			<div class="col-lg-12">
				<form method="post" class="well well-lg col-sm-10" action="/Settings/ParametreFond/ChangerFond" enctype="multipart/form-data">
					<legend>Edit your wallpaper</legend>
					<div class="form-group">
						<input type="file" class="form-control" name="photo"/>
					</div>
					<button class="btn btn-primary" type="submit">Upload your new wallpaper</button>
				</form>
			</div>
			<div class="col-lg-12">
				<form method="post" class="well well-lg col-sm-10"  action="/Settings/ParametreFond/FondParDefaut" enctype="multipart/form-data">
					<legend>Back to the default desktop wallpaper</legend>
					<button class="btn btn-primary" type="submit">Back to the default wallpaper</button>
				</form>
			</div>
		</div>
		
	</div>
</div>
