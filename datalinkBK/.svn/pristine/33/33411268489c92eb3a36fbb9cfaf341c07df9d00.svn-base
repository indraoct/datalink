<?php

class AuthController extends BaseController {


    public function doLogin(){
	
		$input = Input::all();
		$remember = (isset($input['remember'])) ? true : null;
        $param = array(
            'user_name' => $input['username'],
            'password' => $input['password']
        );
		
        if(Auth::attempt($param,$remember)) {

			$user = new User;
			$user_data = $user->getUserData($param['user_name']); 
			$user_privi = $user->getUserPrivi($user_data->id_group); 
			
			Session::put('owner', Crypt::encrypt(1));
			Session::put('user_id', Crypt::encrypt($user_data->id_user));
			Session::put('user_name', $user_data->user_name);
			Session::put('user_privi', $user_privi);
			
			return Redirect::to('home');

        }else{
		
			return Redirect::to('login')->with('error', 'Username atau password salah')->withInput();
			
        }

    }


    public function doSignUp(){
		
        $rules = array(
            'username' => 'required|min:5|unique:m_user,user_name',
            'email'    => 'required|email|unique:m_user,user_email',
            'password' => 'required|min:5:Same:rpassword',
            'rpassword' => 'required|min:5'
        );

        $validator  = Validator::make(Input::all(),$rules);

        // $token = Input::has('_token') ? Input::get('_token') : '';

        // $sessionToken = Session::token();

        // if($token ===$sessionToken){

            if($validator->fails()){

                return  Redirect::to('login#daftar')
                    ->withErrors($validator->messages()->all()) // send back all errors to the login form
                    ->withInput(Input::except('password'));

            }else{
				/*Start Eloquent*/
				$user = new User;

				$user->user_name	= Input::get('username');
				$user->user_email	= Input::get('email');
				$user->user_pass  	= Hash::make(Input::get('password'));

				if($user->save()){

					return Redirect::to('login#daftar/success');

				}else{

					$this->layout->title = "Error";
					$this->layout->content = View::make('errors.default');
				}

				Session::put('_token', sha1(microtime())); //put a new token
            }

        // }else{

            // Session::put('_token', sha1(microtime())); //put a new token
            // return Redirect::to('login#daftar')->with('token_expired',true);
        // }
    }

    public function update(){

        $password    =  (Input::get('password') !='') ? Input::get('rpassword') : '';
        $rpassword  =  (Input::get('password') !='') ? Input::get('rpassword') : '';


        $rules = array(

            'username' => 'required|min:6',
            'email'    => 'required|email',

        );

        if($password !=='')
            $rules = array_merge($rules,array('password' => 'required|min:6'));

        $validator  = Validator::make(Input::all(),$rules);

        $token = Input::has('_token') ? Input::get('_token') : '';

        $sessionToken = Session::token();

        if($token ===$sessionToken){

            if($validator->fails()){

                return  Redirect::to('profile')
                    ->withErrors($validator) // send back all errors to the login form
                    ->withInput(Input::except('password'));

            }else{

                if($password==$rpassword){

                    /*Start Eloquent*/
                    $user = User::find(Auth::user()->id);

                    $user->username  = Input::get('username');
                    $user->email     = Input::get('email');

                    if($password !='')
                        $user->password  = Hash::make($password);

                    if($user->save()){

                        return Redirect::to('profile');

                    }else{

                        $this->layout->title = "Error";
                        $this->layout->content = View::make('errors.default');
                    }

                    Session::put('_token', sha1(microtime())); //put a new token
                }
                else {

                    return Redirect::to('profile')->with('not_match',true)->withInput(Input::except('password'));
                }

            }

        }else{

            Session::put('_token', sha1(microtime())); //put a new token
            return Redirect::to('profile')->with('token_expired',true);
        }
    }


}