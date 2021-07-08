# MVVM

## references

> https://www.liaoxuefeng.com/wiki/1022910821149312/1108898947791072

model view viewmodel

通过 viewmodel 将 view 和 model 绑定到一起

model 的改变会直接响应到 view 上。

view 的修改也会自动同步到 model

#### example

```
<!-- HTML -->
<p>Hello, <span id="name">Bart</span>!</p>
<p>You are <span id="age">12</span>.</p>
```

```
var person = {
    name: 'Bart',
    age: 12
};

person.name = 'Homer';
person.age = 51;
```

