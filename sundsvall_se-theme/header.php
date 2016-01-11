<?php get_template_part('head'); ?>

<header class="container-fluid">

	<div class="row">

		<div class="col-md-4 col-sm-12">
			<h1 class="site-title"><?php bloginfo( 'title' ); ?></h1>
		</div>

		<?php //Search form ?>
		<div class="col-md-8 col-sm-12">
			<form>
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Vad kan vi hjälpa dig med?">
					<span class="input-group-btn">
						<button class="btn btn-secondary" type="button">Go!</button>
					</span>
				</div>
			</form>
		</div>

	</div>

</header>


