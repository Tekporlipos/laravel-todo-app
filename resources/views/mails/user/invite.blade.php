<x-mail::message>
<h1>Hello,</h1>
You have been invited to join <strong>{{$organizer->name}}</strong>
<p>To accept the invite, clink on the accept invite button below.</p>
<small>Ignore this email if you believe it is unexpected</small>
<x-mail::button :url="$url">
Accept Invite
</x-mail::button>
</x-mail::message>
