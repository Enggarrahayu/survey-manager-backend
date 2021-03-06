<?php

namespace App\Http\Teamwork;

use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Application;
use Mpociot\Teamwork\Events\UserInvitedToTeam;
use App\Models\User;
use Illuminate\Support\Str;
class Teamwork
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Create a new Teamwork instance.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get the currently authenticated user or null.
     */
    public function user()
    {
        return $this->app->auth->user();
    }

    /**
     * Invite an email adress to a team.
     * Either provide a email address or an object with an email property.
     *
     * If no team is given, the current_team_id will be used instead.
     *
     * @param string|User $user
     * @param null|Team $team
     * @param callable $success
     * @return TeamInvite
     * @throws \Exception
     */
    public function inviteToTeam($user, $team = null, callable $success = null)
    {
        if (is_null($team)) {
            $team = $this->user()->current_team_id;
        } elseif (is_object($team)) {
            $team = $team->getKey();
        } elseif (is_array($team)) {
            $team = $team['id'];
        }

        if (is_object($user) && isset($user->email)) {
            $email = $user->email;
        } elseif (is_string($user)) {
            $email = $user;
        } else {
            throw new \Exception('The provided object has no "email" attribute and is not a string.');
        }

        $random = Str::random(10);
        $invite = $this->app->make(Config::get('teamwork.invite_model'));
        $invite->invitation_key = $random;
        if(User::where('email', '=', request('email'))->exists()){
            $invite->user_id = User::where('email', $email)->first()->id;
        }
        $invite->team_id = $team;
        $invite->type = 'invite';
        $invite->email = $email;
        $invite->invitation_status = 0;
        $invite->save();

        if (! is_null($success)) {
            event(new UserInvitedToTeam($invite));
            $success($invite);
        }

        return $invite;
    }

    /**
     * Checks if the given email address has a pending invite for the
     * provided Team.
     * @param $email
     * @param Team|array|int $team
     * @return bool
     */
    public function hasPendingInvite($email, $team)
    {
        if (is_object($team)) {
            $team = $team->getKey();
        }
        if (is_array($team)) {
            $team = $team['id'];
        }

        return $this->app->make(Config::get('teamwork.invite_model'))->where('email', '=', $email)->where('team_id', '=', $team)->first() ? true : false;
    }

    /**
     * @param $token
     * @return mixed
     */
}
