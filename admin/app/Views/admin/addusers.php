

					<!-- Page content -->
					<div class="container-fluid pt-8">
						<!-- bootom top bar -->
                        <?= $this->include('bottomtopbar/topbar') ?>
                        <!-- bottom top bar -->
						<div class="row">
							<div class="col-md-12">
								<div class="card shadow">
									<div class="card-header">
										<h2 class="mb-0">Add New User</h2>
									</div>
									<div class="card-body">
										<div class="table-responsive">

                                        <?= $this->include('message/message') ?>  

											<table id="example" class="table table-striped table-bordered w-100 text-nowrap">
												<thead>
													<tr>
														<th class="wd-15p">First name</th>
														<th class="wd-15p">Last name</th>
														<th class="wd-20p">Email</th>
														<th class="wd-15p">Username</th>
														<th class="wd-10p">Phone Number</th>
														<th class="wd-10p">Status</th>
														<!-- <th class="wd-10p">Created Date</th> -->
														<th class="wd-25p">Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($users as $row) : ?>
														<tr>
															<th><?= $row['first_name'] ?></th>
															<td><?= $row['last_name'] ?></td>
															<td><?= $row['email'] ?></td>
															<td><?= $row['username'] ?></td>
															<td><?= $row['phone_number'] ?></td>
															<td>
																<p class="text-muted"><?php echo (!empty($row['status_ind'])) ? '<i class="fa fa-check" aria-hidden="true" style="color:lightgreen;"></i>' : '<i class="fa fa-times" aria-hidden="true" style="color:red;"></i>'; ?></p>
															</td>
															<!-- <td>
                                                <p class="text-muted"><?php echo $row['created_date'] ?></p>
                                            </td> -->
															<td>

																<a href="<?= base_url($user_edit.'/' . $row['user_id']) ?>" class="mx-2 text-decoration-none text-primary"><i class="fa fa-edit"></i></a>

																<a href="<?=  base_url($user_delete.'/' . $row['user_id']) ?>" class="mx-2 text-decoration-none text-danger" onclick="if(confirm('Are you sure to delete  - <?= $row['first_name'] ?> from list?') !== true) event.preventDefault()"><i class="fa fa-trash"></i></a>
															</td>
														</tr>
													<?php endforeach; ?>
													<?php if (count($users) <= 0) : ?>
														<tr>
															<td class="p-1 text-center" colspan="4">No result found</td>
														</tr>
													<?php endif ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>

							</div>
						</div>
					
					</div>
	