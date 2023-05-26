<?php

namespace App\Controllers;

use App\Models\UserInformation;
use App\Models\Users;
use App\Models\ServiceModel;
use App\Models\Scheduleappointments;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class UserInfo extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function __construct()
    {
        parent::__construct();
        $this->users = new Users();
        $this->usersinfor = new UserInformation();
        $this->scheduleappointments = new Scheduleappointments();
        $this->servicemodel = new ServiceModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {

        $check = $this->authUser();
        if ($check) {
            $header_values = getallheaders();
            $userdata = $this->users->select('user_id')->where('login_token', $header_values['token'])->first();
            if (empty($userdata['user_id'])) {
                return $this->fail('No User Found!!');
            }
            $validation = $this->validate([
                'age' => 'required',
                "gender" => "required",
                "weight" => "required",
                "height" => "required",
                "workout" => "required",
                "healthcondition" => 'required',
            ]);
            if (!$validation) {
                return $this->failValidationErrors($this->validator->getErrors());
            }
            $checkUser = $this->usersinfor
                ->where("user_id=", $userdata['user_id'])
                ->countAllResults();
            if ($checkUser > 0) {
                $usersnfo = [
                    'age' => esc($this->request->getVar('age')),
                    'gender' => esc($this->request->getVar('gender')),
                    'weight' => esc($this->request->getVar('weight')),
                    'height' => esc($this->request->getVar('height')),
                    'workout' => esc($this->request->getVar('workout')),
                    'healthcondition' => esc($this->request->getVar('healthcondition')),
                ];
                $usersId = $this->usersinfor
                    ->where("user_id", $userdata['user_id'])
                    ->set($usersnfo)
                    ->update();
                $response = [
                    'message' => 'User Information Updated Successfully',
                ];
            } else {
                $usersnfo = [
                    'age' => esc($this->request->getVar('age')),
                    'gender' => esc($this->request->getVar('gender')),
                    'weight' => esc($this->request->getVar('weight')),
                    'height' => esc($this->request->getVar('height')),
                    'workout' => esc($this->request->getVar('workout')),
                    'healthcondition' => esc($this->request->getVar('healthcondition')),
                    'user_id' => $userdata['user_id'],
                ];
                $response = [
                    'message' => 'User Information Saved Successfully',
                ];
                $usersId = $this->usersinfor->insert($usersnfo);
            }

            if ($usersId) {
                return $this->respondCreated($response);
            }
            return $this->fail('Sorry! no student created');
        } else {
            return $this->fail('Invalid Token !!');
        }

    }

    public function getalluserinfo()
    {
        $check = $this->authUser();
        if ($check) {
            $header_values = getallheaders();
            $userdata = $this->users->select('user_id')->where('login_token', $header_values['token'])->first();
            if (empty($userdata['user_id'])) {
                return $this->fail('No User Found!!');
            }
            $db = \Config\Database::connect();
            $builder = $db->table('userinformations');
            $query = $builder->select('userinformations.*')
            ->join('users', 'users.user_id = userinformations.user_id', 'left')
            ->select('users.user_name, users.user_phone, users.user_email')
            ->where('userinformations.user_id', $userdata['user_id'])
            ->get();
            $result = $query->getRow();
     
            $buildertwo = $db->table('schedule_appointment');
            // DATE_FORMAT(time_column, '%h:%i %p');
            $querytwo = $buildertwo->select('schedule_appointment.isnewbooking, schedule_appointment.appointment_date, schedule_appointment.appointment_time')
            ->join('users', 'users.user_id = schedule_appointment.user_id', 'left')
            ->join('services', 'services.service_id = schedule_appointment.service_id', 'left')
            ->select('services.servicename')
            ->where('users.user_id', $userdata['user_id'])
            ->orderBy('appointment_id', 'DESC')
            ->get();
            $resulttwo = $querytwo->getResult();

            $result = $query->getRow();
            $data = [
                'message' => 'success',
                'data_user' => $result,
                'data_appointments' => $resulttwo,
            ];
            return $this->respond($data, 200);

        } else {
            return $this->fail('Invalid Token !!');
        }
    }

}
