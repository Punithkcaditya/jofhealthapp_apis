<div class="container-fluid pt-8">

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="mb-0"><?=$title1?></h2>

                </div>
                <div class="card-body">
                    <div class="table-responsives">
                        <form action="<?=base_url("savenewsubscription")?>" method="POST" enctype="multipart/form-data"
                            id="myform">

                            <!-- Dynamic fields for subcription prices -->

                            <div class="card shadow" style="padding-bottom: 74px;">
                                <div class="card-header">
                                    <h2 class="mb-0"><?=$title2?></h2>
                                </div>
                                <input type="hidden" name="subscrption_id_hidd" id="subscrption_id_hidd"
                                    value="<?php echo !empty($query["subscription_id"]) ? $query["subscription_id"] : ""; ?>" />
                                <div class="card-body" id="addanother">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="grid-margin">
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table
                                                            class="table card-table table-vcenter text-nowrap  align-items-center"
                                                            id="planstable">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th> <?php if (empty($query['subscription_id'])) {?><input
                                                                            class='check_all' type='checkbox'
                                                                            onclick="select_all()" /> Select All
                                                                        <?php }?>
                                                                    </th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($query['subscription_id'])) {?>
                                                                <tr>
                                                                    <?php if (empty($query['subscription_id'])) {?><td>
                                                                        <input type='checkbox' class='case' />
                                                                    </td>
                                                                    <?php }?>

                                                                    <td>
                                                                        <div class="form-group">
                                                                            <input type="number" class="form-control"
                                                                                name="age_start" id="age_start<?=$i?>"
                                                                                placeholder="Enter Age From"
                                                                                value="<?=!empty($query["age_start"]) ? $query["age_start"] : ""?>"
                                                                                required>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <input type="number" class="form-control"
                                                                                name="age_end" id="age_end<?=$i?>"
                                                                                placeholder="Enter Age To"
                                                                                value="<?=!empty($query["age_end"]) ? $query["age_end"] : ""?>"
                                                                                required>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <select name="service_choosen"
                                                                                id="service_choosen<?=$i?>"
                                                                                class="form-control" required>
                                                                                <option value="">-- Choose Service
                                                                                </option>
                                                                                <?php foreach ($services as $rowdata): ?>
                                                                                <option
                                                                                    value="<?php echo $rowdata['service_id'] ?>"
                                                                                    <?php echo (!empty($query["service_name"]) && $query["service_name"] == $rowdata['service_id']) ? 'selected' : '' ?>>
                                                                                    <?php echo $rowdata['servicename'] ?>
                                                                                </option>
                                                                                <?php endforeach;?>
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php } else {?>
                                                                <!-- below -->
                                                                <tr>
                                                                    <td width="1em"><input type='checkbox'
                                                                            class='case' />
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <input type="number" class="form-control"
                                                                                name="age_start[]" id="age_start<?=$i?>"
                                                                                placeholder="Enter Age From" required>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <input type="number" class="form-control"
                                                                                name="age_end[]" id="age_end<?=$i?>"
                                                                                placeholder="Enter Age To" required>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <select name="service_choosen[]"
                                                                                id="service_choosen<?=$i?>"
                                                                                class="form-control" required>
                                                                                <option value="">-- Choose Service
                                                                                </option>
                                                                                <?php foreach ($services as $rowdata): ?>
                                                                                <option
                                                                                    value="<?php echo $rowdata['service_id'] ?>">
                                                                                    <?php echo $rowdata['servicename'] ?>
                                                                                </option>
                                                                                <?php endforeach;?>
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php }?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="count_plan_items" id="count_plan_items"
                                        value="<?=$i?>" />
                                </div>
                                <div class="col-md-12 button_holder">
                                    <div class="card-body">
                                        <button type="submit"
                                            class="btn rounded-0 btn-primary bg-gradient">Submit</button>
                                        <?php if (empty($query['subscription_id'])) {?>
                                        <button type="button" class="btn btn-info mt-1 mb-1" id="addmoreplans"><i
                                                class="fas fa-plus-circle"></i> Add
                                            Fields</button>
                                        <button type="button" class="btn hideing btn-danger mt-1 mb-1"
                                            id="deleteplans"><i class="fas fa-minus"></i> Delete Fields</button>
                                        <?php }?>
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
function displayDivDemo(id, elementValue) {

    document.getElementById(id).style.display = elementValue.value == 1 ? 'block' : 'none';
}




const form = document.getElementById('myform');
form.addEventListener('submit', function(event) {

    const count_plan_items = document.getElementById('count_plan_items');
    const hiddenValue = count_plan_items.value;
    for (let i = 1; i <= hiddenValue; i++) {
        const dynamicId = "age_start" + hiddenValue;
        const dynamicIdTwo = "age_end" + hiddenValue;
        const agestart = document.getElementById(dynamicId);
        const age_end = document.getElementById(dynamicIdTwo);
        if (agestart && age_end) {
            const agestartValue = agestart.value;
            const age_endValue = age_end.value;
                
            if (agestartValue >= age_endValue) {
                alert("Error: Age start value must be less than age end value.");
                event.preventDefault();
            }
        }

    }

});






$("#addmoreplans").on('click', function() {
    var counts = $("#count_plan_items").val();
    $.ajax({
        type: 'POST',
        url: '<?=base_url()?>/appendplandetails',
        dataType: "json",
        data: {
            counts: ++counts
        },
        success: function(data) {
            console.log(data['services']);
            var datahtml = "<tr><td width='1em'><input type='checkbox' class='case'/></td>";
            datahtml +=
                "<td><div class='form-group'><input type='number' class='form-control' name='age_start[]' id='age_start" +
                data['status'] + "' placeholder='Enter Age From' required> </div> </td>";
            datahtml +=
                "<td><div class='form-group'><input type='number' class='form-control' name='age_end[]' id='age_end" +
                data['status'] + "' placeholder='Enter Age To' required> </div> </td>";
            datahtml +=
                "<td><div class='form-group'><select  name='service_choosen[]' id='service_choosen" +
                data['status'] +
                "' class='form-control' required> <option value=''> Choose Service </option> </select> </div> </td></tr>";
            $('#planstable').append(datahtml);
            $('#count_plan_items').val(data['status']);
            var option = document.createElement("option");
            $.each(data['services'], function(index, value) {
                $('#service_choosen' + data["status"] + '').append($('<option>', {
                    value: value['service_id'],
                    text: value['servicename']
                }));
            });

            // var select = document.getElementById("service_choosen" + data['status'] + "");
            // for (var s = 0; s < data['services'].length; s++) {
            //     option.text = data['services'][s]['servicename'];
            //     option.value = data['services'][s]['service_id'];
            //     optionhtml += option;
            // }
            // select.append(optionhtml);
        }
    });
});
</script>