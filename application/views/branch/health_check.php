<style>
.health-progress { height: 30px; border-radius: 15px; margin-bottom: 20px; }
.health-progress .progress-bar { font-size: 14px; font-weight: 700; line-height: 30px; }
.check-pass { color: #28a745; }
.check-fail { color: #dc3545; }
.check-warn { color: #ffc107; }
.health-card { margin-bottom: 15px; border-radius: 8px; overflow: hidden; }
.health-card .panel-heading { font-weight: 700; font-size: 15px; }
.score-badge { font-size: 13px; font-weight: 700; float: right; padding: 2px 10px; border-radius: 12px; }
.score-green { background: #d4edda; color: #155724; }
.score-yellow { background: #fff3cd; color: #856404; }
.score-red { background: #f8d7da; color: #721c24; }
.fix-text { font-size: 11px; color: #dc3545; font-style: italic; }
</style>

<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title">
			<i class="fas fa-heartbeat"></i> Branch Health Check: <strong><?=$branch['school_name']?></strong>
			<a href="<?=base_url('branch')?>" class="btn btn-default btn-sm pull-right"><i class="fas fa-arrow-left"></i> Back to Branches</a>
		</h4>
	</header>
	<div class="panel-body">

		<!-- Overall Score -->
		<div class="row mb-lg">
			<div class="col-md-8 col-md-offset-2 text-center">
				<h3>
					Overall Score: <strong style="font-size:36px; color:<?=$percentage >= 80 ? '#28a745' : ($percentage >= 50 ? '#ffc107' : '#dc3545')?>;"><?=$percentage?>%</strong>
					<small>(<?=$passedChecks?> of <?=$totalChecks?> checks passed)</small>
				</h3>
				<div class="progress health-progress">
					<div class="progress-bar <?=$percentage >= 80 ? 'progress-bar-success' : ($percentage >= 50 ? 'progress-bar-warning' : 'progress-bar-danger')?>" style="width: <?=$percentage?>%;">
						<?=$percentage?>% Complete
					</div>
				</div>
				<?php if ($percentage >= 90): ?>
					<div class="alert alert-success"><i class="fas fa-check-circle"></i> <strong>Excellent!</strong> This school is fully set up and ready to use.</div>
				<?php elseif ($percentage >= 70): ?>
					<div class="alert alert-info"><i class="fas fa-info-circle"></i> <strong>Good progress!</strong> A few items still need attention.</div>
				<?php elseif ($percentage >= 50): ?>
					<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> <strong>Partially set up.</strong> Several important items are missing.</div>
				<?php else: ?>
					<div class="alert alert-danger"><i class="fas fa-times-circle"></i> <strong>Needs attention!</strong> Many critical items are not set up yet.</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Section Cards -->
		<div class="row">
		<?php foreach ($checks as $key => $section):
			$sectionPercent = $section['total'] > 0 ? round(($section['passed'] / $section['total']) * 100) : 0;
			$scoreClass = $sectionPercent >= 80 ? 'score-green' : ($sectionPercent >= 50 ? 'score-yellow' : 'score-red');
		?>
			<div class="col-md-6">
				<div class="panel health-card">
					<div class="panel-heading" style="background:<?=$sectionPercent >= 80 ? '#f0faf0' : ($sectionPercent >= 50 ? '#fef9e7' : '#fdf2f2')?>;">
						<i class="<?=$section['icon']?>"></i> <?=$section['title']?>
						<span class="score-badge <?=$scoreClass?>"><?=$section['passed']?>/<?=$section['total']?></span>
					</div>
					<div class="panel-body" style="padding:0;">
						<table class="table table-condensed mb-none" style="font-size:13px;">
						<?php foreach ($section['items'] as $item): ?>
							<tr>
								<td style="width:30px; text-align:center; vertical-align:middle;">
									<?php if ($item['pass']): ?>
										<i class="fas fa-check-circle check-pass" style="font-size:18px;"></i>
									<?php else: ?>
										<i class="fas fa-times-circle check-fail" style="font-size:18px;"></i>
									<?php endif; ?>
								</td>
								<td>
									<strong><?=$item['name']?></strong>
									<?php if (!empty($item['detail'])): ?>
										<br><span style="color:#666; font-size:12px;"><?=$item['detail']?></span>
									<?php endif; ?>
									<?php if (!$item['pass'] && !empty($item['fix'])): ?>
										<br><span class="fix-text"><i class="fas fa-wrench"></i> <?=$item['fix']?></span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</table>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		</div>

	</div>
</section>
