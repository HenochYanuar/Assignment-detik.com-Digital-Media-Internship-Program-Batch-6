Hi {{ $user->name }}, <br>
Silahkan ganti password anda dengan klik link di bawah ini <br>
@php
$link = Route('fp.new.form');
$link .= '?email=';
$link .= $user->email;
$link .= '&token=';
$link .= $resetPassword->token;
@endphp
<a target="_blank" href="{{ $link }}">Ganti Password</a> <br>
Link akan expired pada {{ $resetPassword->expired }}