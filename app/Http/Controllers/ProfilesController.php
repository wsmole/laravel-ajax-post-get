<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use knowmadic\Forms\ProfileForm;
use View;

class ProfilesController extends Controller
{

	/**
	 * @var ProfileForm
	 */
	protected $profileForm;
	/**
	 * @param ProfileForm $profileForm
	 */
	function __construct(ProfileForm $profileForm)
	{
		$this->profileForm = $profileForm;
		$this->beforeFilter('currentUser', ['only' => ['edit', 'update']]);
	}
	/**
	 * /username
	 *
	 * @param $username
	 * @return Response
	 */
	public function show($username)
	{
		$user = $this->getUserByUsername($username);
		return View::make('profiles.show')->withUser($user);
	}
	/**
	 * /profiles/username/edit
	 *
	 * @param $username
	 * @return mixed
	 */
	public function edit($username)
	{
		$user = $this->getUserByUsername($username);
		return View::make('profiles.edit')->withUser($user);
	}
	/**
	 * Update a user's profile
	 *
	 * @param $username
	 * @return mixed
	 * @throws Laracasts\Validation\FormValidationException
	 */
	public function update($username)
	{
		$user = $this->getUserByUsername($username);
		$input = Input::only('name', 'phone', 'password', 'email');
		$this->profileForm->validate($input);
		$user->profile->fill($input)->save();
		return Redirect::route('profile.edit', $user->username);
	}
	/**
	 * Fetch user
	 * (You can extract this to repository method)
	 *
	 * @param $username
	 * @return mixed
	 */
	public function getUserByUsername($username)
	{
		return User::with('profile')->whereUsername($username)->firstOrFail();
	}


}
