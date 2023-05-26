<!-- Page content -->
<div class="container-fluid pt-8">

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="mb-0"><?=$title?></h2>

                </div>
                <div class="card-body">
                    <div class="table-responsives">
                        <form action="<?=base_url("savenewserviceinfo")?>" method="POST" enctype="multipart/form-data">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <!-- <h2 class="mb-0"></h2> -->
                                        </div>

                                        <div class="card-body">
                                            <?=$this->include("message/message")?>

                                            <div class="row">
                                                <div class="col-md-12">


                                                    <div class="form-group">
                                                        <input type="hidden" name="service_id_hidd" id="service_id_hidd"
                                                            value="<?php echo !empty($query[0]["service_id"]) ? $query[0]["service_id"] : ""; ?>" />
                                                        <label class="form-label"><?=$pade_title3?></label>
                                                        <input type="text" class="form-control" name="service_name"
                                                            id="service_name" placeholder="Enter Service Name"
                                                            value="<?=!empty($query[0]["servicename"]) ? $query[0]["servicename"] : ""?>"
                                                            required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label"><?="Enter Duration"?></label>
                                                        <input type="number" class="form-control" name="serviceduration"
                                                            id="serviceduration" placeholder="Enter Duration"
                                                            value="<?=!empty($query[0]["duration"]) ? $query[0]["duration"] : ""?>"
                                                            required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label">Choose Service
                                                            Status</label>
                                                        <select name="status_ind" id="status_ind" class="form-control"
                                                            required>
                                                            <option value="">-- Service Status --
                                                            </option>

                                                            <option value="1"
                                                                <?php echo !empty($query[0]["status_ind"]) && $query[0]["status_ind"] == 1 ? "selected" : ""; ?>>
                                                                Active</option>
                                                            <option value="0"
                                                                <?php echo !empty($query[0]["status_ind"]) && $query[0]["status_ind"] == 0 ? "selected" : ""; ?>>
                                                                Inactive</option>
                                                        </select>
                                                    </div>


                                                    <div class="form-group">
                                                        <div class="preview">
                                                            <img id="file-ip-1-preview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">

                                                        <?php if (!empty($query[0]["Service_thumbnail"])) {?>

                                                        <img style="width: 139px;" id="blah"
                                                            src="<?=base_url("uploads/" . $query[0]["Service_thumbnail"])?>" />

                                                        <?php } else {?>
                                                        <div id="blah"></div>
                                                        <?php }?>

                                                    </div>


                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput"><?=$page_heading?></label>
                                                        <input type="file" name="file" class="form-control" id="file"
                                                            onchange="showPreview(event);" accept=".png, .jpg, .jpeg"
                                                            value="<?=!empty($query[0]["Service_thumbnail"]) ? $query[0]["Service_thumbnail"] : ""?>"
                                                            <?php echo !empty($query[0]["service_id"]) ? "" : "required"; ?> />

                                                    </div>

                                                    <div class="form-group">
                                                        <div class="preview">
                                                            <img id="file-ip-2-preview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">

                                                        <?php if (!empty($query[0]["subscriptionplane_img"])) {?>

                                                        <img style="width: 139px;" id="containtwo"
                                                            src="<?=base_url("uploads/" . $query[0]["subscriptionplane_img"])?>" />

                                                        <?php } else {?>
                                                        <div id="containtwo"></div>
                                                        <?php }?>

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput"><?=$pade_title10?></label>
                                                        <input type="file" name="image" class="form-control"
                                                            id="fileupload" onchange="showPreviewcategory(event);"
                                                            accept=".png, .jpg, .jpeg"
                                                            <?php echo !empty($query[0]["service_id"]) ? "" : "required"; ?> />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput"><?=$pade_title1?></label>
                                                        <textarea class="form-control" name="editor1" rows="3"
                                                            placeholder="Add Description here..."
                                                            required><?php echo htmlspecialchars(!empty($query[0]["servicesdescription"]) ? $query[0]["servicesdescription"] : ""); ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label"><?=$pade_title7?></label>
                                                        <input type="text" class="form-control" name="service_heading1"
                                                            id="service_heading1" placeholder="Enter Service Heading 1"
                                                            value="<?=!empty($query[0]["service_heading1"]) ? $query[0]["service_heading1"] : ""?>"
                                                            required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput"><?=$pade_title2?></label>
                                                        <textarea class="form-control" id="editor2" rows="5"
                                                            placeholder="Add Conditions here..." name="editor2"
                                                            required><?php echo htmlspecialchars(!empty($query[0]["servicescondition"]) ? $query[0]["servicescondition"] : ""); ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label"><?=$pade_title8?></label>
                                                        <input type="text" class="form-control" name="service_heading2"
                                                            id="service_heading2" placeholder="Enter Service Heading 2"
                                                            value="<?=!empty($query[0]["service_heading2"]) ? $query[0]["service_heading2"] : ""?>"
                                                            required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput"><?=$pade_title6?></label>
                                                        <textarea class="form-control" id="editor3" name="editor3"
                                                            rows="5" placeholder="Add Services here..."
                                                            required><?php echo htmlspecialchars(!empty($query[0]["services"]) ? $query[0]["services"] : ""); ?></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label"><?=$pade_title11?></label>
                                                        <input type="text" class="form-control"
                                                            name="service_plan_heading" id="service_plan_heading"
                                                            placeholder="Enter Service Plan Heading"
                                                            value="<?=!empty($query[0]["service_plan_heading"]) ? $query[0]["service_plan_heading"] : ""?>"
                                                            required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label"><?=$pade_title12?></label>
                                                        <input type="text" class="form-control" name="button_heading"
                                                            id="button_heading" placeholder="Enter Service Button Text"
                                                            value="<?=!empty($query[0]["button_heading"]) ? $query[0]["button_heading"] : ""?>"
                                                            required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label"><?=$pade_title23?></label>
                                                        <input type="text" class="form-control" name="subscription_button_heading"
                                                            id="subscription_button_heading" placeholder="Enter Service Button Text"
                                                            value="<?=!empty($query[0]["subscription_button_heading"]) ? $query[0]["subscription_button_heading"] : ""?>"
                                                            required>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Dynamic fields for subcription prices -->



                                    <!-- Dynamic fields for plan desc -->
                                    <div class="card shadow" style="padding-bottom: 74px;">
                                        <div class="card-header">
                                            <h2 class="mb-0"><?=$pade_title16?></h2>
                                        </div>
                                        <div class="card-body" id="addanother">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="grid-margin">
                                                        <div class="">
                                                            <div class="table-responsive">
                                                                <table
                                                                    class="table card-table table-vcenter text-nowrap  align-items-center"
                                                                    id="plandesc">
                                                                    <thead class="thead-light">
                                                                        <tr>
                                                                            <th><input class='check_all' type='checkbox'
                                                                                    onclick="select_all()" /> Select All
                                                                            </th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if (!empty($query[0]['service_descriptions'])) {
                                                                        $k = 0;
                                                                        $p = 1;
                                                                        $fraction = count($query);
                                                                        for ($j = 1; $j <= round($fraction); $j++) {?>

                                                                        <tr>
                                                                            <td><input type='checkbox' class='case' />
                                                                            </td>
                                                                            <td> <input type='text' class="form-control"
                                                                                    placeholder="Enter Subscription Plan Description"
                                                                                    id='location<?=$p++?>'
                                                                                    name='subscrpldsc[]'
                                                                                    value="<?=!empty($query[$k]['service_descriptions']) ? $query[$k++]['service_descriptions'] : ''?>"
                                                                                    required /></td>
                                                                        </tr>
                                                                        <?php }} else {?>
                                                                        <!-- below -->
                                                                        <tr>
                                                                            <td><input type='checkbox' class='case' />
                                                                            </td>
                                                                            <td> <input type='text' class="form-control"
                                                                                    placeholder="Enter Subscription Plan Description"
                                                                                    id='location<?=$i++?>'
                                                                                    name='subscrpldsc[]' /></td>
                                                                        </tr>

                                                                        <?php }?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="count_items" id="count_items" value="<?=$i?>" />
                                        </div>
                                        <div class="col-md-12 button_holder">
                                            <div class="card-body">
                                                <button type="submit"
                                                    class="btn rounded-0 btn-primary bg-gradient">Submit</button>
                                                <button type="button" class="btn btn-info mt-1 mb-1" id="addmore"><i
                                                        class="fas fa-plus-circle"></i> Add Fields</button>
                                                <button type="button" class="btn hideing btn-danger mt-1 mb-1"
                                                    id="delete"><i class="fas fa-minus"></i> Delete Fields</button>
                                            </div>
                                        </div>
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

<script>
// CKEDITOR.replace('editor1');
// CKEDITOR.replace('editor2');
// CKEDITOR.replace('editor3');

function displayDivDemo(id, elementValue) {

    document.getElementById(id).style.display = elementValue.value == 1 ? 'block' : 'none';
}
</script>