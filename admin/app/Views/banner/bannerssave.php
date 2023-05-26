<!-- Page content -->
<div class="container-fluid pt-8">

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="mb-0"><?php echo $title?></h2>

                </div>
                <div class="card-body">
                    <div class="table-responsives">
                        <form id="user_form" name="user_form" action="<?php echo base_url('savebanners')?>"
                            method="POST" enctype="multipart/form-data">

                            <?php echo $this->include('message/message')?>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="preview" >
                                        <img id="file-ip-1-preview" style="max-width: 50em;width: auto;">
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                <input type="hidden" name="banner_id_hidd" id="banner_id_hidd"
                                        value="<?php echo (!empty($query['banner_id'])) ? $query['banner_id'] : '' ?>" />
                                    <?php if (!empty($query['banner'])) {?>

                                    <img style="width: 139px;" id="blah"
                                        src="<?php echo base_url("uploads/" . $query['banner'])?>" />

                                    <?php } else {?>
                                    <div id="blah"></div>
                                    <?php }?>

                                </div>

                                <div class="form-group col-md-12">
                                    <label for="formGroupExampleInput"><?php echo $pade_title2 ?></label>
                                    <input type="file" name="file" class="form-control" id="file"
                                        onchange="showPreview(event);" accept=".png, .jpg, .jpeg"
                                        <?php echo (!empty($query['banner_id'])) ? '' : 'required' ?> />
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="formGroupExampleInput"><?php echo $pade_title3 ?></label>
                                    <select name="status_ind" id="status_ind" class="form-control"
                                        data-validation="required" required>
                                        <option value="">-- User Status --</option>

                                        <option value="1" <?php echo (!empty($query['status_ind']) && $query['status_ind']==1) ? 'selected' : '' ?>>Active</option>
                                        <option value="0" <?php echo (empty($query['status_ind'])) ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="formGroupExampleInput"><?php echo $pade_title4 ?></label>
                                    <input type="text" class="form-control" name="display_order" id="display_order" placeholder="Enter Display Order" value="<?= !empty($query['display_order']) ? $query['display_order'] : '' ?>" required>
                                </div>
                                <div class="col-md-12" style="text-align: center;">
                                    <div class="d-grid gap-1">
                                        <button class="btn rounded-0 btn-primary bg-gradient">Save</button>
                                    </div>
                                </div>

                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>