<a href="{{ config('app.url') }}">{{ config('app.name') }}</a>
<p>
    {{ __('下記のURLをクリックしてパスワードの再発行手続きをしてください。') }}<br>
</p>
<p>
    <a href="{{ $url }}">{{ $url }}</a>
</p>

<p>
    {{ __('※URLの有効期限は30分以内です。有効期限が切れた場合は、お手数ですがもう一度最初からお手続きを行ってください。') }}<br>
</p>
