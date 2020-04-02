<?php
class UserController extends Controller
{
	public function __construct($_parameters)
	{
		$this->parameters = $_parameters;

		$this->model = new UserModel();
		$this->view = new UserView();
		$this->dataAccess = new UserDA(DatabaseConnector::getInstance()->getConnection());

        // Fill the array with the names of the methods
        $this->controllersMethodsArr = array();
        $this->controllersMethodsArr[] = "login";
        $this->controllersMethodsArr[] = "register";
        $this->controllersMethodsArr[] = "getAllUsers";
	}

    public function login()
    {
    	if(empty($this->parameters) || !isset($this->parameters) || !is_array($this->parameters))
        {
        	echo $this->view->exception();
        	exit();
        }

    	if(!array_key_exists("email", $this->parameters))
        {
        	echo $this->view->exception();
        	exit();
        }

        if(!array_key_exists("password", $this->parameters))
        {
        	echo $this->view->exception();
        	exit();
        }

        $this->model->email = $this->parameters["email"];
        $this->model->password = hash('sha256', $this->parameters["password"]);

        $asoArr = array("email" => $this->model->email, "password" => $this->model->password); 

        $userData = $this->dataAccess->select($asoArr);

        if($userData)
        {
        	$this->model->accessToken = $userData["access_token"];
        	echo $this->view->renderObject("access_token", $this->model->accessToken);
		}
		else
		{
			echo $this->view->fail();
		}

		exit();
    }


    function getAllUsers()
    {
        $data = $this->dataAccess->selectAll();

        foreach ($data as $user) 
        {
            unset($user["password"]); 
            unset($user["access_token"]); 
            $users[] = $user;
        }

        echo $this->view->renderArray($users);
        exit();
    }
}