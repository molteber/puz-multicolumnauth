# Laravel MultiAuth
Ever felt like you need to allow both a `username` and `email` field? Then you're in luck!  
Puz MultiAuth enables you to allow your users to use whatever userfield they want to when they log in!

# Installation
    composer require puz/multiauth

You will need to update your `config/app.php` by adding the service provider to your application.  
Add this under the provider section: `Puz\MultiAuth\ServiceProvider::class`

To configure your fields and columns you'll need to add my configuration files. Simply do this by running the artisan command `php artisan vendor:publish`

The last thing you have to do now, is to replace the current Authorization trait. In the default AuthController class, you will see that laravel have included `use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;`. If you're still using both the authentication and registration of users, simply change this to: `use Puz\MultiColumnAuth\AuthenticatesAndRegistersUsers;`. If you don't need registration, simply change it to `use Puz\MultiColumnAuth\AuthenticatesUsers;`, or do the same if you have a custom registration trait maybe and need to include this `AuthenticatesUsers` in your custom class.
In short, there are two available trais:  
`Puz\MultiColumnAuth\AuthenticatesAndRegistersUsers`  
`Puz\MultiColumnAuth\AuthenticatesUsers`

# How to
**Select which columns I can use for login**  
In your newly created config file from `php artisan vendor:publish` located in `config/puz/multiauth.php`, there exists a array with a key named `columns`. This key takes an array as a value with the columns you want the users to log in with. Ex: `['username', 'email']` will check for the `username` column and then `email`.

**Select name of login field**  
In the same config file, you have a key `loginfield`. This is set to `login` as default. This means that the input field where you usually inputs a username or email, you use the name `login`, or to something you freely choose yourself.

# How does it work?
If you have specified columns to log in with, if will loop through each one and try to log in. If it succeeds by one of then (first one to succeed), then it will log in with that.

**There is a catch tho..** If you for an example have set the `columns` to allow `username`, `phone` and `email`, in that specific order, then you have to be alert what the username might be. Someone can have created a username as someone else email. If they by any chance have the same password, someone will have access to another account.

As a fallback if you want to use laravel's default, you can simply change the columns in the config to a null value, like this

    [
        'columns' => null,
    ]
