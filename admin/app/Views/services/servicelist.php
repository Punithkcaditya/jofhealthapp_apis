

					<!-- Page content -->
					<div class="container-fluid pt-8">
                    	<?= $this->include('bottomtopbar/topbar') ?>

						<div class="row">
							<div class="col-md-12">
								<div class="card shadow">
									<div class="card-header">
										<h2 class="mb-0"><?= $title ?></h2>
									</div>
									<div class="card-body">
										<div class="table-responsive">

                                        <?= $this->include('message/message') ?> 

											<table id="example" class="table table-striped table-bordered w-100 text-nowrap">
												<thead>
													<tr>
														<th class="wd-5p">SI No</th>
														<th class="wd-10p">Service Name</th>
														<th class="wd-10p">Service Thumbnail</th>
														<th class="wd-10p">Status</th>
														<th class="wd-15p">Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php $i = 1;
													
													?>
                                                    
													<?php 
                                                    // echo '<pre>';
                                                    // print_r($services);
                                                    foreach ($services as $key => $sid) : ?>
													
														<tr>
															<th><?= $i++ ?></th>
															<td><?= $sid['servicename'] ?></td>													
															<td style="text-align: center;"> <img style="width: 139px;" id="blah" src="<?= base_url("uploads/" . $sid['Service_thumbnail']) ?>" /> </td>
															<td>
																<p class="text-muted"><?php echo (!empty($sid['status_ind'])) ? '<i class="fa fa-check" aria-hidden="true" style="color:lightgreen;"></i>' : '<i class="fa fa-times" aria-hidden="true" style="color:red;"></i>'; ?></p>
															</td>
															
															<td>

																<a href="<?= base_url($edit_service.'/' . $sid['service_id']) ?>" class="mx-2 text-decoration-none text-primary"><i class="fa fa-edit"></i></a>
																<a href="<?= base_url('manage_booking/' . $sid['service_id']) ?>" class="mx-2 text-decoration-none text-primary"><i class="fa fa-cog"></i></a>
																<a href="<?= base_url($delete_service.'/' . $sid['service_id']) ?>" class="mx-2 text-decoration-none text-danger" onclick="if(confirm('Are you sure to delete  - <?= $sid['servicename'] ?> from list?') !== true) event.preventDefault()"><i class="fa fa-trash"></i></a>
															</td>
														</tr>
													<?php endforeach; ?>
													<?php if (count($services) <= 0) : ?>
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
	