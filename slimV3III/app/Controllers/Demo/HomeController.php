<?php
namespace App\Controllers\Demo;

use App\Kernel\ControllerAbstract;
use Exception;

class HomeController extends ControllerAbstract
{

    /**
     * Index Action
     *
     * @return string
     */
    public function login()
    {
        $request = $this->getRequest();
        $method = $request->getMethod();
        $params = $request->getParams();

        $this->getService("logger")->debug("entrou em login com baseurl " . base_url() . "método " . $method, $params);
        $data = [
            'baseUrl' => base_url(),
            'title' => 'The Title',
            'subtitle' => 'The SubTitle'
        ];
        if ($request->isPost()) {
            /*
             * |----------------------------------------------------------------
             * | está entrando por POST receberá os parâmetros e irá verificar
             * |----------------------------------------------------------------
             */

            if (empty($params['username'])) {
                $data['error'] = 'Login deve ser preenchido';
                $data['username'] = '';
                $data['password'] = '';
                $data['success'] = '';

                return $this->render('login.twig', $data);
            }
            if (empty($params['password'])) {
                $data['error'] = 'Senha deve ser preenchida';
                $data['username'] = '';
                $data['password'] = '';
                $data['success'] = '';

                return $this->render('login.twig', $data);
            }

            try {
                $falesol = $this->getService('falesol');
                $falesol->setPrimary('users', 'ID');

                $user = $falesol->users()
                    ->where('username', $params['username'])
                    ->where('senha_painel', $params['password'])
                    ->fetch();
            } catch (Exception $e) {
                $this->getService("logger")->critical("Exception " . $e->__toString());
                $data['status'] = $e->getCode();
                $data['message'] = 'Estamos com problemas na base de dados, mas já está sendo solucionado.';
                $data['username'] = '';
                return $this->render('Demo/Home/error.twig', $data);
            }
            if ($user) {
                if (isset($user['error'])) {
                    $data['username'] = '';
                    $data['password'] = '';
                    $data['error'] = $user['error'];
                    $data['success'] = '';

                    return $this->render('login.twig', $data);
                }
                $data['username'] = $user->name;
                $data['error'] = '';
                $data['success'] = '';
                $data['user'] = $user;
                // TODO gerar session para usuário

                return $this->render('Demo/Home/index.twig', $data);
            } else {
                $data['username'] = '';
                $data['password'] = '';
                $data['error'] = 'Usuário não encontrado com essas credenciais';
                $data['success'] = '';

                return $this->render('login.twig', $data);
            }
        } else {
            /*
             * |----------------------------------------------------------------
             * | está entrando por GET e só mostrará a tela a ser preenchida
             * |----------------------------------------------------------------
             */
            $data['username'] = '';
            $data['error'] = '';
            $data['success'] = '';
            $data['user'] = null;

            return $this->render('login.twig', $data);
        }
    }

    /**
     * Index Action
     *
     * @return string
     */
    public function index()
    {
        $this->getService("logger")->debug("entrou em index");
        $data = [
            'baseUrl' => base_url(),
            'title' => 'The Title',
            'subtitle' => 'The SubTitle'
        ];
        try {
            $falesol = $this->getService('falesol');
            $falesol->setPrimary('users', 'ID');
            $user = $falesol->users(81);
            $this->getService("logger")->debug("Leu usuário " . $user->name);
        } catch (Exception $e) {
            $this->getService("logger")->critical("Exception " . $e->__toString());
            $data['status'] = $e->getCode();
            $data['message'] = 'Estamos com problemas na base de dados, mas já está sendo solucionado.';
            $data['username'] = 'Iomar';
            return $this->render('Demo/Home/error.twig', $data);
        }
        $data['username'] = $user->name;
        $data['error'] = '';
        $data['success'] = '';
        $data['user'] = $user;

        return $this->render('Demo/Home/index.twig', $data);
    }

    /**
     * Index Action
     *
     * @return string
     */
    public function profile()
    {
        $this->getService("logger")->debug("entrou em profile");
        $data = [
            'baseUrl' => base_url(),
            'title' => 'The Title',
            'subtitle' => 'The SubTitle'
        ];
        try {
            $falesol = $this->getService('falesol');
            $falesol->setPrimary('users', 'ID');
            $user = $falesol->users(81);
            $this->getService("logger")->debug("Leu usuário " . $user->name);
        } catch (Exception $e) {
            $this->getService("logger")->critical("Exception " . $e->__toString());
            $data['status'] = $e->getCode();
            $data['message'] = 'Estamos com problemas na base de dados, mas já está sendo solucionado.';
            $data['username'] = 'Iomar';
            return $this->render('Demo/Home/error.twig', $data);
        }
        $data['username'] = $user->name;
        $data['error'] = '';
        $data['success'] = '';
        $data['user'] = $user;

        return $this->render('Demo/Home/profile.twig', $data);
    }

    public function logout()
    {
        $this->getService("logger")->debug("entrou em logout");
        session_destroy();
        $this->login();
    }
}
