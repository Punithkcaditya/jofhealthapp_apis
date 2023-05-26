<!-- Page content -->
<div class="container-fluid pt-8">

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="mb-0">Add New User</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsives">
                        <form action="<?php echo base_url('addnewuser') ?>" method="POST" enctype="multipart/form-data">
                            <?= $this->include('message/message') ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Enter First Name</label>
                                        <input type="text" class="form-control" name="first_name" id="first_name"
                                            placeholder="Enter First Name" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Enter Last Name</label>
                                        <input type="text" class="form-control" name="last_name" id="last_name"
                                            placeholder="Enter Last Name" value="" required>
                                    </div>
                                   <div class="form-group">
                                        <label class="form-label">Enter Phone Number</label>
                                        <input type="number" class="form-control"  name="phone_number" id="phone_number"
                                            placeholder="Enter Valid Ten Digit Number" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Enter Password</label>
                                        <input type="password" class="form-control" name="password" id="password"
                                            placeholder="Enter Password Address" value="" required>
                                    </div>
                                    <div class="form-group">

                                        <label class="form-label"> Select User Status</label>

                                        <select name="status_ind" id="status_ind" class="form-control"
                                            data-validation="required" required>
                                            <option value="">-- User Status --</option>

                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>

            



                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Enter Username Name</label>
                                        <input type="text" class="form-control" name="username" id="username"
                                            placeholder="Enter Username Name" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Enter Email Address</label>
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Enter Email Address" value="" required>
                                    </div>





                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select name="role_id" id="role_id" class="form-control"
                                            data-validation="required" required>
                                            <option value="">-- User Type --</option>
                                            <?php foreach ($roles as $row) : ?>
                                            <option value="<?php echo $row['role_id'] ?>"
                                                <?php echo (!empty($query['role_id']) && $query['role_id'] == $row['role_id']) ? 'selected' : '' ?>>
                                                <?php echo $row['role_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>

                                    </div>


                                    <div class="form-group">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" name="cpassword" id="cpassword"
                                            placeholder="Enter Email Address" value="" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="formGroupExampleInput"><?= 'Add Company Image' ?></label>
                                        <input type="file" name="file" class="form-control" id="file"
                                            onchange="showPreviewcategory(event);" accept=".png, .jpg, .jpeg"
                                            required />
                                    </div>

                                    <div class="form-group">
                                        <div class="preview">
                                            <img id="file-ip-2-preview">
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <?php if (!empty($query[0]->profile_pic)) { ?>

                                        <img style="width: 139px;" id="blah"
                                            src="<?= base_url("uploads/" . $query[0]->profile_pic) ?>" />
                                        <?php } else {?>
                                        <div id="containtwo"></div>
                                        <?php }?>

                                    </div>

                     



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