<div class="col-md-6">
		<div class="search-module">

			<div class="search-module__header">

				<h2 class="search-module__title">E-tjänster</h2>
				<div class="post-count">
				<?php
					printf('Visar <span class="post-count__count">%s</span> av <span class="post-count__total">%d</span>', 12, 12);
				?>
				</div>
			</div>

			<ol class="search-module__items">

<?php

				global $sk_eservices, $searchPostMarkup, $sk_search;

				$eservices = $sk_eservices->search_eservices($sk_search->search_string);

				foreach( $eservices as $eservice) {

					printf($searchPostMarkup, 'eservice', $eservice['URL'], get_icon('alignleft'), $eservice['Name'], 'E-tjänst', $eservice['Category']);

				}

?>

			</ol>

			<div class="search-module__footer">
				<a href="//e-tjanster.sundsvall.se/">Alla e-tjänster</a>
			</div>

	</div>
</div>
