<!-- Formulaire pour ajouter une nouvelle fonction à une tâche -->
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4 class="modal-title"><?php echo $fonctionAModifier->getNomFonction(); ?></h4>
    </div>
	<div class="modal-body">
		
		<ul class="nav nav-pills">
			<li class="active"><a href="#edit" data-toggle="tab">Edit this function</a></li>
			<li><a href="#delete" data-toggle="tab">Delete this function</a></li>
		</ul><br>

		<div class="tab-content">
			<div class="tab-pane active" id="edit">
				<div class="alert alert-warning" style="overflow-x:auto"><?php echo $texteSource;?></div>
				<div>
					<input type="hidden" value="<?php echo $fonctionAModifier->getIdFonction(); ?>" name="id" id="id"/>
					<div class="file-picker">
						<svg class="file-picker__icon" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
						<g>
						<polygon points="41.3,57.7 42.1,58.5 49.5,51.1 49.5,74.8 50.5,74.8 50.5,51.1 57.9,58.5 58.7,57.7 50,49    "/>
						<path d="M71.3,38.2c-0.1,0-0.2,0-0.3,0c-1.1-7.5-7.4-13-15.1-13c-6.5,0-12.3,4.2-14.4,10.3c-1.1-0.3-2.3-0.5-3.4-0.5
						c-6,0-11.1,4.4-12,10.3c-0.3,0-0.6-0.1-0.9-0.1c-4.7,0-8.6,3.8-8.6,8.6c0,4.7,3.8,8.6,8.6,8.6h10.8v-1.1H25.2
						c-4.1,0-7.5-3.4-7.5-7.5c0-4.1,3.3-7.5,7.5-7.5c0.4,0,0.8,0,1.3,0.1l0.6,0.1l0.1-0.6c0.6-5.6,5.3-9.9,10.9-9.9
						c1.2,0,2.4,0.2,3.6,0.6l0.5,0.2l0.2-0.5c1.8-5.9,7.3-10.1,13.5-10.1c7.2,0,13.3,5.4,14,12.6l0.1,0.5l0.5,0c0.2,0,0.5,0,0.7,0
						c6.1,0,11,4.9,11,11c0,6.1-4.9,11-11,11h-7v1.1h7c6.7,0,12.1-5.4,12.1-12.1C83.3,43.6,77.9,38.2,71.3,38.2z"/>
						</g>
						</svg>

						<svg class="file-picker__icon--cancel" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
						<path d="M51,50l14.9-15c0.3-0.3,0.3-0.7,0-1c-0.3-0.3-0.7-0.3-1,0L50,49L35.1,34.1c-0.3-0.3-0.7-0.3-1,0c-0.3,0.3-0.3,0.7,0,1L49,50
						L34.1,65c-0.3,0.3-0.3,0.7,0,1c0.1,0.1,0.3,0.2,0.5,0.2c0.2,0,0.4-0.1,0.5-0.2L50,51l14.9,14.9c0.1,0.1,0.3,0.2,0.5,0.2
						c0.2,0,0.4-0.1,0.5-0.2c0.3-0.3,0.3-0.7,0-1L51,50z"/>
						</svg>

						<div class="file-picker__progress" id="file-picker__progress"></div>

						<form class="file-picker__form dropzone" id="dropzone" method="POST" action="https://shary.herokuapp.com/api/upload/?redirect=true" enctype="multipart/form-data">
							<div class="dropzone__fallback hidden">
								<input name="file" type="file" />
								<input type="submit" name="submit" value="Upload file" />
							</div>
							<div class="hidden" id="dropzone__hidden"></div>
						</form>

						<div class="file-picker__overlay"></div>
					</div>
				</div>	
			</div>
			<div class="tab-pane" id="delete">
				<form class="well well-lg">
					<legend>Do you want to delete this function ?</legend>
					<input type="hidden" name="idFonction" value="<?php echo $fonctionAModifier->getIdFonction(); ?>"/>
					<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">
				     Delete
				 	</button>
				</form>
			</div>
		</div>
	</div>
