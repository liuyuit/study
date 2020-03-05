# jquery时间倒计时特效

## references

> http://www.jq22.com/webqd1074

html

```
<button id="get_code">获取验证码</button>
```

js

```
$('#get_code').click(function () {
        if (display === false) {
            return false;
        }

        alert('获取验证码成功');

        let getCodeBtn = $('#get_code');
        getCodeBtn.text('5s');
        var timer = setInterval(function() {
            let seconds = parseFloat(getCodeBtn.text());
            let btnText = seconds - 1 + 's';
            getCodeBtn.text(btnText);
            if (btnText == '0s') {
                clearInterval(timer);
                getCodeBtn.text('获取验证码');
                display = true;
            }
        }, 1000);
        display = false;
    });
```

