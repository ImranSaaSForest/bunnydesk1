<div>
    Dear {{$name}}!
    <br>
    <br>
    <div>I hope this email finds you well. We are thrilled to welcome you to the {{$company}} family, and we are excited
        to have you on board.</div>
    <br>

    <div> Your username is your email address {{$email}}</div>
    <br>
    <div>To get started with your onboarding process, please click on the link below. This link will redirect you to the
        authentication page, where you will be able to set your new password and access your account.</div>
    <br>
    <div style="width: 100%;">
        <a href="{{$url}}"
            style="padding: 8px 10px;background:#B39800;color:white;width:fit-content;margin:0 auto;text-decoration:none;border-radius:5px">Reset
            Password</a>
    </div>
    <br>
    This password reset link will expire in 60 minutes.
    <br>
    <br>
    @if($hr)
    <div>
        If you have any questions or need assistance at any point during the onboarding process, please do not hesitate
        to reach out to our HR team at {{$hr->email}} or contact {{$hr->last_name?$hr->name.'
        '.$hr->last_name:$hr->name}} directly.
    </div>
    @endif
    <br>
    <div> Once again, welcome to {{$company}}. We look forward to working with you and hope you have a fantastic journey
        with us.</div>
    <br>
    <div>
        Best regards,
        <br>
        {{$company}}
    </div>
</div>
