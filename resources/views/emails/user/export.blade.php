<div style="background-color: #F2FAFD; padding: 40px;">
    <p> Dear {{ $userName }}, </p>
    <p> A request has been sent for a full data export of your account. </p>
    <p> You can check your data <a href="{{ $fileFullPath }}">here</a>. </p>
    <p> This link will be available for the next <b>48 hours!</b></p>
    With best wishes, <br>
    The team at {{ $websiteName }} <br>
    <a class="link" href="{{ $websiteUrl }}">{{ $websiteUrl }}</a>
</div>