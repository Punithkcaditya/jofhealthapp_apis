

                    <!-- Page content -->
                    <div class="container-fluid pt-8">
                       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <h2 class="mb-0">Edit User</h2>
                                        <!-- <?php echo $query[0]->first_name ?> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsives">
                                        <?= $this->include('message/message') ?>
                                        <form action="<?= base_url('editnewuser') ?>" method="POST" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                    <input type="hidden" name="user_id_hidd" value="<?php echo (!empty($query[0]->user_id)) ? $query[0]->user_id : "" ?>"/>
                                                        <div class="form-group">
                                                         
                                                            <label class="form-label">Enter First Name</label>
                                                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter First Name" value="<?= !empty($query[0]->first_name) ? $query[0]->first_name : '' ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Enter Last Name</label>
                                                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter Last Name" value="<?= !empty($query[0]->last_name) ? $query[0]->last_name : '' ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Enter Phone Number</label>
                                                            <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Enter Phone Number" value="<?= !empty($query[0]->phone_number) ? $query[0]->phone_number : '' ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Enter Password</label>
                                                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" value="">
                                                        </div>
                                                        <div class="form-group">
                                                        <label class="form-label"> Select User Status</label>

                                                            <select name="status_ind" id="status_ind" class="form-control" data-validation="required" required>
                                                                <option value="">-- User Status --</option>
                                                                <?php foreach ($userInfo as $row) : ?>
                                                                    <option value="<?php echo $row['status_ind'] ?>" <?php echo (!empty($query[0]->status_ind) && $query[0]->status_ind == $row['status_ind']) ? 'selected' : '' ?>><?php echo (!empty($query[0]->status_ind) && $query[0]->status_ind == 1) ? 'Active' : 'Inactive' ?></option>
                                                                <?php endforeach; ?>
                                                              
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Enter Username Name</label>
                                                            <input type="text" class="form-control" name="username" id="username" placeholder="Enter Username Name" value="<?= !empty($query[0]->username) ? $query[0]->username : '' ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Enter Email Address</label>
                                                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email Address" value="<?= !empty($query[0]->email) ? $query[0]->email : '' ?>" required>
                                                        </div>


                                                        <div class="form-group">
                                                            <label for="role">Role</label>
                                                            <select name="role_id" id="role_id" class="form-control" data-validation="required" required>
                                                                <option value="">-- User Type --</option>
                                                                <?php foreach ($roles as $row) : ?>
                                                                    <option value="<?php echo $row['role_id'] ?>" <?php echo (!empty($query[0]->role_id) && $query[0]->role_id == $row['role_id']) ? 'selected' : '' ?>><?php echo $row['role_name'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>

                                                        </div>


                                                        <div class="form-group">
                                                            <label class="form-label">Confirm Password</label>
                                                            <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirm Password" value="">
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="formGroupExampleInput"><?= 'Add Company Image' ?></label>
                                                            <input type="file" name="file" class="form-control" id="file" onchange="showPreviewcategory(event);"  accept=".png, .jpg, .jpeg" <?php echo !empty($query[0]->profile_pic)  ? '' : 'required' ?> />
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
 