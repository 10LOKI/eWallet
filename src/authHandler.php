<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Database;
use App\User;
header('Content-Type: application/json');

$response = ['success' => false , 'message' => 'Erreur'];
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    try
    {
        $db = new Database();
        $userObj = new User($db);

        $action = $_POST['action'] ?? '';
        if($action === 'register')
        {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if(empty($name) || empty($email) || empty($password))
            {
                $response['message'] = "veuillez saisir des informations valides";
            }
            else
            {
                $userObj -> username = $name;
                    $userObj -> email = $email;
                $userObj -> password = $password;
                if($userObj -> register())
                {
                    $response['success'] = true;
                    $response['message'] = "inscription reussie";
                }
                else
                {
                    $response['message'] = "Erreur";
                }
            }
        }
        elseif ($action === 'login')
        {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            if(empty($email)|| empty($password))
            {
                $response['message'] = "veuillez saisir les informations";
            }
            else
            {
                $userObj -> username = $email;
                if($userObj-> login($password))
                {
                    $response['success'] = true;
                    $response['message'] = "Connexion en cours";
                    $response['redirect'] = 'index.php';
                }
                else
                {
                    $response['message'] = "incorrecte infos";
                }
            }
        }
    }
   catch (\Exception $e)
   {
    $response['message'] = "Erreur" . $e->getMessage();
   }
}
echo json_encode($response);
exit;
?>