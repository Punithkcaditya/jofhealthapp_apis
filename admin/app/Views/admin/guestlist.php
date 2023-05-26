

					<!-- Page content -->
					<div class="container-fluid pt-8">
						<!-- bootom top bar -->
                        <?php echo  $this->include('bottomtopbar/topbar') ?>
                        <!-- bottom top bar -->
						<div class="row">
							<div class="col-md-12">
								<div class="card shadow">
									<div class="card-header spacedcontent">
										<h2 class="mb-0">Add New User</h2>
										<a class="btn btn-info mt-1 mb-1" href="<?php echo  base_url('exportexcel')?>">Export Excel</a>
									</div>
									<div class="card-body">
										<div class="table-responsive">

                                        <?php echo  $this->include('message/message') ?>  

											<table id="example" class="table table-striped table-bordered w-100 text-nowrap">
												<thead>
													<tr>
														<th class="wd-15p">Name</th>
														<th class="wd-20p">Email</th>
														<th class="wd-10p">Phone Number</th>
														<th class="wd-25p">Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($guest as $row) : ?>
														<tr>
															<th><?php echo  $row['name'] ?></th>
															<td><?php echo  $row['email'] ?></td>
															<td><?php echo  $row['phone'] ?></td>
												
															<td>

																<a href="<?php echo  base_url('guest_edit/' . $row['guest_list_id']) ?>" class="mx-2 text-decoration-none text-primary"><i class="fa fa-edit"></i></a>

																<a href="<?php echo  base_url('guest_delete/' . $row['guest_list_id']) ?>" class="mx-2 text-decoration-none text-danger" onclick="if(confirm('Are you sure to delete  - <?php echo  $row['name'] ?> from list?') !== true) event.preventDefault()"><i class="fa fa-trash"></i></a>
															</td>
														</tr>
													<?php endforeach; ?>
													<?php if (count($guest) <= 0) : ?>
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
	