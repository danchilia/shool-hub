<div class="row">
<?php if (get_permission('cbc_pathways', 'is_add')): ?>
<div class="col-md-4">
    <section class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-route"></i> Add Pathway</h4>
        </header>
        <?php echo form_open($this->uri->uri_string()); ?>
        <div class="panel-body">
            <p class="text-muted" style="font-size:12px;">Senior Secondary (Grade 10–12) pathways: STEM, Arts & Sports Science, Social Sciences.</p>
            <div class="form-group">
                <label class="control-label">Pathway Name <span class="required">*</span></label>
                <input type="text" class="form-control" name="name" value="<?=set_value('name')?>" placeholder="e.g. STEM" />
                <span class="error"><?=form_error('name')?></span>
            </div>
            <div class="form-group">
                <label class="control-label">Description <small class="text-muted">(optional)</small></label>
                <textarea class="form-control" name="description" rows="2" placeholder="Brief description of this pathway"><?=set_value('description')?></textarea>
            </div>
        </div>
        <div class="panel-footer">
            <button class="btn btn-default float-end" type="submit" name="save" value="1">
                <i class="fas fa-plus-circle"></i> <?=translate('save')?>
            </button>
        </div>
        <?php echo form_close(); ?>
    </section>

    <!-- Assign Learning Areas to Pathways -->
    <?php if (!empty($pathways) && !empty($learning_areas)): ?>
    <section class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-link"></i> Assign Learning Areas to Pathways</h4>
        </header>
        <div class="panel-body">
            <p class="text-muted" style="font-size:12px;">Set which pathway each Senior Secondary Learning Area belongs to. Leave blank for core subjects (all pathways).</p>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead><tr><th>Learning Area</th><th>Pathway</th></tr></thead>
                    <tbody>
                    <?php foreach ($learning_areas as $la): ?>
                    <tr>
                        <td style="font-size:12px;"><?=htmlspecialchars($la['name'])?></td>
                        <td>
                            <select class="form-control input-sm la-pathway-select" data-la="<?=$la['id']?>">
                                <option value="">— Core (all) —</option>
                                <?php foreach ($pathways as $p): ?>
                                <option value="<?=$p['id']?>" <?=$la['pathway_id'] == $p['id'] ? 'selected' : ''?>><?=htmlspecialchars($p['name'])?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="col-md-<?=get_permission('cbc_pathways', 'is_add') ? '8' : '12'?>">
    <section class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-list-ul"></i> Senior Secondary Pathways</h4>
        </header>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0 table-export">
                    <thead>
                        <tr>
                            <th><?=translate('sl')?></th>
                            <th>Pathway Name</th>
                            <th>Description</th>
                            <th>Learning Areas</th>
                            <th><?=translate('action')?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; if (!empty($pathways)): foreach ($pathways as $row):
                        $laCount = $this->db->where('pathway_id', $row['id'])->count_all_results('cbc_learning_areas');
                        $coreCount = $this->db->where('branch_id', $row['branch_id'])->where('level', 'senior_secondary')->where('pathway_id IS NULL', null, false)->count_all_results('cbc_learning_areas');
                    ?>
                    <tr>
                        <td><?=$count++?></td>
                        <td><strong><?=htmlspecialchars($row['name'])?></strong></td>
                        <td style="font-size:12px;color:#666;"><?=htmlspecialchars($row['description'] ?: '—')?></td>
                        <td>
                            <span class="badge badge-info"><?=$laCount?> specific</span>
                            <span class="badge badge-default"><?=$coreCount?> core</span>
                        </td>
                        <td>
                            <?php if (get_permission('cbc_pathways', 'is_edit')): ?>
                            <a class="btn btn-default btn-circle icon" href="javascript:void(0);"
                               onclick="getPathwayModal(this)"
                               data-id="<?=$row['id']?>"
                               data-name="<?=htmlspecialchars($row['name'], ENT_QUOTES)?>"
                               data-description="<?=htmlspecialchars($row['description'] ?: '', ENT_QUOTES)?>">
                                <i class="fas fa-pen-nib"></i>
                            </a>
                            <?php endif; if (get_permission('cbc_pathways', 'is_delete')): ?>
                            <?php echo btn_delete('cbc/pathway_delete/' . $row['id']); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5"><h5 class="text-danger text-center"><?=translate('no_information_available')?></h5></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
</div>

<?php if (get_permission('cbc_pathways', 'is_edit')): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
    <section class="panel">
        <?php echo form_open('cbc/pathway_edit', array('class' => 'frm-submit')); ?>
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-route"></i> Edit Pathway</h4>
        </header>
        <div class="panel-body">
            <input type="hidden" name="pathway_id" id="edit_pathway_id" />
            <div class="form-group">
                <label class="control-label">Pathway Name <span class="required">*</span></label>
                <input type="text" class="form-control" name="name" id="edit_pathway_name" />
            </div>
            <div class="form-group">
                <label class="control-label">Description</label>
                <textarea class="form-control" name="description" id="edit_pathway_desc" rows="2"></textarea>
            </div>
        </div>
        <footer class="panel-footer">
            <div class="row"><div class="col-md-12 text-right">
                <button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                    <i class="fas fa-plus-circle"></i> <?=translate('update')?>
                </button>
                <button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
            </div></div>
        </footer>
        <?php echo form_close(); ?>
    </section>
</div>

<script>
function getPathwayModal(el) {
    $('#edit_pathway_id').val($(el).data('id'));
    $('#edit_pathway_name').val($(el).data('name'));
    $('#edit_pathway_desc').val($(el).data('description'));
    mfp_modal('#modal');
}

$(document).on('change', '.la-pathway-select', function() {
    var laId = $(this).data('la');
    var pathwayId = $(this).val();
    $.post(base_url + 'cbc/pathway_assign_la', {la_id: laId, pathway_id: pathwayId}, function(r) {
        if (r.status !== 'success') alert('Failed to update assignment.');
    }, 'json');
});
</script>
<?php endif; ?>
