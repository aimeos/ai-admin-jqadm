<div class="order-saleslastmonth card col-lg-12">
	<div id="order-saleslastmonth-head" class="card-header header" role="tab"
		 data-toggle="collapse" data-target="#order-saleslastmonth-data"
		 aria-expanded="true" aria-controls="order-saleslastmonth-data">
		<div class="card-tools-left">
			<div class="btn btn-card-header act-show fa"></div>
		</div>
		<span class="item-label header-label">
			<?= $enc->html( $this->translate( 'admin', 'Sales of the last 30 days' ) ); ?>
		</span>
	</div>
	<div id="order-saleslastmonth-data" class="card-block collapse show content loading" role="tabpanel" aria-labelledby="order-saleslastmonth-head">
	</div>
</div>
