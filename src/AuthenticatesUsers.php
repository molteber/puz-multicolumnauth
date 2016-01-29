<?php
namespace Puz\MultiColumnAuth;

use Illuminate\Foundation\Auth\AuthenticatesUsers as OrigAuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait AuthenticatesUsers
{
    use OrigAuthenticatesUsers;

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $loginusername = $this->loginUsername();
        $this->validate($request, [
            $loginusername => 'required', 'password' => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);
        $possiblecolumns = $this->loginColumns();
        $templogin = $credentials[$loginusername];
        unset($credentials[$loginusername]);

        foreach ($possiblecolumns as $column) {
            $credentials[$column] = $templogin;
            if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
                return $this->handleUserWasAuthenticated($request, $throttles);
            }
            unset($credentials[$column]);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        return config('puz.multicolumnauth.loginfield', property_exists($this, 'username') ? $this->username : 'email');
    }

    /**
     * Get the possible login columns to verify login with
     *
     * @return array
     */
    public function loginColumns()
    {
        return (array) config('puz.multicolumnauth.columns', property_exists($this, 'username') ? $this->username : 'email');
    }
}
