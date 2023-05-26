<?php

namespace App\Controllers;

use App\Models\Scheduleappointments;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;

class Scheduleappointment extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */

    public function __construct()
    {
        parent::__construct();
        $this->scheduleappointments = new Scheduleappointments();
        $this->session = \Config\Services::session();
   
        // Build your query using the Query Builder
    }

    public function index()
    {
        $db = Database::connect();
        $check = $this->authUser();
        if ($check) {
            $header_values = getallheaders();
            $userdata = $this->users->select('user_id')->where('login_token', $header_values['token'])->first();
            if (empty($userdata['user_id'])) {
                return $this->fail('No User Found!!');
            }
            $validation = $this->validate([
                'time' => 'required',
                "date" => "required",
            ]);
            if (!$validation) {
                return $this->failValidationErrors($this->validator->getErrors());
            }
            $dateString = esc($this->request->getVar('date'));
            $format = 'd M, Y';
            $date = \DateTime::createFromFormat($format, $dateString);
            $formattedDate = $date->format('Y-m-d');
            $time_str = esc($this->request->getVar('time'));
            $service_id = esc($this->request->getVar('service_id'));
            $time_unix = strtotime($time_str);
            $time_format = date("H:i:s", $time_unix);
            $isbooking = ['isnewbooking' => 0];
            $this->scheduleappointments->where("user_id", $userdata['user_id'])->where("appointment_date < CURDATE()")
            ->set($isbooking)
            ->update();
            $checkUser = $this->scheduleappointments
                ->where("user_id=", $userdata['user_id'])
                ->countAllResults();
            if ($checkUser > 0) {
                $resultmain  = $db->table('schedule_appointment')->select('appointment_time')->select('appointment_id')->where("appointment_date", $formattedDate)->where('user_id', $userdata['user_id'])->get()->getResult();
                if(!empty($resultmain)){
                    foreach($resultmain as $rows){
                        $previous_appointment_time = new \DateTime($rows->appointment_time);
                        $appointment_id[] = $rows->appointment_id;
                        $new_appointment_time[] = $previous_appointment_time->modify('2 hours')->format('H:i:s');
                    }
                    $result = array();
                    for ($i = 0; $i < count($new_appointment_time); $i++) {
                        if($time_format <= $new_appointment_time[$i]){
                            $result[$i] = array($new_appointment_time[$i], $appointment_id[$i]);
                        }
                    }
    
                   if(!empty($result)){
                    $scheduleinfo = [
                        'appointment_time' => $time_format,
                        'appointment_date' => $formattedDate,
                        'service_id' => $service_id,
                        'user_id' => $userdata['user_id'],
                        'isnewbooking' => 1
                    ];
                    $response = [
                        'message' => 'Appointment Scheduled Successfully',
                    ];
                    foreach($result as $val){
                        $newid = $val[1];
                    }
    
                    $usersId = $this->scheduleappointments
                    ->where("user_id", $userdata['user_id'])
                    ->where("appointment_id ", $newid)
                    ->set($scheduleinfo)
                    ->update();
                    if ($usersId) {
                        return $this->respondCreated($response);
                    }
                }else {
             
                    $scheduleinfo = [
                        'appointment_time' => $time_format,
                        'appointment_date' => $formattedDate,
                        'service_id' => $service_id,
                        'user_id' => $userdata['user_id'],
                        'isnewbooking' => 1
                    ];
                    $response = [
                        'message' => 'Appointment Scheduled Successfully',
                    ];
                    $usersId = $this->scheduleappointments->insert($scheduleinfo);
                }
    
                if ($usersId) {
                    return $this->respondCreated($response);
                }
                return $this->fail('Sorry! Appointment Sceduled Fail');
               
               }else{
                $scheduleinfo = [
                    'appointment_time' => $time_format,
                    'appointment_date' => $formattedDate,
                    'service_id' => $service_id,
                    'user_id' => $userdata['user_id'],
                    'isnewbooking' => 1
                ];
                $response = [
                    'message' => 'Appointment Scheduled Successfully',
                ];
                $usersId = $this->scheduleappointments->insert($scheduleinfo);
                if ($usersId) {
                    return $this->respondCreated($response);
                }
               }
             return $this->fail('Sorry! Appointment Sceduled Fail');
            } else {
             
                $scheduleinfo = [
                    'appointment_time' => $time_format,
                    'appointment_date' => $formattedDate,
                    'service_id' => $service_id,
                    'user_id' => $userdata['user_id'],
                    'isnewbooking' => 1
                ];
                $response = [
                    'message' => 'Appointment Scheduled Successfully',
                ];
                $usersId = $this->scheduleappointments->insert($scheduleinfo);
            }

            if ($usersId) {
                return $this->respondCreated($response);
            }
            return $this->fail('Sorry! Appointment Sceduled Fail');
        } else {
            return $this->fail('Invalid Token !!');
        }

    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    function new () {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}