Hello Dear {{$user->name }}

would you kindly confirm the Email throuh this link : {{route('verify', $user->verification_token)}}