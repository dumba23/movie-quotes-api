<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body style="width: 100%; height:100%; background: linear-gradient(187.16deg, #181623 100.07%, #191725 100.65%, #0D0B14 98.75%);">
        <div style="padding:140px; max-width:1229px; margin:0 auto;">
            <div style="text-align:center; margin-bottom: 56px;">
                <img src="{{$message->embed(public_path() . '/images/IconMessage.png')}}" style="margin-top: 50px;" alt="Dashboard"/>
                <p style="color: #DDCCAA">MOVIE QUOTES</p>
            </div>
            <div style="margin-bottom:40px; color: #ffffff;">
                <p>Hola {{ $user->username }}!</p>
                <p>Thanks for joining Movie quotes! We really appreciate it. Please click the button below to verify your account:</p>
                <a
                href="{{ config('app.frontend_url') . '/?reset_password=true&token=' . $token }}"
                style=
                "background-color: #E31221;  display:block;
                width: 128px;height:18px; padding-top:8px; padding-bottom:10px;
                border-radius:4px; color: #fff;
                font-weight:400; margin-top: 32px; text-decoration: none;
                text-align:center; font-size: 16px; outline: none;">
                    Verify account
                </a>
                <p>If clicking doesn't work, you can try copying and pasting it to your browser:</p>
                <div style="max-width:1200px;">
                    <p style="word-break: break-all; color: #DDCCAA;">{{ config('app.frontend_url') . '/?reset_password=true&token=' . $token }}</p>
                </div>
                <p>If you have any problems, please contact us: support@moviequotes.ge</p>
                <p>MovieQuotes Crew</p>
            </div>

        </div>
    </body>
</html>
