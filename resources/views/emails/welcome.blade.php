Hello {{$user->name}}
thank you for creating account .please verify  from this link:
{{route('verify', $user->verification_token)}}