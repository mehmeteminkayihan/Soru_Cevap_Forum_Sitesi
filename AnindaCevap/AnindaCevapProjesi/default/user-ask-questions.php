<?php include("header.php");?>

		<!-- Start Mail Content Area -->
		<div class="main-content-area ptb-100">
			<div class="container">
				<div class="row">
					<div class="col-lg-3">
						<div class="sidebar-menu-wrap">
							<div class="sidemenu-wrap d-flex justify-content-between align-items-center">
								<h3>AC Kenar Çubuğu Menüsü </h3>
								<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
									<i class="ri-menu-line"></i>
								</button>
							</div>
							  
							<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
								<div class="offcanvas-header">
									<h5 class="offcanvas-title" id="offcanvasExampleLabel">Menu</h5>
									<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
								</div>
								<div class="offcanvas-body">
									<div class="left-sidebar">
									<?php include("sidebar.php");?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="middull-content">
							<form class="your-answer-form">
								<div class="form-group">
									<h3>Create a questions</h3>
								</div>
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control">
								</div>
								<div class="form-group">
									<label>Category</label>

									<select class="form-select form-control" aria-label="Default select example">
										<option selected="">Selete cagegory</option>
										<option value="1">One</option>
										<option value="2">Two</option>
										<option value="3">Three</option>
									</select>
								</div>
								<div class="form-group">
									<label>Tags (Add up to 5 tags to describe what your question is about)</label>
									<input type="text" class="form-control">
								</div>
								<div class="form-group">
									<label>Description</label>
									<div id="txtEditor"></div>
								</div>
								<div class="form-group">
									<div class="file-upload-account-info">
										<input type="file" name="file" id="file-2" class="inputfile">
										<label class="upload">
											<i class="ri-link"></i>
											Upload Photo
										</label>
									</div>
								</div>
								<div class="form-group">
									<button type="submit" class="default-btn">Post your answer</button>
								</div>
							</form>
						</div>
					</div>
					<?php include("rightsidebar.php");?>
				</div>
			</div>
		</div>
		<!-- End Mail Content Area -->

		<?php include("footer.php");?>