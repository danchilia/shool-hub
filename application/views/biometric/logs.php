<?php if ($unmatched_count > 0): ?>
<div class="alert alert-warning">
	<i class="fas fa-exclamation-triangle"></i> <strong><?=$unmatched_count?> scans</strong> could not be matched to a student/staff record.
	Go to <a href="<?=base_url('biometric/mapping')?>">Biometric &gt; ID Mapping</a> to link the missing biometric IDs.
</div>
<?php endif; ?>

<section class="panel">
	<header class="panel-heading"><h4 class="panel-title"><i class="fas fa-history"></i> Recent Scan Logs (latest 200)</h4></header>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-condensed table-export mb-none">
				<thead>
					<tr>
						<th>#</th><th>Scan Time</th><th>Biometric ID</th><th>Name</th><th>Type</th><th>Device</th><th>Source</th><th>Status</th>
					</tr>
				</thead>
				<tbody>
				<?php $c = 1; if (count($logs)): foreach ($logs as $l): ?>
					<tr>
						<td><?=$c++?></td>
						<td><?=_d($l['scan_time'])?></td>
						<td><?=$l['biometric_id']?></td>
						<td><?=$l['matched'] ? '<strong>' . $l['person_name'] . '</strong> (' . ucfirst($l['person_type']) . ')' : '<span class="text-danger">Unmatched</span>'?></td>
						<td><?=ucfirst($l['scan_type'])?></td>
						<td><?=!empty($l['device_name']) ? $l['device_name'] . ' (' . $l['location'] . ')' : '-'?></td>
						<td><?=$l['source'] == 'api_push' ? 'Live Device' : 'CSV Import'?></td>
						<td><?=$l['processed'] ? '<span class="label label-success">Attendance Marked</span>' : '<span class="label label-default">Not Processed</span>'?></td>
					</tr>
				<?php endforeach; else: ?>
					<tr><td colspan="8"><h5 class="text-danger text-center"><?=translate('no_information_available')?></h5></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>
