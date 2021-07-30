<?php
/* * * * * * show all errors * * * */
//ini_set('display_startup_errors', 1);
//ini_set('display_errors', 1);
//error_reporting(-1);

class HiBob_API
{
///////////////////////////// SETUP ////////////////////////////////////////

    private $email = 'email@example.net';  // your email for log in
    private $password = 'yourSecretPassword';  // your password

    //time is used to submit time entries for today, for example, you want to submit you was working from 8:00 to 16:00
    private $startTime = '08:00';  //time when you start working
    private $stopTime = '16:00';   //time when you stop working

//////////////////////////////////////////////////////////////////////////////

    private $cookies = '';
    private $userId = '';
    function __construct()
    {
        $this->cookies = tempnam('/tmp', 'cookie'); // notification is OK
        $this->log_in($this->email, $this->password);
    }

    public function log_in($email, $password)
    {
        $curlClient = curl_init('https://app.hibob.com/api/login');

        // Setup request to send json via POST
        $data = array(
            'email' => $email,
            'password' => $password,
        );

        $payload = json_encode($data);

        // Set the content type to application/json
        curl_setopt($curlClient, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        curl_setopt($curlClient, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curlClient, CURLOPT_COOKIEJAR, $this->cookies);
        curl_setopt($curlClient, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curlClient);
        $decoded_result = json_decode($result);
        $this -> userId = $decoded_result -> id;

        curl_close($curlClient);

        return $result;
    }

    public function setClock($status)
    {
        $curlClient = curl_init('https://app.hibob.com/api/attendance/my/punchClock');

        $data = array(
            'timeZone' => "Europe/Kiev",
            'clockAction' => $status,
        );

        $payload = json_encode($data);

        curl_setopt($curlClient, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        curl_setopt($curlClient, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curlClient, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlClient, CURLOPT_COOKIEFILE, $this->cookies);

        $result = curl_exec($curlClient);

        curl_close($curlClient);

        print_r('Clock is set successfully ');
        return $result;
    }

    public function setTimeEntries($date)
    {

        $startTime = $date . 'T' . $this->startTime;
        $stopTime = $date . 'T' . $this->stopTime;
        $url = 'https://app.hibob.com/api/attendance/employees/'. $this ->userId .'/attendance/entries?forDate=' . $date;
        $curlClient = curl_init($url);

        $data = array(array(
            'start' => $startTime,
            'end' => $stopTime,
        ));

        $payload = json_encode($data);

        curl_setopt($curlClient, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        curl_setopt($curlClient, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curlClient, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlClient, CURLOPT_COOKIEFILE, $this->cookies);

        $result = curl_exec($curlClient);

        curl_close($curlClient);

        print_r('Time entries is set successfully ');

        return $result;
    }
}