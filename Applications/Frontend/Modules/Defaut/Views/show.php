		<?php $userSession = unserialize($user->getAttribute('userSession')); ?>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="col-sm-8 text-center centering maxWidth fadeIn">
					<a class="caseMenu text-left infoBulleGeneralMenuTop" href="/Library/" title="Search an application in the library">
						<p>Library</p>
						<img src="/Images/library.png" alt="Library"/>
					</a>
					<a class="caseMenu text-left infoBulleGeneralMenuTop" href="/NooSpace/" title="The workspace for running applications">
						<p>NooSpace</p>
						<img src="/Images/outils.png" alt="NooSpace"/>
					</a>
					<a class="caseMenu text-left infoBulleGeneralMenuTop" href="/SubmitAnApplication/" title="Submit a new application">
						<p>Submit</p>
						<img src="/Images/ajouterApp.png" alt="Submit a new application"/>
					</a>
					<a class="caseMenu text-left infoBulleGeneralMenuBottom" href="/ManagerOfApplications/" title="Manage your own applications">
						<p>Manage</p>
						<img src="/Images/gererApp.png" alt="Manage your own applications"/>
					</a>
					<a class="caseMenu text-left infoBulleGeneralMenuBottom" href="/Settings/" title="Edit your settings and manage your account">
						<p>Settings</p>
						<img src="/Images/parameters.png" alt="Settings"/>
					</a>
					<?php if($userSession->getPasswordAdminUtilisateur() != ''){?>
					<a class="caseMenu text-left infoBulleGeneralMenuBottom" href="/PourAdminSeulement/" title="Need to administrate NooLib ?">
						<p>Administrator</p>
						<img src="/Images/admin.png" alt="Administration"/>
					</a>
					<?php } ?>
				</div>
			</div>
		</div>