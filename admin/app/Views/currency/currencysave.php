<!-- Page content -->
<div class="container-fluid pt-8">

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="mb-0"><?php echo  $title ?></h2>

                </div>
                <div class="card-body">
                    <div class="table-responsives">
                        <form action="<?php echo  base_url("savenewcurrency") ?>" method="POST" enctype="multipart/form-data">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <!-- <h2 class="mb-0"></h2> -->
                                        </div>

                                        <div class="card-body">
                                            <?php echo  $this->include('message/message') ?>

                                            <div class="row">
                                                <div class="col-md-12">


                                                    <div class="form-group">
                                                        <label class="form-label"><?php echo  $pade_title1 ?></label>
                                                        <input type="hidden" class="form-control" name="currency_hid_id"
                                                            id="currency_hid_id"  value="<?php echo !empty( $query[0]['currencyId'])? $query[0]['currencyId']: ""; ?>" />
                                                        <input type="text" class="form-control" name="currencyName"
                                                            id="currencyName" placeholder="Enter Currency Name" value="<?php echo !empty( $query[0]['currencyName'])? $query[0]['currencyName']: ""; ?>"
                                                            required />
                                                    </div>
    
                                                    <div class="form-group">
                                                        <label class="form-label"><?php echo  $pade_title2 ?></label>
                                                        <input type="text" name="currencySymbol"
                                                            id="currencySymbol" class="form-control" required
                                                            placeholder="Enter Currency Symbol" value="<?php echo !empty( $query[0]['currencySymbol'])? $query[0]['currencySymbol']: ""; ?>">

                                                    </div>


                                                    <div class="form-group">
                                                        <label class="form-label"><?php echo $pade_title3 ?></label>
                                                        <select name="currency_status" id="currency_status" class="form-control"
                                                            required>
                                                            <option value="">-- Service Status --
                                                            </option>

                                                            <option value="1" <?php echo !empty(
                                                                                        $query[0]['status']) &&
                                                                                    $query[0]['status'] ==1 ? "selected": ""; ?>>
                                                                Active</option>
                                                            <option value="0" <?php echo !empty(
                                                                                        $query[0]['status']) && $query[0]['status'] == 0 ? "selected": ""; ?>>
                                                                Inactive</option>
                                                        </select>
                                                    </div>


                                                    <div class="form-group">
                                                    <label class="form-label"><?php echo  $pade_title4 ?></label>
                                                        <input type="text" name="sortOrder"
                                                            id="sortOrder" class="form-control" required
                                                            placeholder="Enter Sort Order" value="<?php echo !empty( $query[0]['sortOrder'])? $query[0]['sortOrder']: ""; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label"><?php echo $pade_title5 ?></label>
                                                        <select name="defaultCurrency" id="defaultCurrency" class="form-control"
                                                            required>
                                                            <option value="">-- Choose Default --
                                                            </option>

                                                            <option value="1" <?php echo !empty(
                                                                                        $query[0]['defaultCurrency']) &&
                                                                                    $query[0]['defaultCurrency'] ==1 ? "selected": ""; ?>>
                                                                Yes</option>
                                                            <option value="2" <?php echo !empty(
                                                                                        $query[0]['defaultCurrency']) && $query[0]['defaultCurrency'] == 2 ? "selected": ""; ?>>
                                                                No</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-12" style="text-align: center;">
                                                        <div class="d-grid gap-1">
                                                            <button
                                                                class="btn rounded-0 btn-primary bg-gradient">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
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

