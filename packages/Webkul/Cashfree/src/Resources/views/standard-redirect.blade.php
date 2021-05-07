<?php $cashfreeStandard = app('Webkul\Cashfree\Payment\Standard') ?>

<body data-gr-c-s-loaded="true" cz-shortcut-listen="true">
    You will be redirected to the Cashfree website in a few seconds.
    

    <form action="{{ $cashfreeStandard->getcashfreeUrl() }}" id="cashfree_standard_checkout" method="POST">
        <input value="Click here if you are not redirected within 10 seconds..." type="submit">

        @foreach ($cashfreeStandard->getFormFields() as $name => $value)

            <input type="hidden" name="{{ $name }}" value="{{ $value }}">

        @endforeach
    </form>

    <script type="text/javascript">
        document.getElementById("cashfree_standard_checkout").submit();
    </script>
</body>